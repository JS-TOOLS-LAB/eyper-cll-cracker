<?php
/**
 * EYPER CLL - Ultra-Fast Hash Verifier with Caching
 * Developed by JS Intergrated Labs
 */

class HashVerifier {
    private $target_hash;
    private $hash_type;
    private $verify_func;
    private $cache = [];
    private $cache_hits = 0;
    private $cache_misses = 0;
    private $start_time;
    private $attempts = 0;
    
    // Hash patterns for detection
    const HASH_PATTERNS = [
        32 => 'MD5',
        40 => 'SHA1',
        56 => 'SHA224',
        64 => 'SHA256',
        96 => 'SHA384',
        128 => 'SHA512'
    ];
    
    /**
     * Constructor
     * @param string $target_hash Hash to crack
     */
    public function __construct($target_hash) {
        $this->target_hash = $target_hash;
        $this->hash_type = $this->fastDetectType($target_hash);
        $this->verify_func = $this->createVerifyFunction();
        $this->start_time = microtime(true);
    }
    
    /**
     * Fast hash type detection (O(1) by length)
     * @param string $hash Hash to detect
     * @return string Hash type
     */
    private function fastDetectType($hash) {
        $len = strlen($hash);
        
        // Check for bcrypt first (most common in modern systems)
        if ($len == 60 && $hash[0] == '$' && $hash[1] == '2') {
            return 'bcrypt';
        }
        
        // Fast lookup by length
        return self::HASH_PATTERNS[$len] ?? 'unknown';
    }
    
    /**
     * Create optimized verification function
     * @return callable Verification function
     */
    private function createVerifyFunction() {
        $target = $this->target_hash;
        
        switch($this->hash_type) {
            case 'MD5':
                return function($pass) use ($target) {
                    return md5($pass) === $target;
                };
            case 'SHA1':
                return function($pass) use ($target) {
                    return sha1($pass) === $target;
                };
            case 'SHA224':
                return function($pass) use ($target) {
                    return hash('sha224', $pass) === $target;
                };
            case 'SHA256':
                return function($pass) use ($target) {
                    return hash('sha256', $pass) === $target;
                };
            case 'SHA384':
                return function($pass) use ($target) {
                    return hash('sha384', $pass) === $target;
                };
            case 'SHA512':
                return function($pass) use ($target) {
                    return hash('sha512', $pass) === $target;
                };
            case 'bcrypt':
                return function($pass) use ($target) {
                    return password_verify($pass, $target);
                };
            default:
                // Mixed mode - try all common algorithms
                return function($pass) use ($target) {
                    return md5($pass) === $target ||
                           sha1($pass) === $target ||
                           hash('sha256', $pass) === $target ||
                           password_verify($pass, $target);
                };
        }
    }
    
    /**
     * Verify a password against target hash
     * @param string $password Password to test
     * @return bool True if match found
     */
    public function verify($password) {
        $this->attempts++;
        
        // Check cache first (LRU)
        if (isset($this->cache[$password])) {
            $this->cache_hits++;
            return $this->cache[$password];
        }
        
        $this->cache_misses++;
        $result = ($this->verify_func)($password);
        
        // Manage cache size (LRU)
        if (count($this->cache) >= 10000) {
            // Remove 20% oldest entries
            $remove = (int)(10000 * 0.2);
            $this->cache = array_slice($this->cache, $remove, null, true);
        }
        
        $this->cache[$password] = $result;
        return $result;
    }
    
    /**
     * Get hash type
     * @return string Hash type
     */
    public function getHashType() {
        return $this->hash_type;
    }
    
    /**
     * Get performance statistics
     * @return array Stats
     */
    public function getStats() {
        $total = $this->cache_hits + $this->cache_misses;
        $elapsed = microtime(true) - $this->start_time;
        $speed = $elapsed > 0 ? round($this->attempts / $elapsed) : 0;
        
        return [
            'attempts' => $this->attempts,
            'speed' => $speed,
            'cache_hits' => $this->cache_hits,
            'cache_hit_rate' => $total > 0 ? round(($this->cache_hits / $total) * 100, 2) : 0,
            'cache_size' => count($this->cache),
            'elapsed' => round($elapsed, 2)
        ];
    }
    
    /**
     * Reset statistics
     */
    public function resetStats() {
        $this->attempts = 0;
        $this->cache_hits = 0;
        $this->cache_misses = 0;
        $this->start_time = microtime(true);
    }
}
?>
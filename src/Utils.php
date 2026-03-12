<?php
/**
 * EYPER CLL - Utility Functions
 * Developed by JS Intergrated Labs
 */

/**
 * Clear the screen (cross-platform)
 */
function clearScreen() {
    if (php_sapi_name() === 'cli') {
        echo "\033[2J\033[;H";
    } else {
        echo str_repeat("\n", 100);
    }
}

/**
 * Format time in human readable format
 * @param float $seconds Time in seconds
 * @return string Formatted time
 */
function formatTime($seconds) {
    if ($seconds < 0.001) {
        return round($seconds * 1000000) . 'µs';
    }
    if ($seconds < 1) {
        return round($seconds * 1000) . 'ms';
    }
    if ($seconds < 60) {
        return round($seconds, 2) . 's';
    }
    if ($seconds < 3600) {
        $m = floor($seconds / 60);
        $s = round($seconds % 60, 1);
        return $m . 'm ' . $s . 's';
    }
    if ($seconds < 86400) {
        $h = floor($seconds / 3600);
        $m = floor(($seconds % 3600) / 60);
        return $h . 'h ' . $m . 'm';
    }
    $d = floor($seconds / 86400);
    $h = floor(($seconds % 86400) / 3600);
    return $d . 'd ' . $h . 'h';
}

/**
 * Format bytes to human readable
 * @param int $bytes Size in bytes
 * @return string Formatted size
 */
function formatBytes($bytes) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $i = 0;
    while ($bytes >= 1024 && $i < count($units) - 1) {
        $bytes /= 1024;
        $i++;
    }
    return round($bytes, 2) . ' ' . $units[$i];
}

/**
 * Generate random string
 * @param int $length Length of string
 * @return string Random string
 */
function randomString($length = 10) {
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $chars_len = strlen($chars);
    $result = '';
    for ($i = 0; $i < $length; $i++) {
        $result .= $chars[random_int(0, $chars_len - 1)];
    }
    return $result;
}

/**
 * Validate hash format
 * @param string $hash Hash to validate
 * @return bool True if valid format
 */
function isValidHash($hash) {
    // bcrypt
    if (preg_match('/^\$2[ayb]\$[0-9]{2}\$[A-Za-z0-9\.\/]{53}$/', $hash)) {
        return true;
    }
    
    // MD5, SHA1, SHA256, SHA512 (hex)
    if (preg_match('/^[a-f0-9]{32,128}$/i', $hash)) {
        return true;
    }
    
    return false;
}

/**
 * Secure string compare (timing attack safe)
 * @param string $a First string
 * @param string $b Second string
 * @return bool True if equal
 */
function secureCompare($a, $b) {
    if (function_exists('hash_equals')) {
        return hash_equals($a, $b);
    }
    
    // Fallback implementation
    $len = strlen($a);
    if ($len !== strlen($b)) {
        return false;
    }
    
    $result = 0;
    for ($i = 0; $i < $len; $i++) {
        $result |= ord($a[$i]) ^ ord($b[$i]);
    }
    return $result === 0;
}

/**
 * Get system memory usage
 * @return array Memory stats
 */
function getMemoryUsage() {
    $usage = memory_get_usage();
    $peak = memory_get_peak_usage();
    
    return [
        'current' => $usage,
        'current_fmt' => formatBytes($usage),
        'peak' => $peak,
        'peak_fmt' => formatBytes($peak)
    ];
}

/**
 * Check if running in Termux
 * @return bool True if in Termux
 */
function isTermux() {
    return getenv('TERMUX_VERSION') !== false || 
           strpos(getenv('PATH'), 'com.termux') !== false;
}

/**
 * Show error message and exit
 * @param string $message Error message
 */
function errorExit($message) {
    echo "\033[31m❌ Error: " . $message . "\033[0m\n";
    exit(1);
}

/**
 * Show success message
 * @param string $message Success message
 */
function successMessage($message) {
    echo "\033[32m✅ " . $message . "\033[0m\n";
}

/**
 * Show warning message
 * @param string $message Warning message
 */
function warningMessage($message) {
    echo "\033[33m⚠️  " . $message . "\033[0m\n";
}

/**
 * Show info message
 * @param string $message Info message
 */
function infoMessage($message) {
    echo "\033[36mℹ️  " . $message . "\033[0m\n";
}

/**
 * Get input with hidden typing (for passwords)
 * @param string $prompt Prompt message
 * @return string Input
 */
function hiddenInput($prompt) {
    echo $prompt;
    system('stty -echo');
    $input = trim(fgets(STDIN));
    system('stty echo');
    echo "\n";
    return $input;
}

/**
 * Create directory if not exists
 * @param string $path Directory path
 * @param int $permissions Permissions (octal)
 * @return bool Success
 */
function ensureDirectory($path, $permissions = 0755) {
    if (!file_exists($path)) {
        return mkdir($path, $permissions, true);
    }
    return true;
}

/**
 * Log message to file
 * @param string $message Message to log
 * @param string $level Log level
 */
function logMessage($message, $level = 'INFO') {
    $logFile = ROOT_PATH . '/logs/app.log';
    ensureDirectory(dirname($logFile));
    
    $timestamp = date('Y-m-d H:i:s');
    $logLine = "[$timestamp] [$level] $message" . PHP_EOL;
    
    file_put_contents($logFile, $logLine, FILE_APPEND | LOCK_EX);
}

/**
 * Get all files in directory
 * @param string $dir Directory path
 * @param string $extension Filter by extension
 * @return array List of files
 */
function getFiles($dir, $extension = null) {
    if (!file_exists($dir)) {
        return [];
    }
    
    $files = scandir($dir);
    $result = [];
    
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        
        $path = $dir . '/' . $file;
        if (is_file($path)) {
            if ($extension === null || pathinfo($file, PATHINFO_EXTENSION) === $extension) {
                $result[] = $file;
            }
        }
    }
    
    return $result;
}

/**
 * Truncate string with ellipsis
 * @param string $string Input string
 * @param int $length Max length
 * @return string Truncated string
 */
function truncate($string, $length = 50) {
    if (strlen($string) <= $length) {
        return $string;
    }
    return substr($string, 0, $length - 3) . '...';
}

/**
 * Generate hash ID for files
 * @return string Unique ID
 */
function generateHashId() {
    return date('Ymd-His') . '-' . randomString(6);
}

/**
 * Save hash to file
 * @param string $hash Hash to save
 * @return string Filename
 */
function saveHash($hash) {
    $id = generateHashId();
    $filename = ROOT_PATH . "/hashes/hash-{$id}.txt";
    
    ensureDirectory(ROOT_PATH . '/hashes');
    file_put_contents($filename, $hash . PHP_EOL);
    
    return $filename;
}

/**
 * Load hash from file
 * @param string $filename Filename
 * @return string|false Hash or false on error
 */
function loadHash($filename) {
    $path = ROOT_PATH . '/hashes/' . $filename;
    if (!file_exists($path)) {
        return false;
    }
    
    return trim(file_get_contents($path));
}
?>
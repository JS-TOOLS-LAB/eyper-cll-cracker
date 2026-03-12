<?php
/**
 * EYPER CLL - Session Management
 * Developed by JS Intergrated Labs
 * 
 * Simple file-based session handling for CLI
 * Compatible with Android/Termux (no LOCK_EX)
 */

class Session {
    private static $sessionFile = ROOT_PATH . '/data/session.dat';
    private static $data = null;
    
    /**
     * Initialize session
     */
    private static function init() {
        if (self::$data !== null) return;
        
        if (file_exists(self::$sessionFile)) {
            $content = file_get_contents(self::$sessionFile);
            self::$data = unserialize($content) ?: [];
        } else {
            self::$data = [];
        }
    }
    
    /**
     * Save session data
     */
    private static function save() {
        if (!file_exists(dirname(self::$sessionFile))) {
            mkdir(dirname(self::$sessionFile), 0700, true);
        }
        
        // Use fopen/fwrite instead of file_put_contents with LOCK_EX
        $fp = fopen(self::$sessionFile, 'w');
        if ($fp) {
            fwrite($fp, serialize(self::$data));
            fclose($fp);
            chmod(self::$sessionFile, 0600);
        }
    }
    
    /**
     * Set session value
     * @param string $key Key
     * @param mixed $value Value
     */
    public static function set($key, $value) {
        self::init();
        self::$data[$key] = $value;
        self::save();
    }
    
    /**
     * Get session value
     * @param string $key Key
     * @param mixed $default Default value
     * @return mixed
     */
    public static function get($key, $default = null) {
        self::init();
        return self::$data[$key] ?? $default;
    }
    
    /**
     * Check if logged in
     * @return bool
     */
    public static function isLoggedIn() {
        self::init();
        return isset(self::$data['logged_in']) && self::$data['logged_in'] === true;
    }
    
    /**
     * Get username
     * @return string
     */
    public static function getUsername() {
        self::init();
        return self::$data['user'] ?? 'Unknown';
    }
    
    /**
     * Get login time
     * @return int|null
     */
    public static function getLoginTime() {
        self::init();
        return self::$data['login_time'] ?? null;
    }
    
    /**
     * Get session age in seconds
     * @return int|null
     */
    public static function getSessionAge() {
        $loginTime = self::getLoginTime();
        if ($loginTime) {
            return time() - $loginTime;
        }
        return null;
    }
    
    /**
     * Destroy session
     */
    public static function destroy() {
        self::$data = [];
        if (file_exists(self::$sessionFile)) {
            unlink(self::$sessionFile);
        }
    }
    
    /**
     * Get all session data
     * @return array
     */
    public static function getAll() {
        self::init();
        return self::$data;
    }
    
    /**
     * Check if session key exists
     * @param string $key Key
     * @return bool
     */
    public static function has($key) {
        self::init();
        return isset(self::$data[$key]);
    }
    
    /**
     * Remove session key
     * @param string $key Key
     */
    public static function remove($key) {
        self::init();
        unset(self::$data[$key]);
        self::save();
    }
    
    /**
     * Clear all session data
     */
    public static function clear() {
        self::$data = [];
        self::save();
    }
    
    /**
     * Regenerate session ID (creates new file)
     */
    public static function regenerate() {
        $oldFile = self::$sessionFile;
        $oldData = self::$data;
        
        // Create new session file
        self::$sessionFile = ROOT_PATH . '/data/session_' . uniqid() . '.dat';
        self::save();
        
        // Remove old session file
        if (file_exists($oldFile)) {
            unlink($oldFile);
        }
    }
}

/**
 * Helper function to redirect to cracker
 */
function redirectToCracker() {
    require_once ROOT_PATH . '/eyper-cll.php';
    exit;
}

/**
 * Helper function to redirect to login
 */
function redirectToLogin() {
    require_once ROOT_PATH . '/login.php';
    exit;
}

/**
 * Helper function to redirect to start
 */
function redirectToStart() {
    require_once ROOT_PATH . '/start.php';
    exit;
}
?>
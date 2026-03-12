<?php
/**
 * EYPER CLL - Authentication System
 * Developed by JS Intergrated Labs
 * 
 * File-based authentication without database
 * Compatible with Android/Termux (no LOCK_EX support)
 */

class Auth {
    private static $userFile = ROOT_PATH . '/data/user-password.pwd';
    
    /**
     * Create a new user
     * @param string $username Username
     * @param string $password Password
     * @return bool Success
     */
    public static function createUser($username, $password) {
        // Validate username (alphanumeric + underscore only)
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            return false;
        }
        
        // Hash password
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        // Create data directory if not exists
        if (!file_exists(dirname(self::$userFile))) {
            mkdir(dirname(self::$userFile), 0700, true);
        }
        
        // Check if user already exists
        if (self::userExists($username)) {
            return false;
        }
        
        // Append to user file (no LOCK_EX for Android/Termux compatibility)
        $line = $username . ':' . $hash . "\n";
        
        // Use fopen/fwrite instead of file_put_contents with LOCK_EX
        $fp = fopen(self::$userFile, 'a');
        if ($fp) {
            fwrite($fp, $line);
            fclose($fp);
            
            // Set proper permissions
            chmod(self::$userFile, 0600);
            return true;
        }
        
        return false;
    }
    
    /**
     * Check if user exists
     * @param string $username Username to check
     * @return bool True if exists
     */
    public static function userExists($username) {
        if (!file_exists(self::$userFile)) {
            return false;
        }
        
        $lines = file(self::$userFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            // Skip comments
            if (strpos($line, '#') === 0) continue;
            
            $parts = explode(':', $line, 2);
            if (count($parts) == 2 && $parts[0] === $username) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Verify login credentials
     * @param string $username Username
     * @param string $password Password
     * @return bool True if login successful
     */
    public static function login($username, $password) {
        if (!file_exists(self::$userFile)) {
            return false;
        }
        
        $lines = file(self::$userFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            // Skip comments
            if (strpos($line, '#') === 0) continue;
            
            $parts = explode(':', $line, 2);
            if (count($parts) == 2 && $parts[0] === $username) {
                if (password_verify($password, $parts[1])) {
                    Session::set('user', $username);
                    Session::set('logged_in', true);
                    Session::set('login_time', time());
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Logout user
     */
    public static function logout() {
        Session::destroy();
    }
    
    /**
     * Get all users (for admin purposes)
     * @return array List of usernames
     */
    public static function getAllUsers() {
        if (!file_exists(self::$userFile)) {
            return [];
        }
        
        $users = [];
        $lines = file(self::$userFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, '#') === 0) continue;
            $parts = explode(':', $line, 2);
            if (count($parts) == 2) {
                $users[] = $parts[0];
            }
        }
        
        return $users;
    }
    
    /**
     * Get user count
     * @return int Number of users
     */
    public static function getUserCount() {
        return count(self::getAllUsers());
    }
    
    /**
     * Change user password
     * @param string $username Username
     * @param string $newPassword New password
     * @return bool Success
     */
    public static function changePassword($username, $newPassword) {
        if (!file_exists(self::$userFile)) {
            return false;
        }
        
        $lines = file(self::$userFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $newContent = [];
        $found = false;
        
        foreach ($lines as $line) {
            if (strpos($line, '#') === 0) {
                $newContent[] = $line;
                continue;
            }
            
            $parts = explode(':', $line, 2);
            if (count($parts) == 2 && $parts[0] === $username) {
                // Update this user's password
                $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
                $newContent[] = $username . ':' . $newHash;
                $found = true;
            } else {
                $newContent[] = $line;
            }
        }
        
        if ($found) {
            // Write back to file (no LOCK_EX)
            $fp = fopen(self::$userFile, 'w');
            if ($fp) {
                fwrite($fp, implode("\n", $newContent) . "\n");
                fclose($fp);
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Delete user
     * @param string $username Username to delete
     * @return bool Success
     */
    public static function deleteUser($username) {
        if (!file_exists(self::$userFile)) {
            return false;
        }
        
        $lines = file(self::$userFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $newContent = [];
        $found = false;
        
        foreach ($lines as $line) {
            if (strpos($line, '#') === 0) {
                $newContent[] = $line;
                continue;
            }
            
            $parts = explode(':', $line, 2);
            if (count($parts) == 2 && $parts[0] === $username) {
                // Skip this user (delete)
                $found = true;
                continue;
            } else {
                $newContent[] = $line;
            }
        }
        
        if ($found) {
            // Write back to file (no LOCK_EX)
            $fp = fopen(self::$userFile, 'w');
            if ($fp) {
                fwrite($fp, implode("\n", $newContent) . "\n");
                fclose($fp);
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Initialize user file with header if not exists
     */
    public static function initUserFile() {
        if (!file_exists(self::$userFile)) {
            $dir = dirname(self::$userFile);
            if (!file_exists($dir)) {
                mkdir($dir, 0700, true);
            }
            
            $header = "# EYPER CLL User Database\n";
            $header .= "# Format: username:password_hash\n";
            $header .= "# Created: " . date('Y-m-d H:i:s') . "\n";
            $header .= "# DO NOT EDIT MANUALLY\n\n";
            
            $fp = fopen(self::$userFile, 'w');
            if ($fp) {
                fwrite($fp, $header);
                fclose($fp);
                chmod(self::$userFile, 0600);
            }
        }
    }
}

// Initialize user file on load
Auth::initUserFile();
?>
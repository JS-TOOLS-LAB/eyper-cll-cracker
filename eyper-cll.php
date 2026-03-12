#!/usr/bin/env php
<?php
/**
 * EYPER CLL - Password Security Testing Tool (Protected)
 * Developed by JS Intergrated Labs
 * 
 * ACCESS: Only through successful login via start.php/login.php
 */

// Define root path ONLY if not already defined
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', __DIR__);
}

// Load core files with error checking
$required_files = [
    '/src/Config.php',
    '/src/Utils.php',
    '/src/Banner.php',
    '/src/Session.php',
    '/src/Auth.php',
    '/src/PasswordLoader.php',
    '/src/HashVerifier.php',
    '/src/ProgressTracker.php',
    '/src/Cracker.php'
];

foreach ($required_files as $file) {
    $full_path = ROOT_PATH . $file;
    if (!file_exists($full_path)) {
        die("\033[31m❌ Error: Required file not found: " . $full_path . "\033[0m\n");
    }
    require_once $full_path;
}

// Check authentication
if (!Session::isLoggedIn()) {
    echo "\033[31m\n❌ Access Denied! Please login first.\033[0m\n";
    echo "\033[33mRedirecting to login page...\033[0m\n";
    sleep(2);
    require_once ROOT_PATH . '/login.php';
    exit;
}

// Fix output buffering
if (php_sapi_name() === 'cli') {
    if (ob_get_level() > 0) {
        ob_implicit_flush(true);
        while (ob_get_level() > 0) {
            ob_end_flush();
        }
    } else {
        ob_implicit_flush(true);
    }
}

// Run cracker in interactive mode only
try {
    $cracker = new Cracker();
    $cracker->runInteractive();
} catch (Exception $e) {
    echo "\033[31mError: " . $e->getMessage() . "\033[0m\n";
    
    echo "\033[2m\nPress Enter to return to main menu...\033[0m";
    fgets(STDIN);
    require_once ROOT_PATH . '/start.php';
}
?>
<?php
/**
 * EYPER CLL - Configuration Settings
 * Developed by JS Intergrated Labs
 */

// Performance settings
define('BATCH_SIZE', 1000);
define('PROGRESS_INTERVAL', 0.3);
define('CACHE_SIZE', 10000);

// Paths
define('ROOT_PATH', dirname(__DIR__));
define('PASSWORDS_PATH', ROOT_PATH . '/passwords');
define('HASHES_PATH', ROOT_PATH . '/hashes');
define('DATA_PATH', ROOT_PATH . '/data');
define('LOGS_PATH', ROOT_PATH . '/logs');

// Color codes (CLI only)
if (php_sapi_name() === 'cli') {
    define('COLOR_RESET', "\033[0m");
    define('COLOR_RED', "\033[31m");
    define('COLOR_GREEN', "\033[32m");
    define('COLOR_YELLOW', "\033[33m");
    define('COLOR_BLUE', "\033[34m");
    define('COLOR_MAGENTA', "\033[35m");
    define('COLOR_CYAN', "\033[36m");
    define('COLOR_WHITE', "\033[37m");
    define('COLOR_BOLD', "\033[1m");
    define('COLOR_DIM', "\033[2m");
    define('COLOR_BLINK', "\033[5m");
    define('COLOR_REVERSE', "\033[7m");
    define('CLEAR_SCREEN', "\033[2J\033[;H");
} else {
    define('COLOR_RESET', '');
    define('COLOR_RED', '');
    define('COLOR_GREEN', '');
    define('COLOR_YELLOW', '');
    define('COLOR_BLUE', '');
    define('COLOR_MAGENTA', '');
    define('COLOR_CYAN', '');
    define('COLOR_WHITE', '');
    define('COLOR_BOLD', '');
    define('COLOR_DIM', '');
    define('COLOR_BLINK', '');
    define('COLOR_REVERSE', '');
    define('CLEAR_SCREEN', "\n\n\n\n\n\n\n\n\n\n");
}

// Version info
define('VERSION', '5.0');
define('DEV_TEAM', 'JS Intergrated Labs');
define('TOOL_NAME', 'EYPER CLL');
?>
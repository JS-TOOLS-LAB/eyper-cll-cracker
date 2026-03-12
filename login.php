#!/usr/bin/env php
<?php
/**
 * EYPER CLL - Login Handler
 * Developed by JS Intergrated Labs
 */

define('ROOT_PATH', __DIR__);
require_once ROOT_PATH . '/src/Config.php';
require_once ROOT_PATH . '/src/Utils.php';
require_once ROOT_PATH . '/src/Banner.php';
require_once ROOT_PATH . '/src/Session.php';
require_once ROOT_PATH . '/src/Auth.php';

// If already logged in, redirect to cracker
if (Session::isLoggedIn()) {
    redirectToCracker();
}

// Max login attempts
define('MAX_ATTEMPTS', 3);
$attempts = 0;

while ($attempts < MAX_ATTEMPTS) {
    clearScreen();
    showAuthBanner();
    
    echo COLOR_CYAN . "╔════════════════════════════════════════════════════════════╗\n";
    echo "║                      USER LOGIN                                 ║\n";
    echo "╚════════════════════════════════════════════════════════════╝\n\n" . COLOR_RESET;
    
    echo COLOR_YELLOW . "[" . COLOR_GREEN . "?" . COLOR_YELLOW . "] Username: " . COLOR_RESET;
    $username = trim(fgets(STDIN));
    
    echo COLOR_YELLOW . "[" . COLOR_GREEN . "?" . COLOR_YELLOW . "] Password: " . COLOR_RESET;
    system('stty -echo');
    $password = trim(fgets(STDIN));
    system('stty echo');
    echo "\n";
    
    if (Auth::login($username, $password)) {
        echo COLOR_GREEN . "\n✅ Login successful! Welcome, " . $username . "!\n" . COLOR_RESET;
        echo COLOR_CYAN . "⏱️  Redirecting to cracker..." . COLOR_RESET . "\n";
        sleep(2);
        redirectToCracker();
        break;
    } else {
        $attempts++;
        $remaining = MAX_ATTEMPTS - $attempts;
        echo COLOR_RED . "\n❌ Invalid username or password!\n" . COLOR_RESET;
        
        if ($remaining > 0) {
            echo COLOR_YELLOW . "⚠️  Attempts remaining: " . $remaining . "\n" . COLOR_RESET;
            echo COLOR_DIM . "Press Enter to try again..." . COLOR_RESET;
            fgets(STDIN);
        }
    }
}

// Too many failed attempts
echo COLOR_RED . "\n❌ Too many failed attempts! Returning to main menu.\n" . COLOR_RESET;
sleep(3);
require_once ROOT_PATH . '/start.php';
?>
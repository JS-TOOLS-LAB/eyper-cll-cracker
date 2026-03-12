#!/usr/bin/env php
<?php
/**
 * EYPER CLL - Welcome Page & Main Entry Point
 * Developed by JS Intergrated Labs
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
    '/src/Auth.php'
];

foreach ($required_files as $file) {
    $full_path = ROOT_PATH . $file;
    if (!file_exists($full_path)) {
        die("\033[31m❌ Error: Required file not found: " . $full_path . "\033[0m\n");
    }
    require_once $full_path;
}

// Clear screen and show welcome banner
clearScreen();
showWelcomeBanner();

// Check if already logged in
if (Session::isLoggedIn()) {
    echo "\033[32m\n✅ Already logged in as: \033[33m" . Session::getUsername() . "\033[0m\n";
    echo "\033[36m⏱️  Redirecting to cracker in 2 seconds...\033[0m\n";
    sleep(2);
    redirectToCracker();
}

// Main welcome menu
while (true) {
    echo "\033[36m\n╔════════════════════════════════════════════════════════════╗\n";
    echo "║                    WELCOME TO EYPER CLL                       ║\n";
    echo "╠════════════════════════════════════════════════════════════╣\n\033[0m";
    echo "║  \033[32m1.\033[0m 🔐 Login                                          ║\n";
    echo "║  \033[32m2.\033[0m 📝 Create New Account                             ║\n";
    echo "║  \033[32m3.\033[0m ℹ️  About EYPER CLL                               ║\n";
    echo "║  \033[32m4.\033[0m 🚪 Exit                                          ║\n";
    echo "\033[36m╚════════════════════════════════════════════════════════════╝\n\033[0m";
    
    echo "\033[33m[\033[32m?\033[33m] Select option [1-4]: \033[0m";
    $choice = trim(fgets(STDIN));
    
    switch ($choice) {
        case '1':
            // Redirect to login
            require_once ROOT_PATH . '/login.php';
            break;
        case '2':
            // Create new account
            createNewAccount();
            break;
        case '3':
            showAbout();
            break;
        case '4':
            echo "\033[36m\n╔════════════════════════════════════════════════════════════╗\n";
            echo "║     👋 Goodbye! Thanks for using EYPER CLL                    ║\n";
            echo "║     Developed by JS Intergrated Labs                          ║\n";
            echo "╚════════════════════════════════════════════════════════════╝\n\033[0m";
            exit(0);
        default:
            echo "\033[31m\n❌ Invalid option! Please try again.\033[0m\n";
            sleep(2);
    }
}

/**
 * Create new account
 */
function createNewAccount() {
    clearScreen();
    showAuthBanner();
    
    echo "\033[36m╔════════════════════════════════════════════════════════════╗\n";
    echo "║                 CREATE NEW ACCOUNT                           ║\n";
    echo "╚════════════════════════════════════════════════════════════╝\n\n\033[0m";
    
    // Get username
    echo "\033[33m[\033[32m+\033[33m] Enter username: \033[0m";
    $username = trim(fgets(STDIN));
    
    if (empty($username)) {
        echo "\033[31m\n❌ Username cannot be empty!\033[0m\n";
        sleep(2);
        return;
    }
    
    // Check if username exists
    if (Auth::userExists($username)) {
        echo "\033[31m\n❌ Username already exists! Please choose another.\033[0m\n";
        sleep(2);
        return;
    }
    
    // Get password
    echo "\033[33m[\033[32m+\033[33m] Enter password: \033[0m";
    system('stty -echo');
    $password = trim(fgets(STDIN));
    system('stty echo');
    echo "\n";
    
    if (empty($password)) {
        echo "\033[31m\n❌ Password cannot be empty!\033[0m\n";
        sleep(2);
        return;
    }
    
    // Confirm password
    echo "\033[33m[\033[32m+\033[33m] Confirm password: \033[0m";
    system('stty -echo');
    $confirm = trim(fgets(STDIN));
    system('stty echo');
    echo "\n";
    
    if ($password !== $confirm) {
        echo "\033[31m\n❌ Passwords do not match!\033[0m\n";
        sleep(2);
        return;
    }
    
    // Create account
    if (Auth::createUser($username, $password)) {
        echo "\033[32m\n✅ Account created successfully!\033[0m\n";
        echo "\033[36m⏱️  Redirecting to login...\033[0m\n";
        sleep(2);
        require_once ROOT_PATH . '/login.php';
    } else {
        echo "\033[31m\n❌ Failed to create account. Please try again.\033[0m\n";
        sleep(2);
    }
}

/**
 * Show about page
 */
function showAbout() {
    clearScreen();
    showAboutBanner();
    
    echo "\033[36m╔════════════════════════════════════════════════════════════╗\n";
    echo "║                    ABOUT EYPER CLL                            ║\n";
    echo "╠════════════════════════════════════════════════════════════╣\n\033[0m";
    echo "║  \033[33mVersion:\033[0m     5.0                                           ║\n";
    echo "║  \033[33mDeveloped:\033[0m   JS Intergrated Labs                         ║\n";
    echo "║  \033[33mPurpose:\033[0m    Educational Security Testing                 ║\n";
    echo "║  \033[33mLicense:\033[0m    MIT                                           ║\n";
    echo "║                                                              ║\n";
    echo "║  \033[32mFeatures:\033[0m                                               ║\n";
    echo "║  • Multi-hash support (MD5, SHA1, SHA256, bcrypt)           ║\n";
    echo "║  • Real-time progress tracking                              ║\n";
    echo "║  • Verbose mode with password attempts                      ║\n";
    echo "║  • Secure file-based authentication                         ║\n";
    echo "║                                                              ║\n";
    echo "║  \033[31m⚠️  LEGAL USE ONLY!\033[0m                                       ║\n";
    echo "║  Only test systems you own or have permission to test.      ║\n";
    echo "\033[36m╚════════════════════════════════════════════════════════════╝\n\033[0m";
    
    echo "\033[2m\nPress Enter to return to main menu...\033[0m";
    fgets(STDIN);
}
?>
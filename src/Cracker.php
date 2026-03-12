<?php
/**
 * EYPER CLL - Main Cracker Engine
 * Developed by JS Intergrated Labs
 */

class Cracker {
    private $verifier;
    private $loader;
    private $tracker;
    private $wordlist = 'passwords/pass.txt';
    
    /**
     * Run interactive cracker mode
     */
    public function runInteractive() {
        while (true) {
            $this->showCrackerMenu();
            
            echo "\n" . "\033[33m[\033[32m?\033[33m] Enter hash: \033[0m";
            $hash = trim(fgets(STDIN));
            
            if (empty($hash)) {
                echo "\033[31m❌ Hash cannot be empty!\033[0m\n";
                sleep(2);
                continue;
            }
            
            echo "\033[33m[\033[32m?\033[33m] Wordlist [$this->wordlist]: \033[0m";
            $input = trim(fgets(STDIN));
            $wordlist = !empty($input) ? $input : $this->wordlist;
            
            $this->crackHash($hash, $wordlist);
            
            echo "\n\033[2mPress Enter to continue...\033[0m";
            fgets(STDIN);
        }
    }
    
    /**
     * Show cracker menu
     */
    private function showCrackerMenu() {
        system('clear');
        echo "\033[36m\033[1m
    ╔════════════════════════════════════════════════════════════╗
    ║              EYPER CLL - PASSWORD CRACKER                 ║
    ║                    Interactive Mode                        ║
    ╚════════════════════════════════════════════════════════════╝
    \033[0m\n";
    }
    
    /**
     * Crack a single hash
     * @param string $hash Target hash
     * @param string $wordlist Path to wordlist
     */
    public function crackHash($hash, $wordlist) {
        echo "\n\033[2m[\033[32m✓\033[2m] Initializing cracker...\033[0m";
        
        try {
            // Initialize components
            $this->verifier = new HashVerifier($hash);
            $this->loader = new PasswordLoader($wordlist);
        } catch (Exception $e) {
            echo "\n\033[31m❌ Error: " . $e->getMessage() . "\033[0m\n";
            return;
        }
        
        $total = $this->loader->getTotal();
        
        if ($total == 0) {
            echo "\n\033[31m❌ No passwords in wordlist!\033[0m\n";
            return;
        }
        
        // Display target info
        echo "\033[32m ✓\033[0m\n";
        echo "\033[2m[\033[36mℹ\033[2m] Wordlist: \033[33m" . number_format($total) . "\033[2m passwords\033[0m\n";
        echo "\033[2m[\033[36mℹ\033[2m] Hash Type: \033[35m" . $this->verifier->getHashType() . "\033[0m\n";
        echo "\033[2m[\033[36mℹ\033[2m] Target: \033[33m" . substr($hash, 0, 20) . "..." . substr($hash, -10) . "\033[0m\n\n";
        
        // Initialize progress tracker
        $this->tracker = new ProgressTracker($total);
        
        $found = false;
        $found_password = '';
        $attempts = 0;
        
        // Main cracking loop
        while (!$found && ($batch = $this->loader->getBatch())) {
            foreach ($batch as $password) {
                $attempts++;
                
                if ($this->verifier->verify($password)) {
                    $found = true;
                    $found_password = $password;
                    break 2;
                }
                
                // Update progress
                if ($attempts % 50 == 0) {
                    $stats = $this->verifier->getStats();
                    $this->tracker->update($attempts, "Speed: " . number_format($stats['speed']) . "/s");
                }
            }
        }
        
        // Final update
        $this->tracker->update($attempts);
        echo "\n\n";
        
        // Show results
        $this->showResults($found, $found_password, $attempts);
    }
    
    /**
     * Show cracking results
     * @param bool $found Whether password was found
     * @param string $password Found password
     * @param int $attempts Number of attempts
     */
    private function showResults($found, $password, $attempts) {
        $stats = $this->verifier->getStats();
        
        if ($found) {
            echo "\033[32m\033[1m
    ╔════════════════════════════════════════════════════════════╗
    ║              ✅ PASSWORD FOUND! ✅                        ║
    ╚════════════════════════════════════════════════════════════╝
    \033[0m\n";
            
            echo "\033[36m[\033[32m🔑\033[36m] \033[1mExtracted Password: \033[33m\033[1m" . $password . "\033[0m\n\n";
            
            echo "\033[36m┌─[ STATISTICS ]─────────────────────────────────┐\033[0m\n";
            printf("\033[36m│\033[0m %-20s: \033[33m%-25s\033[0m \033[36m│\033[0m\n", "Hash Type", $this->verifier->getHashType());
            printf("\033[36m│\033[0m %-20s: \033[32m%-25s\033[0m \033[36m│\033[0m\n", "Attempts", number_format($attempts));
            printf("\033[36m│\033[0m %-20s: \033[35m%-25s\033[0m \033[36m│\033[0m\n", "Time", $stats['elapsed'] . ' seconds');
            printf("\033[36m│\033[0m %-20s: \033[32m%-25s\033[0m \033[36m│\033[0m\n", "Speed", number_format($stats['speed']) . '/s');
            printf("\033[36m│\033[0m %-20s: \033[36m%-25s\033[0m \033[36m│\033[0m\n", "Cache Hit Rate", $stats['cache_hit_rate'] . '%');
            echo "\033[36m└" . str_repeat('─', 50) . "┘\033[0m\n";
            
        } else {
            echo "\033[31m\033[1m
    ╔════════════════════════════════════════════════════════════╗
    ║              ❌ PASSWORD NOT FOUND! ❌                     ║
    ╚════════════════════════════════════════════════════════════╝
    \033[0m\n";
            
            echo "\033[36m┌─[ STATISTICS ]─────────────────────────────────┐\033[0m\n";
            printf("\033[36m│\033[0m %-20s: \033[33m%-25s\033[0m \033[36m│\033[0m\n", "Hash Type", $this->verifier->getHashType());
            printf("\033[36m│\033[0m %-20s: \033[31m%-25s\033[0m \033[36m│\033[0m\n", "Attempts", number_format($attempts));
            printf("\033[36m│\033[0m %-20s: \033[35m%-25s\033[0m \033[36m│\033[0m\n", "Time", $stats['elapsed'] . ' seconds');
            printf("\033[36m│\033[0m %-20s: \033[32m%-25s\033[0m \033[36m│\033[0m\n", "Speed", number_format($stats['speed']) . '/s');
            echo "\033[36m└" . str_repeat('─', 50) . "┘\033[0m\n";
        }
    }
    
    /**
     * Run command line mode (fast, no interactive menu)
     * @param string $hash Target hash
     * @param string $wordlist Path to wordlist
     */
    public function runCommandLine($hash, $wordlist) {
        $this->crackHash($hash, $wordlist);
    }
}
?>
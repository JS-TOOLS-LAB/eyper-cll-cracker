<?php
/**
 * EYPER CLL - Real-Time Progress Tracker with Animations
 * Developed by JS Intergrated Labs
 */

class ProgressTracker {
    private $start_time;
    private $total;
    private $last_update = 0;
    private $speeds = [];
    private $spinner = ['⠋', '⠙', '⠹', '⠸', '⠼', '⠴', '⠦', '⠧', '⠇', '⠏'];
    private $spinner_idx = 0;
    private $update_interval = 0.3; // seconds
    
    /**
     * Constructor
     * @param int $total Total items to process
     */
    public function __construct($total) {
        $this->start_time = microtime(true);
        $this->total = $total;
    }
    
    /**
     * Update progress display
     * @param int $current Current progress
     * @param string $extra_info Additional info to display
     */
    public function update($current, $extra_info = '') {
        $now = microtime(true);
        
        // Rate limit updates
        if ($now - $this->last_update < $this->update_interval && $current < $this->total) {
            return;
        }
        
        $elapsed = $now - $this->start_time;
        $speed = $elapsed > 0 ? round($current / $elapsed) : 0;
        
        // Smooth speed calculation (moving average)
        $this->speeds[] = $speed;
        if (count($this->speeds) > 5) {
            array_shift($this->speeds);
        }
        $avg_speed = count($this->speeds) > 0 ? round(array_sum($this->speeds) / count($this->speeds)) : 0;
        
        $percent = $this->total > 0 ? round(($current / $this->total) * 100, 1) : 0;
        $eta = $avg_speed > 0 ? round(($this->total - $current) / $avg_speed) : 0;
        
        $this->renderProgress($percent, $current, $avg_speed, $eta, $extra_info);
        $this->last_update = $now;
    }
    
    /**
     * Render progress bar
     * @param float $percent Percentage complete
     * @param int $current Current count
     * @param int $speed Speed in items/sec
     * @param int $eta Estimated time remaining
     * @param string $extra Extra info
     */
    private function renderProgress($percent, $current, $speed, $eta, $extra) {
        $bar_length = 40;
        $filled = floor(($percent / 100) * $bar_length);
        
        // Gradient progress bar
        $bar = '';
        for ($i = 0; $i < $bar_length; $i++) {
            if ($i < $filled) {
                if ($i < $bar_length * 0.3) {
                    $bar .= "\033[31m█"; // Red - start
                } elseif ($i < $bar_length * 0.6) {
                    $bar .= "\033[33m█"; // Yellow - middle
                } else {
                    $bar .= "\033[32m█"; // Green - end
                }
            } else {
                $bar .= "\033[2m░\033[0m"; // Dim - empty
            }
        }
        $bar .= "\033[0m";
        
        // Spinner animation
        $spinner = $this->spinner[$this->spinner_idx % count($this->spinner)];
        $this->spinner_idx++;
        
        // Format ETA
        $eta_str = $eta > 0 ? $this->formatETA($eta) : '∞';
        $speed_str = number_format($speed) . '/s';
        
        // Clear line and print
        echo "\r\033[K"; // Clear line
        printf("%s %s [%s] %s%5.1f%%%s %s/%s | %s%10s%s | ETA: %s %s",
            "\033[36m" . $spinner . "\033[0m",
            "\033[2m",
            $bar,
            "\033[33m",
            $percent,
            "\033[0m",
            number_format($current),
            number_format($this->total),
            "\033[32m",
            $speed_str,
            "\033[0m",
            $eta_str,
            $extra ? " \033[2m" . $extra . "\033[0m" : ""
        );
    }
    
    /**
     * Format ETA nicely
     * @param int $seconds Seconds
     * @return string Formatted time
     */
    private function formatETA($seconds) {
        if ($seconds < 60) return $seconds . 's';
        if ($seconds < 3600) return floor($seconds / 60) . 'm ' . ($seconds % 60) . 's';
        if ($seconds < 86400) return floor($seconds / 3600) . 'h ' . floor(($seconds % 3600) / 60) . 'm';
        return floor($seconds / 86400) . 'd ' . floor(($seconds % 86400) / 3600) . 'h';
    }
    
    /**
     * Get elapsed time
     * @return float Elapsed seconds
     */
    public function getElapsed() {
        return microtime(true) - $this->start_time;
    }
    
    /**
     * Complete progress and show final newline
     */
    public function complete() {
        echo "\n";
    }
}
?>
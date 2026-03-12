<?php
/**
 * EYPER CLL - Memory-Efficient Password Loader
 * Developed by JS Intergrated Labs
 */

class PasswordLoader {
    private $file;
    private $total = 0;
    private $eof = false;
    private $position = 0;
    
    /**
     * Constructor - open file and count lines
     * @param string $filename Path to wordlist file
     * @throws Exception If file not found or cannot be opened
     */
    public function __construct($filename) {
        if (!file_exists($filename)) {
            throw new Exception("File not found: $filename");
        }
        
        $this->file = fopen($filename, 'r');
        if (!$this->file) {
            throw new Exception("Cannot open file: $filename");
        }
        
        // Fast line count (memory efficient)
        $lines = 0;
        while (!feof($this->file)) {
            $lines += substr_count(fread($this->file, 8192), "\n");
        }
        $this->total = $lines;
        rewind($this->file);
    }
    
    /**
     * Get next batch of passwords
     * @param int $size Number of passwords to load
     * @return array Batch of passwords
     */
    public function getBatch($size = 1000) {
        if ($this->eof) return [];
        
        $batch = [];
        $count = 0;
        
        while ($count < $size && !feof($this->file)) {
            $line = fgets($this->file);
            if ($line === false) break;
            
            $line = rtrim($line, "\r\n");
            if ($line !== '') {
                $batch[] = $line;
                $count++;
                $this->position++;
            }
        }
        
        if (feof($this->file)) {
            $this->eof = true;
            fclose($this->file);
        }
        
        return $batch;
    }
    
    /**
     * Get total number of passwords
     * @return int Total count
     */
    public function getTotal() {
        return $this->total;
    }
    
    /**
     * Get current position
     * @return int Current line number
     */
    public function getPosition() {
        return $this->position;
    }
    
    /**
     * Check if end of file reached
     * @return bool True if EOF
     */
    public function isEof() {
        return $this->eof;
    }
    
    /**
     * Destructor - close file if open
     */
    public function __destruct() {
        if ($this->file && is_resource($this->file)) {
            fclose($this->file);
        }
    }
}
?>
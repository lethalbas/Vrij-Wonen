<?php

require_once "file_handler_util.php";

class gravatar_util {
    private $fhu;
    private $default = "";
    private $size = 30;

    function __construct() {
        $this->fhu = new file_handler_util;
        // Use local default avatar as fallback
        $this->default = $this->fhu->get_cdn_img_dir() . "/default_avatar.jpg";
    }

    // get image source url from gravatar with fallback
    function get_gravatar_url($email){
        $gravatar_url = "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?s=" . $this->size;
        
        // For local development, always use default avatar
        if ($this->is_local_environment()) {
            return $this->default;
        }
        
        return $gravatar_url;
    }
    
    // Check if we're in a local environment
    private function is_local_environment() {
        $host = $_SERVER['HTTP_HOST'] ?? '';
        return strpos($host, 'localhost') !== false || 
               strpos($host, '127.0.0.1') !== false || 
               strpos($host, '.local') !== false ||
               strpos($host, '.dev') !== false;
    }
    
    // Get avatar with fallback handling
    function get_avatar_with_fallback($email) {
        $gravatar_url = $this->get_gravatar_url($email);
        
        // If using Gravatar, add error handling
        if (!$this->is_local_environment()) {
            return $gravatar_url . '&d=' . urlencode($this->default);
        }
        
        return $gravatar_url;
    }
}
<?php

class csrf_util {
    
    public static function generateToken() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        return $_SESSION['csrf_token'];
    }
    
    public static function validateToken($token) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }
        
        return hash_equals($_SESSION['csrf_token'], $token);
    }
    
    public static function getTokenField() {
        $token = self::generateToken();
        return '<input type="hidden" name="' . config_util::get('csrf_token_name', '_token') . '" value="' . htmlspecialchars($token) . '">';
    }
    
    public static function validateRequest() {
        $tokenName = config_util::get('csrf_token_name', '_token');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST[$tokenName]) || !self::validateToken($_POST[$tokenName])) {
                throw new Exception('CSRF token validation failed');
            }
        }
        
        return true;
    }
}

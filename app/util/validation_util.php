<?php

class validation_util {
    
    public static function sanitizeString($input, $maxLength = 255) {
        if (!is_string($input)) {
            return '';
        }
        
        $sanitized = trim($input);
        $sanitized = strip_tags($sanitized);
        $sanitized = htmlspecialchars($sanitized, ENT_QUOTES, 'UTF-8');
        
        if (strlen($sanitized) > $maxLength) {
            $sanitized = substr($sanitized, 0, $maxLength);
        }
        
        return $sanitized;
    }
    
    public static function sanitizeInteger($input) {
        return filter_var($input, FILTER_VALIDATE_INT);
    }
    
    public static function sanitizeEmail($input) {
        return filter_var($input, FILTER_SANITIZE_EMAIL);
    }
    
    public static function validateEmail($input) {
        return filter_var($input, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    public static function sanitizeArray($input, $callback = null) {
        if (!is_array($input)) {
            return [];
        }
        
        $sanitized = [];
        foreach ($input as $key => $value) {
            if ($callback && is_callable($callback)) {
                $sanitized[$key] = $callback($value);
            } else {
                $sanitized[$key] = self::sanitizeString($value);
            }
        }
        
        return $sanitized;
    }
    
    public static function validateRequired($input) {
        return !empty($input) && $input !== null;
    }
    
    public static function validateLength($input, $min = 0, $max = 255) {
        $length = strlen($input);
        return $length >= $min && $length <= $max;
    }
    
    public static function validateNumeric($input) {
        return is_numeric($input);
    }
    
    public static function validatePositiveInteger($input) {
        $int = self::sanitizeInteger($input);
        return $int !== false && $int > 0;
    }
}

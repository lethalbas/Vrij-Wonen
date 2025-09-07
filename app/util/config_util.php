<?php

class config_util {
    private static $config = null;

    private static function init() {
        if (self::$config === null) {
            self::$config = [
                'db_host' => $_ENV['DB_HOST'] ?? 'localhost',
                'db_name' => $_ENV['DB_NAME'] ?? 'vrijwonen',
                'db_user' => $_ENV['DB_USER'] ?? 'root',
                'db_pass' => $_ENV['DB_PASS'] ?? '',
                'app_env' => $_ENV['APP_ENV'] ?? 'development',
                'app_debug' => filter_var($_ENV['APP_DEBUG'] ?? 'true', FILTER_VALIDATE_BOOLEAN),
                'csrf_token_name' => $_ENV['CSRF_TOKEN_NAME'] ?? '_token',
                'session_lifetime' => (int)($_ENV['SESSION_LIFETIME'] ?? 3600)
            ];
        }
    }

    public static function get($key, $default = null) {
        self::init();
        return self::$config[$key] ?? $default;
    }

    public static function set($key, $value) {
        self::init();
        self::$config[$key] = $value;
    }

    public static function getAll() {
        self::init();
        return self::$config;
    }
}

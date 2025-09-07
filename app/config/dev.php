<?php
// Development configuration
// Copy this to .env.local for local development

return [
    'db_host' => 'localhost',
    'db_name' => 'vrijwonen',
    'db_user' => 'root',
    'db_pass' => '',
    'app_env' => 'development',
    'app_debug' => true,
    'csrf_token_name' => '_token',
    'session_lifetime' => 3600
];

<?php
return array(
    'db' => array(
        'host' => 'localhost',
        'dbname' => 'easysklad',
        'user' => 'root',
        'pass' => '',
        'charset' => 'utf8',
    ),
    'smtp' => array(
        'host' => 'smtp.example.com',
        'port' => 587,
        'user' => 'user@example.com',
        'pass' => 'password',
        'from_email' => 'no-reply@example.com',
        'from_name' => 'EasyСклад',
        'encryption' => 'tls',
    ),
    'app' => array(
        'base_url' => 'http://localhost',
        'secret_key' => 'change_this_secret',
    ),
    'security' => array(
        'max_login_attempts' => 5,
        'lockout_minutes' => 15,
        'max_register_attempts_per_ip_per_hour' => 5,
        'max_twofa_attempts' => 5,
    ),
    'session' => array(
        'name' => 'easysklad_session',
        'cookie_lifetime' => 0,
        'cookie_secure' => false,
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax',
    ),
);

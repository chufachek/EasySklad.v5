<?php
function config_get($key, $default = null)
{
    global $config;
    $parts = explode('.', $key);
    $value = $config;
    foreach ($parts as $part) {
        if (!isset($value[$part])) {
            return $default;
        }
        $value = $value[$part];
    }
    return $value;
}

function e($value)
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function redirect($path)
{
    header('Location: ' . $path);
    exit;
}

function flash($key, $message = null)
{
    if ($message === null) {
        if (isset($_SESSION['flash'][$key])) {
            $value = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $value;
        }
        return null;
    }
    $_SESSION['flash'][$key] = $message;
}

function view($template, $data = array())
{
    extract($data);
    include __DIR__ . '/../views/' . $template . '.php';
}

function current_user()
{
    if (isset($_SESSION['user_id'])) {
        return $_SESSION['user_id'];
    }
    return null;
}

function current_company_id()
{
    return isset($_SESSION['company_id']) ? $_SESSION['company_id'] : null;
}

function require_auth()
{
    if (!current_user()) {
        redirect('/login');
    }
}

function require_company()
{
    if (!current_company_id()) {
        redirect('/companies');
    }
}

function log_error($message)
{
    $logDir = __DIR__ . '/../../storage/logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    $line = '[' . date('Y-m-d H:i:s') . '] ' . $message . "\n";
    file_put_contents($logDir . '/app.log', $line, FILE_APPEND);
}

function random_token($length = 16)
{
    if (function_exists('openssl_random_pseudo_bytes')) {
        return bin2hex(openssl_random_pseudo_bytes($length));
    }
    return bin2hex(md5(uniqid(mt_rand(), true), true));
}

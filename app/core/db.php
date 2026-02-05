<?php
function db()
{
    static $pdo;
    if ($pdo) {
        return $pdo;
    }
    global $config;
    $db = $config['db'];
    $dsn = 'mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'] . ';charset=' . $db['charset'];
    $options = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    );
    $pdo = new PDO($dsn, $db['user'], $db['pass'], $options);
    return $pdo;
}

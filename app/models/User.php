<?php
class User
{
    public static function findByEmailOrNickname($identifier)
    {
        $pdo = db();
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
        } else {
            $stmt = $pdo->prepare('SELECT * FROM users WHERE nickname = ?');
        }
        $stmt->execute(array($identifier));
        return $stmt->fetch();
    }

    public static function findById($id)
    {
        $pdo = db();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute(array($id));
        return $stmt->fetch();
    }

    public static function create($data)
    {
        $pdo = db();
        $stmt = $pdo->prepare('INSERT INTO users (first_name, last_name, middle_name, nickname, email, password_hash, email_verified, email_verify_token, twofa_enabled, created_at) VALUES (?, ?, ?, ?, ?, ?, 0, ?, 0, NOW())');
        $stmt->execute(array(
            $data['first_name'],
            $data['last_name'],
            $data['middle_name'],
            $data['nickname'],
            $data['email'],
            $data['password_hash'],
            $data['email_verify_token'],
        ));
        return $pdo->lastInsertId();
    }

    public static function markEmailVerified($token)
    {
        $pdo = db();
        $stmt = $pdo->prepare('UPDATE users SET email_verified = 1, email_verify_token = NULL WHERE email_verify_token = ?');
        return $stmt->execute(array($token));
    }

    public static function updatePassword($userId, $hash)
    {
        $pdo = db();
        $stmt = $pdo->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
        return $stmt->execute(array($hash, $userId));
    }

    public static function setTwofa($userId, $enabled)
    {
        $pdo = db();
        $stmt = $pdo->prepare('UPDATE users SET twofa_enabled = ? WHERE id = ?');
        return $stmt->execute(array($enabled ? 1 : 0, $userId));
    }
}

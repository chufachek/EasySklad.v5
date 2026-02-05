<?php
function rate_limit_check($identifier, $type, $maxAttempts, $lockoutMinutes)
{
    $pdo = db();
    $stmt = $pdo->prepare('SELECT * FROM login_attempts WHERE ip = ? AND identifier = ? AND attempt_type = ?');
    $stmt->execute(array($_SERVER['REMOTE_ADDR'], $identifier, $type));
    $row = $stmt->fetch();
    if ($row && $row['locked_until'] && strtotime($row['locked_until']) > time()) {
        return array(false, $row['locked_until']);
    }
    if ($row && $row['attempts_count'] >= $maxAttempts) {
        $lockedUntil = date('Y-m-d H:i:s', time() + ($lockoutMinutes * 60));
        $stmt = $pdo->prepare('UPDATE login_attempts SET locked_until = ?, updated_at = NOW() WHERE id = ?');
        $stmt->execute(array($lockedUntil, $row['id']));
        return array(false, $lockedUntil);
    }
    return array(true, null);
}

function rate_limit_increment($identifier, $type)
{
    $pdo = db();
    $stmt = $pdo->prepare('SELECT * FROM login_attempts WHERE ip = ? AND identifier = ? AND attempt_type = ?');
    $stmt->execute(array($_SERVER['REMOTE_ADDR'], $identifier, $type));
    $row = $stmt->fetch();
    if ($row) {
        $stmt = $pdo->prepare('UPDATE login_attempts SET attempts_count = attempts_count + 1, updated_at = NOW() WHERE id = ?');
        $stmt->execute(array($row['id']));
    } else {
        $stmt = $pdo->prepare('INSERT INTO login_attempts (ip, identifier, attempt_type, attempts_count, updated_at) VALUES (?, ?, ?, 1, NOW())');
        $stmt->execute(array($_SERVER['REMOTE_ADDR'], $identifier, $type));
    }
}

function rate_limit_reset($identifier, $type)
{
    $pdo = db();
    $stmt = $pdo->prepare('DELETE FROM login_attempts WHERE ip = ? AND identifier = ? AND attempt_type = ?');
    $stmt->execute(array($_SERVER['REMOTE_ADDR'], $identifier, $type));
}

function register_limit_reached($maxPerHour)
{
    $pdo = db();
    $stmt = $pdo->prepare('SELECT COUNT(*) as cnt FROM login_attempts WHERE ip = ? AND attempt_type = ? AND updated_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)');
    $stmt->execute(array($_SERVER['REMOTE_ADDR'], 'register'));
    $count = $stmt->fetch();
    return $count && $count['cnt'] >= $maxPerHour;
}

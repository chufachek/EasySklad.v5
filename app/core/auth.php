<?php
function login_user($userId)
{
    $_SESSION['user_id'] = $userId;
    unset($_SESSION['twofa_pending']);
}

function logout_user()
{
    session_unset();
    session_destroy();
}

function set_twofa_pending($userId)
{
    $_SESSION['twofa_pending'] = $userId;
}

function get_twofa_pending()
{
    return isset($_SESSION['twofa_pending']) ? $_SESSION['twofa_pending'] : null;
}

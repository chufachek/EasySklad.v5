<?php
class AuthController
{
    public function showLogin()
    {
        view('auth/login', array('error' => flash('error')));
    }

    public function login()
    {
        csrf_check();
        $identifier = trim($_POST['identifier']);
        $password = $_POST['password'];

        list($allowed, $lockedUntil) = rate_limit_check($identifier, 'login', config_get('security.max_login_attempts'), config_get('security.lockout_minutes'));
        if (!$allowed) {
            flash('error', 'Слишком много попыток. Попробуйте позже.');
            redirect('/login');
        }

        $user = User::findByEmailOrNickname($identifier);
        if (!$user || !password_verify($password, $user['password_hash'])) {
            rate_limit_increment($identifier, 'login');
            flash('error', 'Неверные данные.');
            redirect('/login');
        }

        if (!$user['email_verified']) {
            $_SESSION['verify_email_id'] = $user['id'];
            flash('error', 'Подтвердите почту перед входом.');
            redirect('/login?verify=1');
        }

        rate_limit_reset($identifier, 'login');

        if ($user['twofa_enabled']) {
            $code = rand(100000, 999999);
            $codeHash = password_hash($code, PASSWORD_DEFAULT);
            $pdo = db();
            $stmt = $pdo->prepare('INSERT INTO email_twofa_codes (user_id, code_hash, expires_at, created_at, used) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 10 MINUTE), NOW(), 0)');
            $stmt->execute(array($user['id'], $codeHash));
            send_mail($user['email'], $user['first_name'], 'Код входа EasyСклад', 'Ваш код: <strong>' . $code . '</strong>');
            set_twofa_pending($user['id']);
            redirect('/twofactor');
        }

        login_user($user['id']);
        redirect('/profile');
    }

    public function showRegister()
    {
        view('auth/register', array('error' => flash('error')));
    }

    public function register()
    {
        csrf_check();
        if (register_limit_reached(config_get('security.max_register_attempts_per_ip_per_hour'))) {
            flash('error', 'Лимит регистраций исчерпан. Попробуйте позже.');
            redirect('/register');
        }

        $data = array(
            'last_name' => trim($_POST['last_name']),
            'first_name' => trim($_POST['first_name']),
            'middle_name' => trim($_POST['middle_name']),
            'nickname' => trim($_POST['nickname']),
            'email' => trim($_POST['email']),
            'password' => $_POST['password'],
            'password_confirm' => $_POST['password_confirm'],
        );

        if (!$data['last_name'] || !$data['first_name'] || !$data['nickname'] || !$data['email']) {
            flash('error', 'Заполните обязательные поля.');
            redirect('/register');
        }
        if (!preg_match('/^[a-zA-Z0-9_]{3,}$/', $data['nickname'])) {
            flash('error', 'Никнейм некорректен.');
            redirect('/register');
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Некорректный email.');
            redirect('/register');
        }
        if ($data['password'] !== $data['password_confirm'] || strlen($data['password']) < 6) {
            flash('error', 'Пароль слишком короткий или не совпадает.');
            redirect('/register');
        }

        $pdo = db();
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? OR nickname = ?');
        $stmt->execute(array($data['email'], $data['nickname']));
        if ($stmt->fetch()) {
            flash('error', 'Email или никнейм уже используются.');
            redirect('/register');
        }

        $token = random_token(16);
        $userId = User::create(array(
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'middle_name' => $data['middle_name'],
            'nickname' => $data['nickname'],
            'email' => $data['email'],
            'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            'email_verify_token' => $token,
        ));

        $verifyUrl = config_get('app.base_url') . '/verify-email?token=' . $token;
        send_mail($data['email'], $data['first_name'], 'Подтвердите почту EasyСклад', 'Перейдите по ссылке: <a href="' . $verifyUrl . '">' . $verifyUrl . '</a>');

        rate_limit_increment($data['email'], 'register');
        flash('error', 'Регистрация завершена. Проверьте почту для подтверждения.');
        redirect('/login');
    }

    public function logout()
    {
        logout_user();
        redirect('/login');
    }

    public function verifyEmail()
    {
        $token = isset($_GET['token']) ? $_GET['token'] : '';
        if ($token) {
            User::markEmailVerified($token);
        }
        flash('error', 'Почта подтверждена. Войдите.');
        redirect('/login');
    }

    public function resendVerify()
    {
        csrf_check();
        if (!isset($_SESSION['verify_email_id'])) {
            redirect('/login');
        }
        $user = User::findById($_SESSION['verify_email_id']);
        if ($user && !$user['email_verified']) {
            $token = random_token(16);
            $pdo = db();
            $stmt = $pdo->prepare('UPDATE users SET email_verify_token = ? WHERE id = ?');
            $stmt->execute(array($token, $user['id']));
            $verifyUrl = config_get('app.base_url') . '/verify-email?token=' . $token;
            send_mail($user['email'], $user['first_name'], 'Подтвердите почту EasyСклад', 'Перейдите по ссылке: <a href=\"' . $verifyUrl . '\">' . $verifyUrl . '</a>');
        }
        flash('error', 'Письмо отправлено повторно.');
        redirect('/login');
    }

    public function showTwofactor()
    {
        view('auth/twofactor', array('error' => flash('error')));
    }

    public function twofactor()
    {
        csrf_check();
        $pendingUserId = get_twofa_pending();
        if (!$pendingUserId) {
            redirect('/login');
        }
        list($allowed, $lockedUntil) = rate_limit_check($pendingUserId, 'twofa', config_get('security.max_twofa_attempts'), config_get('security.lockout_minutes'));
        if (!$allowed) {
            flash('error', 'Слишком много попыток. Попробуйте позже.');
            redirect('/twofactor');
        }
        $code = trim($_POST['code']);
        $pdo = db();
        $stmt = $pdo->prepare('SELECT * FROM email_twofa_codes WHERE user_id = ? AND used = 0 AND expires_at >= NOW() ORDER BY created_at DESC LIMIT 1');
        $stmt->execute(array($pendingUserId));
        $row = $stmt->fetch();
        if (!$row || !password_verify($code, $row['code_hash'])) {
            rate_limit_increment($pendingUserId, 'twofa');
            flash('error', 'Неверный код.');
            redirect('/twofactor');
        }
        $stmt = $pdo->prepare('UPDATE email_twofa_codes SET used = 1 WHERE id = ?');
        $stmt->execute(array($row['id']));
        rate_limit_reset($pendingUserId, 'twofa');
        login_user($pendingUserId);
        redirect('/profile');
    }
}

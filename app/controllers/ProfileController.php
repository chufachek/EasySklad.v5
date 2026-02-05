<?php
class ProfileController
{
    public function show()
    {
        require_auth();
        $user = User::findById(current_user());
        $companies = Company::forUser(current_user());
        $company = current_company_id() ? Company::findById(current_company_id()) : null;
        view('profile/index', array(
            'user' => $user,
            'companies' => $companies,
            'company' => $company,
            'error' => flash('error'),
            'success' => flash('success'),
        ));
    }

    public function changePassword()
    {
        require_auth();
        csrf_check();
        $current = $_POST['current_password'];
        $new = $_POST['new_password'];
        $confirm = $_POST['confirm_password'];
        $user = User::findById(current_user());
        if (!password_verify($current, $user['password_hash'])) {
            flash('error', 'Неверный текущий пароль.');
            redirect('/profile');
        }
        if ($new !== $confirm || strlen($new) < 6) {
            flash('error', 'Пароль слишком короткий или не совпадает.');
            redirect('/profile');
        }
        User::updatePassword(current_user(), password_hash($new, PASSWORD_DEFAULT));
        flash('success', 'Пароль обновлен.');
        redirect('/profile');
    }

    public function enableTwofa()
    {
        require_auth();
        csrf_check();
        $user = User::findById(current_user());
        if (!$user['email_verified']) {
            flash('error', 'Сначала подтвердите почту.');
            redirect('/profile');
        }
        User::setTwofa(current_user(), true);
        flash('success', '2FA включена.');
        redirect('/profile');
    }

    public function disableTwofa()
    {
        require_auth();
        csrf_check();
        User::setTwofa(current_user(), false);
        flash('success', '2FA выключена.');
        redirect('/profile');
    }

    public function updateTaxSettings()
    {
        require_auth();
        csrf_check();
        if (!current_company_id()) {
            flash('error', 'Выберите компанию.');
            redirect('/profile');
        }
        $data = array(
            'tax_mode' => isset($_POST['tax_mode']) && $_POST['tax_mode'] === 'vat' ? 'vat' : 'no_vat',
            'vat_price_mode' => isset($_POST['vat_price_mode']) && $_POST['vat_price_mode'] === 'excluded' ? 'excluded' : 'included',
            'vat_default_rate' => isset($_POST['vat_default_rate']) ? $_POST['vat_default_rate'] : 'none',
        );
        Company::updateTaxSettings(current_company_id(), $data);
        flash('success', 'Настройки НДС обновлены.');
        redirect('/profile');
    }
}

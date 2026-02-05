<?php
class CompanyController
{
    public function list()
    {
        require_auth();
        $companies = Company::forUser(current_user());
        view('company/index', array('companies' => $companies, 'error' => flash('error')));
    }

    public function showCreate()
    {
        require_auth();
        view('company/create', array('error' => flash('error')));
    }

    public function create()
    {
        require_auth();
        csrf_check();
        $data = array(
            'name' => trim($_POST['name']),
            'inn' => trim($_POST['inn']),
            'business_type' => $_POST['business_type'],
            'currency' => 'RUB',
            'tax_mode' => isset($_POST['tax_mode']) && $_POST['tax_mode'] === 'vat' ? 'vat' : 'no_vat',
            'vat_default_rate' => $_POST['vat_default_rate'],
            'vat_price_mode' => $_POST['vat_price_mode'],
            'owner_user_id' => current_user(),
        );
        if (!$data['name']) {
            flash('error', 'Введите название компании.');
            redirect('/companies/create');
        }
        $companyId = Company::create($data);
        $_SESSION['company_id'] = $companyId;
        redirect('/warehouses');
    }

    public function select()
    {
        require_auth();
        csrf_check();
        $companyId = $_POST['company_id'];
        $_SESSION['company_id'] = $companyId;
        redirect('/profile');
    }
}

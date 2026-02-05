<?php
class WarehouseController
{
    public function list()
    {
        require_auth();
        require_company();
        $warehouses = Warehouse::all(current_company_id());
        view('warehouses/index', array('warehouses' => $warehouses, 'error' => flash('error')));
    }

    public function showCreate()
    {
        require_auth();
        require_company();
        view('warehouses/create', array('error' => flash('error')));
    }

    public function create()
    {
        require_auth();
        require_company();
        csrf_check();
        $name = trim($_POST['name']);
        if (!$name) {
            flash('error', 'Введите название склада.');
            redirect('/warehouses/create');
        }
        Warehouse::create(current_company_id(), array(
            'name' => $name,
            'address' => trim($_POST['address']),
        ));
        redirect('/warehouses');
    }
}

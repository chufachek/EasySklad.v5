<?php
class ProductController
{
    public function list()
    {
        require_auth();
        require_company();
        $filters = array(
            'category_id' => isset($_GET['category_id']) ? $_GET['category_id'] : null,
            'search' => isset($_GET['search']) ? $_GET['search'] : null,
        );
        $products = Product::all(current_company_id(), $filters);
        $categories = Product::categories(current_company_id());
        view('products/index', array('products' => $products, 'categories' => $categories));
    }

    public function showCreate()
    {
        require_auth();
        require_company();
        $categories = Product::categories(current_company_id());
        view('products/create', array('categories' => $categories, 'error' => flash('error')));
    }

    public function create()
    {
        require_auth();
        require_company();
        csrf_check();
        $name = trim($_POST['name']);
        if (!$name) {
            flash('error', 'Введите название товара.');
            redirect('/products/create');
        }
        $company = Company::findById(current_company_id());
        $vatRate = $_POST['vat_rate'] ? $_POST['vat_rate'] : $company['vat_default_rate'];
        Product::create(current_company_id(), array(
            'category_id' => $_POST['category_id'] ?: null,
            'name' => $name,
            'sku' => trim($_POST['sku']),
            'barcode' => trim($_POST['barcode']),
            'unit' => trim($_POST['unit']) ?: 'шт',
            'cost_price' => $_POST['cost_price'],
            'sell_price' => $_POST['sell_price'],
            'vat_rate' => $vatRate,
        ));
        redirect('/products');
    }

    public function categories()
    {
        require_auth();
        require_company();
        $categories = Product::categories(current_company_id());
        view('products/categories', array('categories' => $categories, 'error' => flash('error')));
    }

    public function createCategory()
    {
        require_auth();
        require_company();
        csrf_check();
        $name = trim($_POST['name']);
        if (!$name) {
            flash('error', 'Введите название категории.');
            redirect('/categories');
        }
        Product::createCategory(current_company_id(), $name);
        redirect('/categories');
    }
}

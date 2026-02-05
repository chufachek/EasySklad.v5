<?php
$config = require __DIR__ . '/../app/config/config.php';

session_name($config['session']['name']);
session_set_cookie_params(
    $config['session']['cookie_lifetime'],
    '/',
    '',
    $config['session']['cookie_secure'],
    $config['session']['cookie_httponly']
);
session_start();

require_once __DIR__ . '/../app/core/helpers.php';
require_once __DIR__ . '/../app/core/db.php';
require_once __DIR__ . '/../app/core/auth.php';
require_once __DIR__ . '/../app/core/csrf.php';
require_once __DIR__ . '/../app/core/mailer.php';
require_once __DIR__ . '/../app/core/rate_limit.php';

require_once __DIR__ . '/../app/models/User.php';
require_once __DIR__ . '/../app/models/Company.php';
require_once __DIR__ . '/../app/models/Warehouse.php';
require_once __DIR__ . '/../app/models/Product.php';
require_once __DIR__ . '/../app/models/Document.php';

require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/ProfileController.php';
require_once __DIR__ . '/../app/controllers/CompanyController.php';
require_once __DIR__ . '/../app/controllers/WarehouseController.php';
require_once __DIR__ . '/../app/controllers/ProductController.php';
require_once __DIR__ . '/../app/controllers/PosController.php';
require_once __DIR__ . '/../app/controllers/JournalController.php';

require_once __DIR__ . '/../vendor/bramus/Router.php';

use Bramus\Router\Router;

$router = new Router();

$router->get('/', function () {
    if (current_user()) {
        redirect('/profile');
    }
    redirect('/login');
});

$router->get('/login', array('AuthController', 'showLogin'));
$router->post('/login', array('AuthController', 'login'));
$router->get('/register', array('AuthController', 'showRegister'));
$router->post('/register', array('AuthController', 'register'));
$router->get('/logout', array('AuthController', 'logout'));
$router->get('/verify-email', array('AuthController', 'verifyEmail'));
$router->post('/verify-email/resend', array('AuthController', 'resendVerify'));
$router->get('/twofactor', array('AuthController', 'showTwofactor'));
$router->post('/twofactor', array('AuthController', 'twofactor'));

$router->get('/profile', array('ProfileController', 'show'));
$router->post('/profile/password', array('ProfileController', 'changePassword'));
$router->post('/profile/twofa/enable', array('ProfileController', 'enableTwofa'));
$router->post('/profile/twofa/disable', array('ProfileController', 'disableTwofa'));
$router->post('/profile/tax-settings', array('ProfileController', 'updateTaxSettings'));

$router->get('/companies', array('CompanyController', 'list'));
$router->get('/companies/create', array('CompanyController', 'showCreate'));
$router->post('/companies/create', array('CompanyController', 'create'));
$router->post('/companies/select', array('CompanyController', 'select'));

$router->get('/warehouses', array('WarehouseController', 'list'));
$router->get('/warehouses/create', array('WarehouseController', 'showCreate'));
$router->post('/warehouses/create', array('WarehouseController', 'create'));

$router->get('/products', array('ProductController', 'list'));
$router->get('/products/create', array('ProductController', 'showCreate'));
$router->post('/products/create', array('ProductController', 'create'));
$router->get('/categories', array('ProductController', 'categories'));
$router->post('/categories/create', array('ProductController', 'createCategory'));

$router->get('/pos', array('PosController', 'show'));
$router->post('/pos/receipt/create', array('PosController', 'createReceipt'));
$router->post('/pos/sale/create', array('PosController', 'createSale'));
$router->post('/pos/writeoff/create', array('PosController', 'createWriteoff'));

$router->get('/journal', array('JournalController', 'list'));
$router->get('/journal/{id}', array('JournalController', 'show'));

$router->set404(function () {
    http_response_code(404);
    view('layout/404');
});

$router->run();

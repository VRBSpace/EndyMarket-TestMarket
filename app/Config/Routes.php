<?php

use CodeIgniter\Router\RouteCollection;

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

$routes -> group('ApiQurier', function ($r) {
    $c = 'ApiQurier';

    $r -> add('econt_action', "$c::econt_action", ['as' => 'ApiQurier-econt_action']);
    $r -> add('speedy_action', "$c::speedy_action", ['as' => 'ApiQurier-speedy_action']);
});

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
//$routes -> get('/', 'Home::index');
$routes -> add('/login', 'Account::login');
$routes -> match(['get', 'post'], '/register', 'Account::register', ['as' => 'Account-register']);
$routes -> get('/logout', 'Account::logout');

$routes -> group('CronJobs', ['namespace' => 'App\Controllers\cronJobs'], function ($r) {
    $c = 'CronJobs';
    $r -> cli('cron_exp_xml', "$c::cron_exp_xml");
});

//$routes -> group('Test', function ($r) {
//    $c = 'Test';
//
//    $r -> add('', "$c::index", ['as' => 'Test-index']);
//    $r -> add('save/(:any)/(:any)', "$c::save/$1/$2", ['as' => 'Test-save']);
//    $r -> add('delete/(:any)', "$c::delete/$1/$1", ['as' => 'Test-delete']);
//});
//
//$routes-> add('Test2', "Test::test2");
//$routes-> add('Test3', "Test::test3");
$routes -> get('thumb', 'ResizeImage::thumb', ['as' => 'ResizeImage-thumb']);

$routes -> group('Account', function ($r) {
    $c = 'Account';

    $r -> get('', "$c::index", ['as' => 'Account-index']);
    $r -> add('register', "$c::register");
    $r -> add('change-password', "$c::changePassword", ['as' => 'Account-changePassword']);
    $r -> add('change-customerData', "$c::changeCustomerData", ['as' => 'Account-changeCustomerData']);
    $r -> add('change-deliveryData', "$c::changeDeliveryData", ['as' => 'Account-changeDeliveryData']);
    $r -> add('get_speeedyOficeData', "$c::get_speeedyOficeData", ['as' => 'Account-get_speeedyOficeData']);
});

$routes -> group('Dilar', function ($r) {
    $c = 'Dilar';

    $r -> get('index', "$c::index", ['as' => 'Dilar-index']);
    $r -> add('sendForm', "$c::sendForm", ['as' => 'Dilar-sendForm']);
});

// Clean URLs for static pages
$routes -> get('/about', 'Pages::index/about', ['as' => 'Pages-about']);
$routes -> get('/contact', 'Pages::index/contact', ['as' => 'Pages-contact']);
$routes -> get('/privacy-data', 'Pages::index/privacyData', ['as' => 'Pages-privacyData']);
$routes -> get('/terms', 'Pages::index/uslovia', ['as' => 'Pages-uslovia']);
$routes -> get('/return-policy', 'Pages::index/return', ['as' => 'Pages-return']);
$routes -> get('/warranty', 'Pages::index/waranty', ['as' => 'Pages-waranty']);

$routes -> group('/', function ($r) {
    $c = 'Shop';

    //$r -> get('', '\App\Modifications\Controllers\Home::index');
    if ($_ENV['app.theme2'] == 'bestphone') {
        // $c = "\App\Modifications\\{$_ENV['app.theme2']}\Controllers\Shop_mdf";
        //$r -> get('', "\App\Modifications\\{$_ENV['app.theme2']}\Controllers\Home_mdf::index");
    } else {
        $r -> get('', 'Home::index');
    }

    $r -> get('', 'Home::index');

    $r -> get('shop', "$c::index", ['as' => 'Shop-index']);
    $r -> post('shop', "$c::index", ['as' => 'Shop-index']);
    // SEO-friendly route за категория с един сегмент
    $r -> get('shop/(:any)', "$c::index/$1");
    $r -> add('shop?(:any)', "$c::index", ['as' => 'Shop-promo']);

    $r -> post('products/search', "$c::searchProducts", ['as' => 'Shop-searchProducts']);
    $r -> get('product/quick-view/(:num)', "$c::quickView/$1", ['as' => 'Shop-quickView']); //БЪРЗ ПРЕГЛЕД
    $r -> get('product/(:any)', "$c::get_single_product/$1", ['as' => 'Shop-singleProduct']);
    $r -> post('productSort/(:any)', "$c::productSort/$1", ['as' => 'Shop-productSort']);
    $r -> add('shop/xls_export_allProducts', "$c::xls_export_allProducts", ['as' => 'Shop-xls_export_allProducts']);

    // AJAX routes за йерархичните dropdown-и
    $r -> post('shop/getHierarchyModels', "$c::getHierarchyModels", ['as' => 'Shop-getHierarchyModels']);
    $r -> post('shop/getHierarchyYears', "$c::getHierarchyYears", ['as' => 'Shop-getHierarchyYears']);
    $r -> post('shop/getHierarchyCategories', "$c::getHierarchyCategories", ['as' => 'Shop-getHierarchyCategories']);
    $r -> post('shop/getHierarchyResults', "$c::getHierarchyResults", ['as' => 'Shop-getHierarchyResults']);
});

// филтриране на продукти по наличност, цена и атрибути
$routes -> group('Filters', function ($r) {
    $c = 'Filters';

    $r -> add('index/(:any)', "$c::index/$1", ['as' => 'Filters-index']);
});

//
//$routes -> group('users', ['namespace' => 'App\Controllers\users'], function ($routes) {
//    
//});

$routes -> group('Cart', function ($r) {
    $c = 'Cart';

    $r -> get('/', "$c::index", ['as' => 'Cart-index']);
    $r -> post('add-to-cart', "$c::addToCart", ['as' => 'Cart-addToCart']);
    $r -> post('increase-product-plus-one', "$c::increasePlusOne", ['as' => 'Cart-increasePlusOne']);
    $r -> post('decrease-product-minus-one', "$c::decreaseMinusOne", ['as' => 'Cart-decreaseMinusOne']);
    $r -> post('change_qty', "$c::change_qty", ['as' => 'Cart-changeQty']);
    $r -> post('remove-from-cart', "$c::removeFromCart", ['as' => 'Cart-removeFromCart']);
    $r -> add('emptyCart', "$c::emptyCart", ['as' => 'Cart-emptyCart']);
    $r -> add('get_klientObekt', "$c::get_klientObekt", ['as' => 'Cart-get_klientObekt']);
    $r -> add('set_klientObekt', "$c::set_klientObekt", ['as' => 'Cart-set_klientObekt']);
    $r -> add('xls_export', "$c::xls_export", ['as' => 'Cart-xls_export']);
});

//---  Ценови листи ---------------------
$routes -> group('CenovaLista', function ($r) {
    $c = 'CenovaLista';

    $r -> add('/', "$c::index", ['as' => 'CenovaLista-index']);
    $r -> add('(:num)', "$c::getCenovaListaById/$1", ['as' => 'CenovaLista-getCenovaListaById']);
    $r -> add('filter/(:any)', "$c::filter/$1", ['as' => 'CenovaLista-filter']);
});

//---  XML Export ---------------------
$routes -> group('Xml', function ($r) {
    $c = 'Xml';

    $r -> get('/', "$c::index", ['as' => 'Xml-index']);
    $r -> add('xmlExport', "$c::xmlExport", ['as' => 'Xml-xmlExport']);
    $r -> add('csvExport', "$c::csvExport", ['as' => 'Xml-csvExport']);
    $r -> add('autocomplete/(:any)', "$c::autocomplete/$1", ['as' => 'Xml-autocomplete']);
});

//---  Поръчки лист ---------------------
$routes -> group('Order', function ($r) {
    $c = 'Order';

    $r -> get('index', "$c::index", ['as' => 'Order-index']);
    $r -> add('pdf_export/(:num)', "$c::pdf_export/$1", ['as' => 'Order-pdf_export']);
});

//---  Поръчка детайли ---------------------
$routes -> group('OrderDetail', function ($r) {
    $c = 'OrderDetail';

    $r -> get('(:num)', "$c::getOrderDetailByOrderId/$1", ['as' => 'OrderDetail-getById']);
});

//---  Марки ---------------------
$routes -> group('Brand', function ($r) {
    $c = 'Brand';

    $r -> get('/', "$c::index", ['as' => 'Brand-index']);
});

//---  Финализиране на заявка ---------------------
$routes -> group('Checkout', function ($r) {
    $c = 'Checkout';

    $r -> add('', "$c::index", ['as' => 'Checkout-checkout']);
    $r -> add('checkout_klient', "$c::checkout_klient", ['as' => 'Checkout-klient']);
    $r -> add('checkout-fastOrder', "$c::checkout_fastOrder", ['as' => 'Checkout-fastOrder']);
});

$routes -> get('/gdpr1', 'Gdpr::index');
$routes -> get('/privacy-policy', 'Gdpr::policy');
$routes -> get('/cookie-policy', 'Pages::index/cookie', ['as' => 'Pages-cookiePolicy']);

$routes -> set404Override(function () {
    return view('errors/html/error_404');
});

/**
 * --------------------------------------------------------------------
 * === СИСТЕМА ========================================================
 * --------------------------------------------------------------------
 */
$routes -> group('systems', ['namespace' => 'App\Controllers\systems'], function ($r) {
    $c = 'PatchFile';
    $r -> add('PatchFile', "$c::index", ['as' => 'PatchFile']);
    $r -> add('LogViewer', 'LogViewer::index', ['as' => 'LogViewer']); //логове за грешки
    //$routes -> get('LogViewer', 'LogViewer::index', ['as' => 'LogViewer']); //логове за грешки
});
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}

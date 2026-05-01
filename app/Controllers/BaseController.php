<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use \App\Libraries\Breadcrumb;
use App\Models\MODEL__category;
use App\Models\MODEL__cenovaLista;
use App\Models\MODEL__product;
use App\Models\MODEL__setings;
use \App\Models\MODEL__global;
use App\Models\MODEL__account;
use App\Models\MODEL__cart;
use \App\Libraries\Pagination;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller {

    public $theme = '';

    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    public function __construct() {

        $this -> theme = $_ENV['app.theme'];

        helper('mobile', 'form', 'url', 'array', 'file');
        helper('setingGlob');
        helper('euroRate');

        $this -> breadcrumb     = new Breadcrumb();
        $this -> Pagination_lib = new Pagination();
        $this -> cartSession    = $this -> getSessionProperty();
        $this -> accountSession = $this -> getSessionAccount();
        $this -> customConfig   = new \Config\ValentiConfig\CustomConfig();

        $this -> productLevelMapping = [
            'Цена В'    => 'cenaB',
            'Цена А'    => 'cenaA',
            'Цена С'    => 'cenaKl',
            'Цена Кл'   => 'cenaKl',
            'cenaKKC'   => 'cenaKKC',
            'cenaPromo' => 'cenaPromo',
            'Специална' => 'cenaSpec',
        ];

        if (empty(session('is_password_changed')) && session('user_id') && !url_is('*Account*')) {
            // dd(1);
            header("Location:" . base_url('Account') . "");

            //return redirect() -> to('Account');
        }

        $this -> auto_update_session_product_inCart();

    }

//    protected function render_template($page = null, $data = []) {
//
//        echo view('layouts/VIEW__header', $data);
//        echo view('layouts/VIEW__header_menu', $data);
//        //echo view('layouts/VIEW__side_menubar', $data);
//        echo view($page, $data);
//        echo view('layouts/VIEW__footer', $data);
//    }

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        // Preload any models, libraries, etc, here.
        // E.g.: $this->session = \Config\Services::session();

    }

    protected function setDefaultTheme(string $theme) {
        // Check if the theme directory exists

        if (is_dir(APPPATH . 'Views/' . $theme)) {
            $this -> theme     = $theme;
            $_ENV['app.theme'] = $theme;
        } else {
            // Handle invalid theme
            throw new \RuntimeException('Invalid theme: ' . $theme);
        }

    }

    public static function get_logo() {
        $MODEL__setings = new MODEL__setings();

        return $MODEL__setings -> get__logo();

    }

    // глобални данни
    protected function global(): array {
        $settings = service('settings') -> get('App.general')[0] ?? [];

        $MODEL__category    = new MODEL__category();
        $MODEL__cenovaLista = new MODEL__cenovaLista();
        $MODEL__product     = new MODEL__product();
        $MODEL__global      = new MODEL__global();

        $cartSession = $this -> getSessionProperty();

        $categories    = $MODEL__category -> getCategories();
        $category_tree = $this -> buildCategoryTree(null, $categories);

        //dd($category_tree);
        $allCenovaLista = $this -> accountSession ? $MODEL__cenovaLista -> getAllCenovaLista() : [];

        $productPriceLevel          = $this -> getResolvedProductPriceLevel();
        $publicPriceLevel           = 'cenaKKC';
        $countProductsBySubCategory = $MODEL__product -> countProductsBySubCategory($publicPriceLevel);
        $totalShopProducts          = $MODEL__global -> count__all_product([
            'productPriceLevel' => $publicPriceLevel,
        ]);

        $settings_portal = $this -> get_portalSettings();

        $decoded      = [];
        $priceSortKey = session() -> has('user_id') ? $productPriceLevel : 'cenaKKC';
        foreach ($settings_portal as $key => $row) {
            $text = $row['text'] ?? null;

            if (empty($text)) {
                $decoded[$key] = null;
                continue;
            }

            $json = json_decode($text, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                if (isset($json['sort'])) {
                    $json['sort'] = '&sort=' . str_replace('price', $priceSortKey, $json['sort']);
                }
                $decoded[$key] = $json;
            } else {
                $decoded[$key] = $text;
            }
        }

        // $dds = isset($settings['dds']) ? ($settings['dds'] / 100) + 1 : 1.2;
        $settingsJson = json_decode($settings ?? '{}');
        $dds          = !empty($settingsJson -> dds) ? ($settingsJson -> dds / 100) + 1 : 1.2;

        return [
            'settingsJson'               => $settingsJson,
            'dds'                        => $dds,
            'ISMOBILE'                   => isMobile(),
            'setingGeneral'              => setingGlob('App.general'),
            'cartSession'                => $cartSession,
            'cartSessionProductsList'    => $cartSession['products'],
            'productPriceLevel'          => $productPriceLevel,
            'categories'                 => $category_tree,
            'allCenovaLista'             => $allCenovaLista,
            'countProductsBySubCategory' => $countProductsBySubCategory,
            'totalShopProducts'          => $totalShopProducts,
            'settings_portal'            => $decoded,
        ];

    }

    // генериране на масив с марки и продуктови модели 
    protected function buildModelByBrand($brands, $productModels) {
        $tree = array();

        // Organize product models by brand ID 
        $productModelsByBrand = [];
        foreach ($productModels as $p) {
            $productModelsByBrand[$p['brand_id']][] = $p;
        }

        foreach ($brands as $b) {
            // Check if the brand has product models
            if (isset($productModelsByBrand[$b['brand_id']])) {
                // Assign product models directly to the brand
                $b['children'] = $productModelsByBrand[$b['brand_id']];
                $tree[]        = $b;
            }
        }

        return $tree;

    }

    protected function buildCategoryTree($parent_id = null, $categories = null) {
        $tree = [];

        foreach ($categories as $category) {
            if ($category['parent_id'] == $parent_id) {
                $category['children'] = $this -> buildCategoryTree($category['category_id'], $categories);
                $tree[]               = $category;
            }
        }

        if (!empty($tree)) {
            usort($tree, function ($a, $b) {
                return $a['category_position'] <=> $b['category_position'];
            });
        }

        return $tree;

    }

    //определяне на id до подкатегориите от последната вложена подкатегория
    public function findPathToCategory($targetId, $categories, $path = []) {
        foreach ($categories as $cat) {
            $currentPath = [...$path, $cat];

            if ($cat['category_id'] == $targetId) {
                return $currentPath;
            }

            if (!empty($cat['children'])) {
                $result = $this -> findPathToCategory($targetId, $cat['children'], $currentPath);
                if ($result !== null) {
                    return $result;
                }
            }
        }
        return null;

    }

    public function findLeafIds(int $parentId, array $categories): array {
        $leafIds = [];

        $findStart = function ($nodes) use (&$findStart, $parentId, &$leafIds) {
            foreach ($nodes as $node) {
                if ($node['category_id'] == $parentId) {
                    // намерихме стартовата подкатегория -> обхождаме надолу
                    $traverse = function ($cat) use (&$traverse, &$leafIds) {
                        if (empty($cat['children'])) {
                            $leafIds[] = $cat['category_id'];
                        } else {
                            foreach ($cat['children'] as $child) {
                                $traverse($child);
                            }
                        }
                    };

                    $traverse($node);
                    return true;
                }

                if (!empty($node['children'])) {
                    if ($findStart($node['children'])) {
                        return true;
                    }
                }
            }
            return false;
        };

        $findStart($categories);
        return $leafIds;

    }

    /**
     * Връща списък с id-та на всички категории в даден клон (родител + деца в дълбочина).
     */
    protected function collectCategoryBranchIds($categoryId, array $byParent): array {
        if (empty($categoryId)) {
            return [];
        }
        $ids      = [$categoryId];
        $children = $byParent[$categoryId] ?? [];
        foreach ($children as $child) {
            $ids = array_merge($ids, $this -> collectCategoryBranchIds($child['category_id'], $byParent));
        }
        return $ids;

    }

    protected function getSessionAccount() {
        $accountSession = session() -> get('user_id') ?? [];

        return $accountSession;

    }

    protected function getSessionProperty() {
        $cartSession = session() -> get('cartSession') ?? [];

        if (!isset($cartSession['cart_all_products_info']['price'])) {
            $cartSession['cart_all_products_info']['price'] = 0;
        }
        if (!isset($cartSession['cart_all_products_info']['quantity'])) {
            $cartSession['cart_all_products_info']['quantity'] = 0;
        }
        if (!isset($cartSession['products'])) {
            $cartSession['products'] = [];
        }


        return $cartSession;

    }

    protected function setSessionProperty($cartSession) {
        session() -> set('cartSession', $cartSession);
        return;

    }

    protected function getProductPriceLevel() {
        $this -> accountSession = $this -> getSessionAccount();
        // определяме цената на продукта според клиента
        if (empty($this -> accountSession)) {
            $productPriceLevel = 'cenaKKC';
        } else {
            // вземи ИД от сесията на логнатия потребител/клиент
            $user_id = $this -> accountSession;

            // вземи ценовото ниво на дадения потребител
            $client            = new \App\Models\KlientModel();
            $productPriceLevel = $client -> getcenovaNivoByUserId($user_id);
        }

        return $productPriceLevel;

    }

    protected function getResolvedProductPriceLevel($rawLevel = null): string {
        $fallback = 'cenaKKC';
        $rawLevel = $rawLevel ?? $this -> getProductPriceLevel();

        if (!is_string($rawLevel)) {
            return $fallback;
        }

        $rawLevel = trim($rawLevel);
        if ($rawLevel === '') {
            return $fallback;
        }

        // Ниво като "Цена А" -> взимаме реалното поле (cenaA)
        if (array_key_exists($rawLevel, $this -> productLevelMapping)) {
            return $this -> productLevelMapping[$rawLevel];
        }

        // Ниво директно като поле (напр. cenaA)
        if (in_array($rawLevel, $this -> productLevelMapping, true)) {
            return $rawLevel;
        }

        return $fallback;

    }

    protected function getProductRightPrice($productId, $productPriceLevel) {
        helper('price');

        $productPriceModel = new \App\Models\ProductPriceLevelModel();
        $productPrices     = $productPriceModel -> getProductPrice($productId);

        if (empty($productPrices) || !is_array($productPrices)) {
            return null;
        }

        $user_id                = session() -> get('user_id');
        $klientIds_to_cenaIndiv = explode(',', $productPrices['klientIds_to_cenaIndiv'] ?? '');

        $priceLevel = $this -> getResolvedProductPriceLevel($productPriceLevel);

        // Проверка за индивидуални цени за логнати потребители
        if (!empty($user_id) && !empty($productPrices['cenaIndiv']) && in_array($user_id, $klientIds_to_cenaIndiv)) {
            $priceLevel = 'cenaIndiv';
        }
        $basePrice = $productPrices[$priceLevel] ?? null;
        if ($basePrice === null && $priceLevel !== 'cenaKKC') {
            $basePrice = $productPrices['cenaKKC'] ?? null;
        }

        $productModel = new \App\Models\MODEL__product();
        $productInfo  = $productModel -> getProduct($productId);
        $promoPercent = getPromoPercentFromGensoftJson($productInfo -> gensoft_json ?? null);
        $promoPrice   = calculatePromoPrice($basePrice, $promoPercent);

        return $promoPrice ?? $basePrice;

    }

    // проверка дали view файла е наличен в текущата директория на темата
    protected function is_fileExist($file = '') {

        $theme        = $_ENV['app.theme'];
        $defaultTheme = 'themes/test';

        if (!file_exists(APPPATH . "Views/{$_ENV['app.theme']}" . $file . '.php')) {
            $file = $defaultTheme . $file;
        } else {
            $file = $theme . $file;
        }

        return $file;

    }

    // извл на настройките на портала
    public static function get_portalSettings() {
        $MODEL__setings = new MODEL__setings();
        $nastroikiData  = $MODEL__setings -> get__nastroikiAll();
        $nastroikiData  = array_column($nastroikiData, null, 'key');

        return $nastroikiData ?? [];

    }

    // обновяване на сесията за поръчка с нови данни от запазената количка
    public function auto_update_session_product_inCart() {
        $cartSession = $this -> getSessionProperty();
        $user_id     = session('user_id');

        $MODEL__account = new MODEL__account();
        $MODEL__cart    = new MODEL__cart();

        $customerData = $MODEL__account -> get__klient($user_id);
        $res          = $MODEL__cart -> get__cart_productByUserId($user_id);

        // проверяваме дали има сесия с продукти и ако да обновяваме сесията с нови данни
        if (!empty($user_id)) {
            if (empty($res)) {
                $this -> setSessionProperty([]);
            } else {
                $productPriceLevel       = 'cenaKKC';
                $grandTotalPrice         = 0;
                $grandTotalQty           = 0;
                $_product_json           = json_decode($res[0]['product_json'] ?? '[]', true);
                $cartSession['products'] = [];

                foreach ($res as $v) {
                    $qty   = (int) ($_product_json[$v['product_id']]['qty'] ?? 0);
                    $price = $this -> getProductRightPrice($v['product_id'], $productPriceLevel);
                    $price = $price ?? 0;

                    $grandTotalPrice += $qty * $price;
                    $grandTotalQty   += $qty;

                    $cartSession['products'][$v['product_id']] = [
                        'product_id'   => $v['product_id'],
                        'quantity'     => $qty, // Начална количества
                        'product_info' => (object) $v, // Информация за продукта
                        'item_price'   => $price,
                        'total_price'  => $qty * $price
                    ];
                }
                $cartSession['cart_all_products_info']['price']    = $grandTotalPrice;
                $cartSession['cart_all_products_info']['quantity'] = $grandTotalQty;

                $this -> setSessionProperty($cartSession);
            }
        }

    }

    protected function sendEmail($sendTo, $setSubject, $setMessage, $isDilarForm = false) {
        $email = \Config\Services::email();

        $host = $_SERVER['HTTP_HOST'] ?? parse_url(base_url(), PHP_URL_HOST) ?? 'portal';

        $settings_portal  = $this -> get_portalSettings();
        $jsonDecodedEmail = !empty($settings_portal['order']['text']) ? json_decode($settings_portal['order']['text'], true) : [];

        $systemEmail     = $jsonDecodedEmail['email']['systemEmail'] ?? '';
        $additionalEmail = $jsonDecodedEmail['email']['additional'] ?? '';
        $dilarEmail      = $jsonDecodedEmail['email']['dilarEmail'] ?? '';
        $systemEmail     = trim((string) $systemEmail);
        $additionalEmail = trim((string) $additionalEmail);
        $dilarEmail      = trim((string) $dilarEmail);
        $sendTo          = trim((string) $sendTo);
        $fromName        = $this -> customConfig -> comany ?? $host;

//        $combinedEmails = $systemEmail;
//        if (!empty($additionalEmail)) {
//            $additionalEmail = array_map('trim', explode(',', $additionalEmail));
//            $combinedEmails  .= ', ' . implode(', ', $additionalEmail);
//        }
        // ако липсва основен системен email 
        if (empty($systemEmail) || ($isDilarForm && empty($dilarEmail))) {
            return false;
        }

        $primaryRecipient = $isDilarForm ? $dilarEmail : $sendTo;

        $email -> setTo($primaryRecipient)
                -> setFrom($systemEmail, $fromName)
                -> setReplyTo($systemEmail, $fromName)
                -> setSubject($setSubject)
                -> setMessage($setMessage)
                -> setNewLine("\r\n")
                -> setMailType('html');

        // Изпратете писмото
        if ($email -> send()) {
            if (!$isDilarForm) {
                $email -> clear(); // Reset email configuration for new email
                $email -> setTo($systemEmail)
                        -> setFrom($systemEmail, $fromName)
                        -> setReplyTo($sendTo ?: $systemEmail, $fromName)
                        -> setBCC($additionalEmail)
                        -> setSubject($setSubject)
                        -> setMessage($setMessage)
                        -> setNewLine("\r\n")
                        -> setMailType('html');

                if (!$email -> send()) {
                    log_message('error', 'Failed to send confirmation email to sender');
                }
            }
            return true;
        } else {
            log_message('error', 'Failed to send confirmation email to klient');
            return false;
        }

    }

    protected function addGlobalJS() {
        $theme  = 'js/theme/electro';
        $vendor = 'vendor';

        // JS Implementing Plugins 
        $plugins = [
            "$vendor/tableSearchRow/tableSearchRow",
            "$vendor/appear",
            "$vendor/jquery.countdown.min",
            "$vendor/hs-megamenu/src/hs.megamenu",
            "$vendor/svg-injector/dist/svg-injector.min",
            "$vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min",
            "$vendor/jquery-validation/dist/jquery.validate.min",
            "$vendor/fancybox/jquery.fancybox.min",
            "$vendor/ion-rangeslider/js/ion.rangeSlider.min",
            "$vendor/typed.js/lib/typed.min",
            "$vendor/slick-carousel/slick/slick",
            "$vendor/bootstrap-select/dist/js/bootstrap-select.min"
        ];

        // JS Electro 
        $components = [
            "$theme/components/hs.core",
            "$theme/components/hs.countdown",
            "$theme/components/hs.header",
            "$theme/components/hs.hamburgers",
            "$theme/components/hs.unfold",
            "$theme/components/hs.focus-state",
            "$theme/components/hs.malihu-scrollbar",
            "$theme/components/hs.validation",
            "$theme/components/hs.fancybox",
            "$theme/components/hs.onscroll-animation",
            "$theme/components/hs.quantity-counter",
            "$theme/components/hs.slick-carousel",
            "$theme/components/hs.show-animation",
            "$theme/components/hs.range-slider",
            "$theme/components/hs.show-animation",
            "$theme/components/hs.svg-injector",
            "$theme/components/hs.scroll-nav",
            "$theme/components/hs.go-to",
            "$theme/components/hs.selectpicker"
        ];

        $autoload = [
            "$theme/autoload"
        ];

        return array_merge($plugins, $components, $autoload);

    }

    protected function validateForm() {
        $validation = \Config\Services::validation();
        $validation -> setRules([
            'file' => [
                'rules'  => 'uploaded[file]',
                'errors' => [
                    'uploaded' => 'Липсва файл'
                ]
            ]
        ]);

        return $validation;

    }

    protected function get_views() {
        $theme = $this -> theme;

        return [
            //-- global layouts
            'top-bar'                       => "$theme/layouts/header/VIEW__top-bar",
            'miniCart'                      => "$theme/layouts/header/VIEW__miniCart",
            'search'                        => "$theme/layouts/header/VIEW__search",
            'icons'                         => "$theme/layouts/header/VIEW__icons",
            'header-content'                => "$theme/layouts/header/VIEW__header-content",
            'navigation-sidebar'            => "$theme/layouts/navigation/VIEW__navigation-sidebar",
            'navigation-headerLinks'        => "$theme/layouts/navigation/VIEW__navigation-headerLinks",
            'megaMenu'                      => "$theme/layouts/navigation/VIEW__navigation-megaMenu",
            'navigation-vMenu'              => "$theme/layouts/navigation/VIEW__navigation-vMenu",
            ///-- home --/////////////////////////////////////////////////////////////////////
            'slideshow'                     => "$theme/home/VIEW__home-slideShow",
            'sliderCategories'              => "$theme/home/VIEW__home-sliderLinks",
            'prodModelBlock1'               => "$theme/home/VIEW__home-block-prodModel",
            'banersBlock1'                  => "$theme/home/VIEW__home-block-baner1",
            'sectionTabed'                  => "$theme/home/VIEW__home-block-tabbedContent",
            ///-- shop --/////////////////////////////////////////////////////////////////////
            'shop-controlBar'               => "$theme/shop/VIEW__shop-controlBar",
            'shop-productsGrid'             => "$theme/shop/VIEW__shop-productsGrid",
            'shop-productsList'             => "$theme/shop/VIEW__shop-productsList",
            'shop-filter'                   => "$theme/shop/filter/VIEW__shop-filter",
            'shop-relationBar'              => "$theme/shop/VIEW__shop-relationBar",
            ///-- shop filter
            'filter-rangePrice'             => "$theme/shop/filter/VIEW__shop-filter-rangePrice",
            'filter-nalichnost'             => "$theme/shop/filter/VIEW__shop-filter-nalichnost",
            'filter-manufacture'            => "$theme/shop/filter/VIEW__shop-filter-manufacture",
            'filter-productAttr_1'          => "$theme/shop/filter/VIEW__shop-filter-productAttr_1",
            'filter-modelByCategory'        => "$theme/shop/filter/VIEW__shop-filter-modelByCategory",
            'filter-rootCategory'           => "$theme/shop/filter/VIEW__shop-filter-rootCategory",
            'shop-filter-characteristics'   => "{$_ENV['app.theme']}/shop/filter/VIEW__shop-filter-characteristics",
            // shared product card
            'productCard'                   => "$theme/home/partials/VIEW__product-card",
            ///-- cenovaLista --//////////////////////////////////////////////////////////////
            'cenovaLista-controlBar'        => "$theme/cenovaLista/VIEW__cenovaLista-controlBar",
            'cenovaLista-products'          => "$theme/cenovaLista/VIEW__cenovaLista-products",
            ////-- account --//////////////////////////////////////////////////////////////
            'acc-changePassword'            => "$theme/account/VIEW__acc-changePassword",
            'acc-customerData'              => "$theme/account/VIEW__acc-customerData",
            'acc-deliveryData'              => "$theme/account/VIEW__acc-deliveryData",
            ////-- cart --//////////////////////////////////////////////////////////////
            'cart-items'                    => "$theme/cart/VIEW__cart-items",
            'cart-itemsMobile'              => "$theme/cart/VIEW__cart-itemsMobile",
            'cart-aside'                    => "$theme/cart/VIEW__cart-aside",
            'cart-aside-user'               => "$theme/cart/VIEW__cart-aside-user",
            'cart-as-deliveryObekt'         => "$theme/cart/VIEW__cart-as-deliveryObekt",
            'cart-aside-user-deliveryObekt' => "$theme/cart/VIEW__cart-aside-user-deliveryObekt",
            ////-- orders --//////////////////////////////////////////////////////////////
            'orderDetail-items'             => "$theme/order/VIEW__orderDetail-items",
            'orderDetail-itemsMobile'       => "$theme/order/VIEW__orderDetail-itemsMobile",
            'ordersMobile'                  => "$theme/order/VIEW__ordersMobile",
        ];

    }

}

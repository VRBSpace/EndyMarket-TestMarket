<?php

namespace App\Controllers;

use App\Models\MODEL__order;
use App\Models\MODEL__product;
use App\Models\UserModel;
use App\Models\MODEL__account;
use App\Models\KlientModel;

class Cart extends BaseController {

    public function __construct() {
        parent::__construct();

        // ако потребителя няма профил не може да продължи.
        $this -> sessionAccountId = $this -> getSessionAccount();

        if (empty($this -> sessionAccountId) && !url_is('*Cart*')) {
            // header('Location: ' . route_to('Account-index'));
            //  exit;
        }

        $this -> userModel      = new UserModel();
        $this -> cartSession    = $this -> getSessionProperty();
        $this -> MODEL__account = new MODEL__account();

    }

    public function index() {

        $cartSession  = $this -> getSessionProperty();
        $user_id      = session('user_id');
        $user         = $this -> userModel -> find($user_id);
        $customerData = $this -> MODEL__account -> get__klient($user_id);

        $data = [
            'title'            => 'title',
            'addGlobalJS'      => $this -> addGlobalJS(),
            'addJS'            => $this -> addJS(),
            'addCSS'           => $this -> addCSS(),
            'user'             => $user,
            'customerData'     => $customerData,
            'cartSession'      => $cartSession,
            'customerData'     => $customerData,
            'sessionAccountId' => $this -> sessionAccountId,
            // views
            'views'            => $this -> get_views(),
        ];

        foreach ($this -> global() as $key => $val) {
            $data[$key] = $val;
        }

        return view("{$this -> theme}/cart/VIEW__cart", $data);

    }

    public function addToCart() {

        $productId = $this -> request -> getPost('product_id');
        $quantity  = (int) $this -> request -> getPost('quantity');

        $productPriceLevel = 'cenaKKC';
        $price             = $this -> getProductRightPrice($productId, $productPriceLevel);

        if (!isset($price)) {
            $response = [
                'success' => false,
                'message' => 'Продукта има проблем със цената, моля свържете се със нас по телефон!',
            ];
            return $this -> response -> setJSON($response);
        }

        $productModel = new MODEL__product();
        $productInfo  = $productModel -> getProduct($productId);

        $cartSession = $this -> getSessionProperty();

        if (array_key_exists($productId, $cartSession['products'])) {
            $cartSession['products'][$productId]['quantity']    += $quantity;
            $cartSession['products'][$productId]['total_price'] = ($cartSession['products'][$productId]['quantity'] * $price);
        } else {
            $cartSession['products'][$productId] = [
                'product_id'   => $productId,
                'quantity'     => $quantity, // Начална количества
                'product_info' => $productInfo, // Информация за продукта
                'item_price'   => $price,
            ];

            $cartSession['products'][$productId]['quantity']    = $quantity;
            $cartSession['products'][$productId]['total_price'] = $quantity * $price;
        }

        $cart_all_product_price    = $cartSession['cart_all_products_info']['price'] + ($price * $quantity);
        $cart_all_product_quantity = $cartSession['cart_all_products_info']['quantity'] + $quantity;

        $cartSession['cart_all_products_info'] = [
            'price'    => $cart_all_product_price,
            'quantity' => $cart_all_product_quantity
        ];

        $this -> setSessionProperty($cartSession);

        $response = [
            'success'     => true,
            'productName' => $productInfo -> product_name,
            'message'     => 'e добавен/a към количката.',
            'price'       => $cartSession['cart_all_products_info']['price'] . 'лв.',
            'quantity'    => count($cartSession['products']),
        ];

        if ($this -> customConfig -> isEnable_uncompleteOrder && session('user_id')) {
            $this -> set_uncompleteOrder($cartSession);
        }

        return $this -> response -> setJSON($response);

    }

    public function change_qty() {

        $productId         = $this -> request -> getPost('product_id');
        $qty               = $this -> request -> getPost('qty');
        $totalQty          = $this -> request -> getPost('totalQty');
        $totalPrice        = $this -> request -> getPost('totalPrice');
        $cartSession       = $this -> getSessionProperty();
        $productPriceLevel = 'cenaKKC';
        $price             = $this -> getProductRightPrice($productId, $productPriceLevel);

        if (array_key_exists($productId, $cartSession['products'])) {
            // $totalPrice    = $cartSession['cart_all_products_info']['price'] + ($price * ($qty - $qty_old));
            //$totalQuantity = $cartSession['cart_all_products_info']['quantity'] + ($qty - $qty_old);

            $cartSession['cart_all_products_info'] = [
                'price'    => $totalPrice,
                'quantity' => $totalQty
            ];

            $cartSession['products'][$productId]['quantity']    = $qty;
            $cartSession['products'][$productId]['total_price'] = $price * $qty;

            //dd($cartSession);
            $this -> setSessionProperty($cartSession);

            $response = [
                'success' => true,
                //'totalQuantity' => $totalQty,
                'message' => 'Данните са обновени',
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Няма продукт със ID: ' . $productId
            ];
        }

        if ($this -> customConfig -> isEnable_uncompleteOrder && session('user_id')) {
            $this -> set_uncompleteOrder($cartSession);
        }

        return $this -> response -> setJSON($response);

    }

    // изчиства цялата количка
    public function emptyCart() {
        $cartSession = $this -> getSessionProperty();

        $this -> setSessionProperty([]);

        if ($this -> customConfig -> isEnable_uncompleteOrder && session('user_id')) {
            $this -> set_uncompleteOrder($cartSession, $isEmpty = true);
        }

        return json_encode('');

    }

    public function removeFromCart() {

        $productId         = $this -> request -> getPost('product_id');
        $cartSession       = $this -> getSessionProperty();
        $productPriceLevel = 'cenaKKC';
        $price             = $this -> getProductRightPrice($productId, $productPriceLevel);

        if (array_key_exists($productId, $cartSession['products'])) {
            $quantityToRemove = $cartSession['products'][$productId]['quantity'];

            $totalPrice    = $cartSession['cart_all_products_info']['price'] - ($price * $quantityToRemove);
            $totalQuantity = $cartSession['cart_all_products_info']['quantity'] - $quantityToRemove;

            $cartSession['cart_all_products_info'] = [
                'price'    => max(0, $totalPrice),
                'quantity' => max(0, $totalQuantity)
            ];

            unset($cartSession['products'][$productId]);

            $this -> setSessionProperty($cartSession);

            $response = [
                'success'       => true,
                'totalQuantity' => $totalQuantity,
                'totalPrice'    => $totalPrice,
                'message'       => '',
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Няма продукт със ID: ' . $productId
            ];
        }

        if ($this -> customConfig -> isEnable_uncompleteOrder && session('user_id')) {
            $this -> set_uncompleteOrder($cartSession);
        }

        return $this -> response -> setJSON($response);

    }

    // избор на обект за доставка 
    public function get_klientObekt() {
        $loggedUserId = $this -> getSessionAccount();
        $customerData = $this -> MODEL__account -> get__klient($loggedUserId);

        $deliveryData = view("{$this -> theme}/popupForm/VIEWpop__deliveryData", ['customerData' => $customerData]);

        $data = [
            'view' => $deliveryData,
        ];

        return $this -> response -> setJSON($data);

    }

    // kogato е  избран обект за доставка от модал ф.
    public function set_klientObekt() {
        $obekt = $this -> request -> getVar('obekt');

        $loggedUserId = $this -> getSessionAccount();
        $customerData = $this -> MODEL__account -> get__klient($loggedUserId);

        $deliveryData = view("{$this -> theme}/cart/VIEW__cart-as-deliveryObekt", ['customerData' => $customerData, 'obekt' => $obekt]);

        $data = [
            'view' => $deliveryData,
        ];

        return $this -> response -> setJSON($data);

    }

    // създаване на незавършена поръчка с флаг N
    public function set_uncompleteOrder($cartSession = null, $isEmpty = false) {
        $orderDetailData = [];

        $MODEL__order  = new MODEL__order();
        $MODEL__klient = new KlientModel();

        $user_id = session('user_id');

        if ($isEmpty == true || empty($cartSession['products'])) {
            $MODEL__order -> upsertData($user_id, $isEmpty = true);
            return;
        }

        foreach ($cartSession['products'] as $product) {
            if (isset($product['product_id'])) {
                $orderDetailData[$product['product_id']] = [
                    //'order_id'    => $orderId,
                    //'product_id'  => $product['product_id'],
                    'qty'   => $product['quantity'],
                    'price' => $product['item_price']
                ];
            }
        }

        $klient        = $MODEL__klient -> getKlientIdbyUserId(session('user_id'))['klient_obekt_json'];
        $klientDecoded = json_decode($klient ?? '[]', true);
        $obekt         = '';

        foreach ($klientDecoded as $j) {
            if (!empty($j['default'])) {
                $obekt = $j['default'];
            }
        }

        $filteredObekt = $klientDecoded[$obekt] ?? [];
        $filteredObekt = array_filter($filteredObekt, fn($value) => $value !== '');

        $orderData = [
            'klient_id'     => $user_id,
            //'delivery_obekt' => $delivery_obekt ?? null,
            'delivery_json' => !empty($filteredObekt) ? json_encode($filteredObekt, JSON_UNESCAPED_UNICODE) : null,
            'total_price'   => $cartSession['cart_all_products_info']['price'],
            'product_json'  => json_encode($orderDetailData, JSON_UNESCAPED_UNICODE),
        ];

        $MODEL__order -> upsertData($orderData);
        $this -> setSessionProperty($cartSession);

        return;

    }

    public function xls_export() {
        $htmml = $this -> request -> getVar('html');

        $htmlString = preg_replace("/<img[^>]+\>/i", '', $htmml);
        $htmlString = preg_replace('#<tfoot([\s\S]*)</tfoot>#i', '', $htmlString);

        $date      = date("d-m-Y");
        $file_name = "zaqvka_$date.xlsx";
        $reader    = new \PhpOffice\PhpSpreadsheet\Reader\Html();
        $reader -> setReadDataOnly(true);

        $spreadsheet = $reader -> loadFromString($htmlString);
        $sheet       = $spreadsheet -> getActiveSheet();

        $highestRow = $sheet -> getHighestRow();

        $sheet -> removeColumn('A');
        $sheet -> removeColumn('A');
        $sheet -> removeColumn('A');

        $sheet -> getStyle("A1:D$highestRow") -> getBorders() -> getAllBorders() -> setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        //dd($sheet);
        //$sheet -> getPageSetup() -> setFitToWidth(1);
        //$sheet -> getPageSetup() -> setFitToHeight(0);
        //$sheet -> getStyle('B:C') -> getAlignment() -> setWrapText(false);
        $sheet -> getColumnDimension('A') -> setWidth(60);
        $sheet -> getColumnDimension('B') -> setWidth(15);
        $sheet -> getColumnDimension('C') -> setWidth(20);
        $sheet -> getColumnDimension('D') -> setWidth(20);

        $writer  = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        ob_start();
        $writer -> setPreCalculateFormulas(false);
        $writer -> save('php://output');
        $xlsData = ob_get_contents();
        ob_end_clean();

        $response = array(
            'fileName' => $file_name,
            'file'     => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," . base64_encode($xlsData)
        );

        return (json_encode($response));

    }

    // зареждане на css файлове в footer на html страноцата
    // ====================================================
    public function addCSS() {
        $page = 'css/pages/';

        return [
            "$page/global",
            "$page/cart",
        ];

    }

    // зареждане на js файлове в footer на html страноцата
    // ====================================================
    public function addJS() {
        $theme = 'js/theme/electro';

        $plugins = ["$theme/loadPlugins"];

        $global = ["$theme/global"];

        $default = ["$theme/pages/cart/cart", "$theme/pages/cart/cartAside"];

        $modals = [];

        return array_merge($plugins, $global, $default, $modals);

    }

    // плащане с карта
    public function checkout_card() {
        $cartSession = $this -> getSessionProperty();

        if (empty($cartSession['products'])) {
            return redirect() -> to('/');
        }

        $MODEL__order = new \App\Models\MODEL__order();
        $post         = $this -> request -> getPost();

        $user_id        = session('user_id');
        $klientData     = [];
        $cartGrandPrice = 0;

        if ($user_id) {
            $MODEL__klient = new \App\Models\KlientModel();
            $klient        = $MODEL__klient -> getKlientIdbyUserId($user_id);
            $klientData    = $klient ?? [];
        }

        $deliveryData = $post['delivery_json'] ?? [];

        if ($user_id) {
            $contactName = trim((string) ($klient['klient_mol'] ?? ''));

            if ($contactName === '') {
                $contactName = trim((string) ($klient['klient_name'] ?? ''));
            }

            if (empty($deliveryData['email']) && !empty($klientData['email'])) {
                $deliveryData['email'] = $klientData['email'];
            }
            if (empty($deliveryData['lice_zaKont']) && !empty($contactName)) {
                $deliveryData['lice_zaKont'] = $contactName;
            }
            if (empty($deliveryData['tel']) && !empty($klientData['klient_tel'])) {
                $deliveryData['tel'] = $klientData['klient_tel'];
            }
        }

        if (isset($post['tel']) && empty($deliveryData['tel'])) {
            $deliveryData['tel'] = $post['tel'];
        }

        $belezka = $post['belezka'] ?? '';

        $simpleProductJson = [];
        foreach ($cartSession['products'] as $product) {

            if (empty($product['product_id'])) {
                continue;
            }

            $qty   = $product['quantity'] ?? 0;
            $price = $product['item_price'] ?? 0;

            $simpleProductJson[$product['product_id']] = [
                'qty'   => $qty,
                'price' => $price
            ];

            $cartGrandPrice += $qty * $price;
        }

        $paymentMethodValue = $post['name'] ?? null;
        $dbPaymentMethod    = $paymentMethodValue;

        $orderData = [
            'klient_id'          => $user_id,
            'delivery_json'      => json_encode($deliveryData, JSON_UNESCAPED_UNICODE),
            'total_price'        => $cartGrandPrice,
            'payment_method'     => $dbPaymentMethod,
            'product_json'       => json_encode($simpleProductJson, JSON_UNESCAPED_UNICODE),
            'delivery_method'    => 'curier',
            'is_accepted_policy' => 1,
            'date_added'         => date('Y-m-d H:i:s'),
            'date_update'        => date('Y-m-d H:i:s'),
            'last_activity'      => date('Y-m-d H:i:s'),
            'belezka'            => $belezka,
        ];

        $orderId = $MODEL__order -> insertData($orderData);

        if (!$orderId) {
            die('Неуспешна поръчка');
        }

        $this -> setSessionProperty([]);

        if ($this -> customConfig -> isEnable_uncompleteOrder && session('user_id')) {
            $this -> set_uncompleteOrder($cartSession, $isEmpty = true);
        }

        if ($paymentMethodValue === 'D') {
            $amount = (int) round(((float) ($orderData['total_price'] ?? 0)) * 100);

            $dskData = [
                'userName'    => 'aleksandarjelin-api',
                'password'    => 'ieyyf-N1',
                'orderNumber' => $orderId . '_' . time(),
                'amount'      => $amount,
                'currency'    => 978, // евро,
                'returnUrl'   => base_url('payment/success?orderId=' . $orderId),
                'failUrl'     => base_url('payment/fail?orderId=' . $orderId),
                'description' => 'Order #' . $orderId,
                'ip'          => service('request') -> getIPAddress(),
            ];

            $ch = curl_init("https://uat.dskbank.bg/payment/rest/register.do");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($dskData));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

            $response = curl_exec($ch);
            curl_close($ch);

            $result = json_decode($response, true);

            if (!isset($result['formUrl'])) {
                die('Грешка при комуникация с банката');
            }

            return redirect() -> to($result['formUrl']);
        }
        return redirect() -> to('/') -> with('success', 'Поръчката е успешна!');

    }

    public function checkout() {
        $cartSession    = $this -> getSessionProperty();
        $cartGrandPrice = 0;

        if (empty($cartSession['products'])) {
            return redirect() -> to('/');
        }

        $MODEL__order = new \App\Models\MODEL__order();
        $post         = $this -> request -> getPost();

        $simpleProductJson = [];
        foreach ($cartSession['products'] as $product) {

            if (empty($product['product_id'])) {
                continue;
            }

            $qty   = $product['quantity'] ?? 0;
            $price = $product['item_price'] ?? 0;

            $simpleProductJson[$product['product_id']] = [
                'qty'   => $qty,
                'price' => $price
            ];

            $cartGrandPrice += $qty * $price;
        }


        $deliveryData        = $post['delivery_json'] ?? [];
        $deliveryData['tel'] = $post['tel'] ?? '';

        $orderData = [
            'klient_id'          => session('user_id') ?? null,
            'orderTip'           => 'K',
            'delivery_json'      => json_encode($deliveryData, JSON_UNESCAPED_UNICODE),
            'total_price'        => $cartGrandPrice,
            'payment_method'     => 'D',
            'product_json'       => json_encode($simpleProductJson, JSON_UNESCAPED_UNICODE),
            'delivery_method'    => $post['delivery_json']['izborKurier'] ?? 'curier',
            'is_accepted_policy' => 1,
            'date_added'         => date('Y-m-d H:i:s'),
            'date_update'        => date('Y-m-d H:i:s'),
            'last_activity'      => date('Y-m-d H:i:s'),
            'belezka'            => $post['belezka'] ?? null,
        ];

        $orderId = $MODEL__order -> insertData($orderData);

        if (!$orderId) {
            die('Неуспешна поръчка');
        }


        $this -> setSessionProperty([]);

        if ($this -> customConfig -> isEnable_uncompleteOrder && session('user_id')) {
            $this -> set_uncompleteOrder($cartSession, $isEmpty = true);
        }

        $amount = (int) round(((float) ($orderData['total_price'] ?? 0)) * 100);

        $dskData = [
            'userName'    => 'aleksandarjelin-api',
            'password'    => 'ieyyf-N1',
            'orderNumber' => $orderId . '_' . time(),
            'amount'      => $amount,
            'currency'    => 978, // евро
            'returnUrl'   => base_url('payment/success?orderId=' . $orderId),
            'failUrl'     => base_url('payment/fail?orderId=' . $orderId),
            'description' => 'Order #' . $orderId,
            'ip'          => service('request') -> getIPAddress(),
        ];

        $ch = curl_init("https://uat.dskbank.bg/payment/rest/register.do");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($dskData));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        if (!isset($result['formUrl'])) {
            die('Грешка при комуникация с банката');
        }

        return redirect() -> to($result['formUrl']);

    }

}

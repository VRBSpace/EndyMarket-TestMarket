<?php

namespace App\Controllers;

use App\Models\KlientModel;
use App\Models\MODEL__orderDetail;
use App\Models\MODEL__brand;
use App\Models\MODEL__order;
use App\Models\MODEL__account;

class OrderDetail extends BaseController {

    public function __construct() {
        parent::__construct();

        $this -> MODEL__account = new MODEL__account();
    }

    public function getOrderDetailByOrderId($orderId) {

        // ако потребителя няма профил не може да продължи.
        $sessionAccountId = $this -> getSessionAccount();
        if (empty($sessionAccountId)) {
            return redirect() -> to(route_to('Account-index'));
        }


        $orderDetailModel = new MODEL__orderDetail();
        $klientModel      = new KlientModel();
        $orderModel       = new MODEL__order();
        //$brandModel       = new MODEL__brand();

        $productPriceLevel = $this -> getResolvedProductPriceLevel();
        $products          = $orderDetailModel -> getOrderDetailByOrderId($orderId);

        if (empty($orderId) || empty($products)) {
            return redirect() -> back() -> with('message', "Поръчка № $orderId не съществува");
        }

        $folder_forDocumetns = glob(dirname(getcwd()) . '/' . $_ENV['app.documentsDir'] . 'documents/order/site/' . $orderId . '/*.*'); // прикачени файлове
        $klientData          = $klientModel -> getKlientIdbyUserId($sessionAccountId);
        $klient_obektJson    = $klientData['klient_obekt_json'] ?? null;

        $orderData = $orderModel -> getOrderByOrderId($orderId);
        $orderDoc  = $orderModel -> getOrderDocumentsByOrderId($orderId);

        $orderDocuments = null;

        if ($orderDoc) {
            $orderDocuments = explode(',', $orderDoc);
        }

        //$brands       = $brandModel -> getBrands();
        $user_id      = session('user_id');
        $customerData = $this -> MODEL__account -> get__klient($user_id);

        $data = [
            'title'             => 'Valpers',
            'addGlobalJS'       => $this -> addGlobalJS(),
            'addJS'             => $this -> addJS(),
            'addCSS'            => $this -> addCSS(),
            'klient_obektJson'  => $klient_obektJson,
            'products'          => $products,
            'status'            => $orderModel -> getOrderStatus(),
            'orderData'         => $orderData,
            'customerData'      => $customerData,
            'orderDocuments'    => $orderDocuments,
            'productPriceLevel' => $productPriceLevel,
            'get_documents'     => ['docs' => $folder_forDocumetns, 'orderId' => $orderId],
            //'brands'              => $brands,
            // views
            'views'             => $this -> get_views(),
        ];

        foreach ($this -> global() as $key => $val) {
            $data[$key] = $val;
        }

        return view("{$this -> theme}/order/VIEW__orderDetail", $data);
    }

    // зареждане на css файлове в footer на html страноцата
    // ====================================================
    public function addCSS() {
        $page = 'css/pages/';

        return [
            "$page/global",
            "$page/orderDetail",
        ];
    }

    public function addJS() {
        $theme  = 'js/theme/electro';
        $vendor = 'vendor';

        $plugins = ["$theme/loadPlugins", "$vendor/tableSearchRow/tableSearchRow"];

        $global = ["$theme/global"];

        $default = ["$theme/pages/order/order"];

        $modals = [];

        return array_merge($plugins, $global, $default, $modals);
    }
}

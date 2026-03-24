<?php

namespace App\Controllers;

use App\Models\KlientModel;
use App\Models\MODEL__order;
use App\Models\MODEL__orderDetail;

class Order extends BaseController {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        // ако потребителя няма профил не може да продължи.
        $sessionAccountId = $this -> getSessionAccount();

        if (empty($sessionAccountId)) {
            return redirect() -> to(route_to('Account-index'));
        }

        $klientModel = new KlientModel();
        $orderModel  = new MODEL__order();

        $klient = $klientModel -> getKlientIdbyUserId($sessionAccountId);
        if (empty($klient) || empty($klient['klient_id'])) {
            return redirect() -> to(route_to('Account-index')) -> with('message', 'Липсват клиентски данни за този профил.');
        }

        $orders = $orderModel -> getOrdersByKlientId($klient['klient_id']);

        foreach ($orders as &$order) {
            $folder_forDocumetns = glob(dirname(getcwd()) . '/' . $_ENV['app.documentsDir'] . 'documents/order/site/' . $order['order_id'] . '/*.*'); // прикачени файлове

            if (!empty($folder_forDocumetns)) {
                $order['documents'] = ['path' => $folder_forDocumetns, 'orderId' => $order['order_id']];
            }
            $timestamp           = strtotime($order['date_added']);
            $order['date_added'] = date("d-m-Y H:i:s", $timestamp);
        }

        $data = [
            'title'       => 'Valpers',
            'addGlobalJS' => $this -> addGlobalJS(),
            'addCSS'      => $this -> addCSS(),
            'addJS'       => $this -> addJS(),
            'orders'      => $orders,
            'klient'      => $klient,
            'status'      => $orderModel -> getOrderStatus(),
            // views
            'views'       => $this -> get_views(),
        ];

        foreach ($this -> global() as $key => $val) {
            $data[$key] = $val;
        }

        return view("{$this -> theme}/order/VIEW__orders", $data);
    }

    // pdf export
    public function pdf_export($orderId = null) {

        $sessionAccountId = $this -> getSessionAccount();
        $name             = $this -> request -> getVar('name');

        $this -> pdf      = new \App\Libraries\Pdf();
        $klientModel      = new KlientModel();
        $orderModel       = new MODEL__order();
        $orderDetailModel = new MODEL__orderDetail();

        $products = $orderDetailModel -> getOrderDetailByOrderId($orderId);
        $klient   = $klientModel -> getKlientIdbyUserId($sessionAccountId);

        if (empty($klient) || empty($klient['klient_id'])) {
            return redirect() -> to(route_to('Account-index')) -> with('message', 'Липсват клиентски данни за този профил.');
        }

        $klient_obektJson = $klient['klient_obekt_json'] ?? null;

        $orderData = $orderModel -> getOrderByOrderId($orderId);

        $data = [
            'klient_obektJson' => $klient_obektJson,
            'products'         => $products,
            'orderData'        => $orderData,
        ];

        $body = view("{$this -> theme}/order/VIEW__order-pdf", $data);

        $this -> pdf -> create($body, $name);
    }

    public function addCSS() {
        $page   = 'css/pages/';
        $vendor = 'vendor';

        return [
            "$page/global"
        ];
    }

    // зареждане на js файлове в footer на html страноцата
    // ====================================================
    public function addJS() {
        $theme  = 'js/theme/electro';
        $vendor = 'vendor';

        // JS Implementing Plugins 
        $plugins = ["$theme/loadPlugins", "$vendor/tableSearchRow/tableSearchRow"];

        $global = ["$theme/global"];

        $default = ["$theme/pages/order/order"];

        $modals = [];

        return array_merge($plugins, $global, $default, $modals);
    }
}

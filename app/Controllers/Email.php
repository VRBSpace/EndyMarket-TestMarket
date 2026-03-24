<?php

namespace App\Controllers;

use App\Models\KlientModel;
use App\Models\MODEL__orderDetail;
use App\Models\MODEL__brand;
use App\Models\MODEL__order;
use App\Models\MODEL__account;

class Email extends BaseController {

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
        $brandModel       = new MODEL__brand();

        $productPriceLevel = $this -> getResolvedProductPriceLevel();
        $products          = $orderDetailModel -> getOrderDetailByOrderId($orderId);

        $allOrdersTotalPrice = 0;
        foreach ($products as $product) {
            $allOrdersTotalPrice += $product['total_price'];
        }

        $klientData       = $klientModel -> getKlientIdbyUserId($sessionAccountId);
        $klient_obektJson = $klientData['klient_obekt_json'] ?? null;

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
            'title'               => 'Valpers',
            'addGlobalJS'         => $this -> addGlobalJS(),
            'addJS'               => $this -> addJS(),
            'addCSS'              => $this -> addCSS(),
            'klient_obektJson'    => $klient_obektJson,
            'products'            => $products,
            'orderData'           => $orderData,
            'allOrdersTotalPrice' => $allOrdersTotalPrice,
            'customerData'        => $customerData,
            'orderDocuments'      => $orderDocuments,
            'productPriceLevel'   => $productPriceLevel,
                //'brands'              => $brands,
        ];

        foreach ($this -> global() as $key => $val) {
            $data[$key] = $val;
        }

        return view("{$this -> theme}/email/VIEW__emailTemplate", $data);
    }
}

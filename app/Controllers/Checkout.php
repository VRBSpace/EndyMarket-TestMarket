<?php

namespace App\Controllers;

//use CodeIgniter\Controller;
use App\Models\MODEL__order;
use App\Models\MODEL__product;
use App\Models\KlientModel;
use App\Models\UserModel;

//use App\Models\UserModel;

class Checkout extends BaseController {

    // дилърска поръчка за логнати
    public function index() {
        $user_id = session('user_id');

        $sessionAccountId = $this -> getSessionAccount();
        $cartSession      = $this -> getSessionProperty();
        $jsonData         = array_merge(json_decode($this -> request -> getBody(), true), $cartSession['products'] ?? []);
        $orderDetailData  = [];
        $cartGrandPrice   = 0;

        $MODEL__order  = new MODEL__order();
        $MODEL__klient = new KlientModel();
        $MODEL__user   = new UserModel();

        $klient = $MODEL__klient -> getKlientIdbyUserId($sessionAccountId);
        $user   = $MODEL__user -> find($sessionAccountId);

        $klientObekt = json_decode($klient['klient_obekt_json'] ?? '[]', true);

        // Insert to OrderDetail
        foreach ($jsonData as $product) {
            if (empty($product['product_id'])) {
                continue;
            }

            $qty   = $product['quantity'] ?? 0;
            $price = $product['item_price'] ?? 0;

            $orderDetailData[$product['product_id']] = [
                //'order_id'    => $orderId,
                //'product_id'  => $product['product_id'],
                'qty'   => $qty,
                'price' => $price
            ];

            $cartGrandPrice += $qty * $price;
        }

        // Проверка дали има детайли за поръчката
        if (empty($orderDetailData)) {
            return json_encode([
                'success'    => false,
                'errMessage' => "Грешка: Липсват детайли(количество и цена) за продуктите в поръчката.",
            ]);
        }

        // данни за обекта до който ще се праща поръчката
        $deliveryObektKey = $jsonData['delivery_obekt'] ?? '';
        $filteredObekt    = $klientObekt[$deliveryObektKey] ?? [];
        $filteredObekt    = array_filter($filteredObekt, fn($value) => $value !== '');
        $customDelivery   = $jsonData['delivery_json'] ?? [];
        $customDelivery   = is_array($customDelivery) ? array_filter($customDelivery, fn($value) => $value !== '' && $value !== null) : [];

        $deliveryJson = !empty($filteredObekt) ? $filteredObekt : $customDelivery;

        // За логнат потребител винаги допълваме контактните полета от акаунта, ако липсват.
        $accountEmail = trim((string) ($user['email_login'] ?? $klient['email'] ?? ''));
        $contactName  = trim((string) ($klient['klient_mol'] ?? ''));
        if ($contactName === '') {
            $contactName = trim((string) (($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')));
        }
        if ($contactName === '') {
            $contactName = trim((string) ($klient['klient_name'] ?? $user['username'] ?? ''));
        }
        $contactPhone = trim((string) ($klient['klient_tel'] ?? ''));

        if (empty($deliveryJson['email']) && $accountEmail !== '') {
            $deliveryJson['email'] = $accountEmail;
        }
        if (empty($deliveryJson['lice_zaKont']) && $contactName !== '') {
            $deliveryJson['lice_zaKont'] = $contactName;
        }
        if (empty($deliveryJson['tel']) && $contactPhone !== '') {
            $deliveryJson['tel'] = $contactPhone;
        }

        $transportenRazhod = $jsonData['deliveryPrice'] ?? 0;

        $orderData = [
            'orderTip'           => NULL,
            'klient_id'          => $user_id ?? null,
            //'delivery_obekt'  => $jsonData['delivery_obekt'] ?? null,
            'delivery_json'      => !empty($deliveryJson) ? json_encode($deliveryJson, JSON_UNESCAPED_UNICODE) : null,
            'delivery_method'    => $jsonData['delivery_method'] ?? null,
            'total_price'        => $cartGrandPrice,
            'transportenRazhod'  => $transportenRazhod,
            'payment_method'     => $jsonData['payment_method'] ?? null,
            'belezka'            => $jsonData['belezka'] ?? null,
            'is_accepted_policy' => 1,
            'product_json'       => json_encode($orderDetailData, JSON_UNESCAPED_UNICODE),
        ];

        $orderId = $MODEL__order -> insertData($orderData);

        // Проверка дали поръчката е записана успешно
        if (!$orderId) {
            return json_encode([
                'success'    => false,
                'errMessage' => "Грешка: Неуспешно създаване на поръчка.",
            ]);
        }

        // премахва запазената количка от ДБ
        if ($this -> customConfig -> isEnable_uncompleteOrder && session('user_id')) {
            $MODEL__order -> upsertData($user_id, $isEmpty = true);
        }

        // clear session 
        $this -> setSessionProperty([]);

        try {
            // Email configuration
            $setSubjectToClient = "Поръчка № $orderId";
            $setMessageToClient = view("{$this -> theme}/email/VIEW__emailTemplate", [
                'firma'            => $klient['klient_name'],
                'orderData'        => $orderData,
                'orderId'          => $orderId,
                'klient_obektJson' => $klient['klient_obekt_json'],
                'products'         => $jsonData,
                'belezka'          => $jsonData['belezka'],
                    ]
            );

            // sent to client
            if (!empty($accountEmail)) {
                $this -> sendEmail($accountEmail, $setSubjectToClient, $setMessageToClient);
            }
        } catch (\Throwable $e) {
            log_message('error', 'Checkout email error (orderId: {orderId}): {message}', [
                'orderId' => $orderId,
                'message' => $e -> getMessage(),
            ]);
        }

        $response = [
            'success' => true,
            'message' => "Вашата поръчка е приета и ще бъде изпълнена в най скоро време!",
                //'errMessage' => empty($sendEmailToClient) ? "<br><br>Имейл до {$klient['klient_name']} не беше изпратен. Възможни причини са липсва email или не е валиден email адреса." : ''
        ];

        return $this -> response -> setJSON($response);

    }

    // клиентска поръчка
    public function checkout_klient() {
        $sessionAccountId    = $this -> getSessionAccount();
        $cartSession         = $this -> getSessionProperty();
        $cartSessionProducts = $cartSession['products'] ?? [];
        $cartGrandPrice      = 0;

        if (empty($cartSessionProducts) || empty($cartSession)) {
            return json_encode([
                'success'    => false,
                'errMessage' => "Грешка: сесията с поръчани продукти е празна.",
            ]);
        }

        $post = $this -> request -> getPost();

        $jsonData = array_merge($post, $cartSessionProducts);
        $email    = $jsonData['delivery_json']['email'];

        $jsonData['delivery_json'] = json_encode(array_filter((array) $jsonData['delivery_json']) + ['tel' => $jsonData['tel']], JSON_UNESCAPED_UNICODE);

        $orderDetailData = [];
        foreach ($cartSessionProducts as $product) {
            if (empty($product['product_id'])) {
                continue;
            }

            $qty   = $product['quantity'] ?? 0;
            $price = $product['item_price'] ?? 0;

            $orderDetailData[$product['product_id']] = [
                'qty'   => $qty,
                'price' => $price
            ];

            $cartGrandPrice += $qty * $price;
        }

        // Проверка дали има детайли за поръчката
        if (empty($orderDetailData)) {
            return json_encode([
                'success'    => false,
                'errMessage' => "Грешка: Липсват детайли(количество и цена) за продуктите в поръчката.",
            ]);
        }

        $transportenRazhod = $jsonData['deliveryPrice'] ?? 0;

        $orderData = [
            //'sp_status_id'    => 'Не приключена',
            'orderTip'           => 'K',
            'delivery_json'      => $jsonData['delivery_json'] ?? null,
            'delivery_method'    => 'curier',
            'total_price'        => $cartGrandPrice,
            'transportenRazhod'  => $transportenRazhod,
            'payment_method'     => $jsonData['payment_method'],
            'belezka'            => $jsonData['belezka'] ?? null,
            'is_accepted_policy' => 1,
            'product_json'       => json_encode($orderDetailData),
        ];

        // Създаване на нова поръчка
        $MODEL__order = new MODEL__order();
        $orderId      = $MODEL__order -> insertData($orderData);

        // Проверка дали поръчката е записана успешно
        if (!$orderId) {
            return json_encode([
                'success'    => false,
                'errMessage' => "Грешка: Неуспешно създаване на поръчка.",
            ]);
        }

        // clear session 
        $this -> setSessionProperty([]);

        $response = [
            'success' => true,
            'message' => "Вашата поръчка е приета и ще бъде изпълнена в най скоро време!",
        ];

        // sent to client
        if (!empty($email)) {
            // Email configuration
            $setSubjectToClient = "Поръчка № $orderId";
            $setMessageToClient = view("{$this -> theme}/email/VIEW__emailTemplate", [
                'orderData' => $orderData,
                'orderId'   => $orderId,
                'products'  => $jsonData,
                'belezka'   => $orderData['belezka'],
                    ]
            );

            $sendEmailToClient = $this -> sendEmail($email, $setSubjectToClient, $setMessageToClient);

            if (empty($sendEmailToClient)) {
                //$response['errMessage'] = "<br><br>Имейл до $email не беше изпратен. Възможни причини са липсващ или невалиден имейл адрес.";
            }
        }

        return $this -> response -> setJSON($response);

    }

    // бърза поръчка
    public function checkout_fastOrder() {
        $jsonData  = $this -> request -> getPost();
        $qty       = (int) ($jsonData['qty'] ?? 1);
        $productId = $jsonData['product_id'];

        if (!$productId) {
            return $this -> response -> setJSON([
                        'success'    => false,
                        'errMessage' => 'Невалидно ID на продукт!'
            ]);
        }

        $MODEL__product = new MODEL__product();
        $productInfo    = $MODEL__product -> getProduct($productId);

        if (!$productInfo) {
            return $this -> response -> setJSON([
                        'success'    => false,
                        'errMessage' => 'Продуктът не е намерен.'
            ]);
        }

        $orderDetailData = [
            $productInfo -> product_id => [
                'qty'   => $qty,
                'price' => $productInfo -> cenaKKC
            ]
        ];

        $orderData = [
            'orderTip'        => 'F',
            'delivery_json'   => json_encode(array_filter($jsonData), JSON_UNESCAPED_UNICODE),
            'total_price'     => $productInfo -> cenaKKC * $qty,
            'belezka'         => empty($jsonData['belezka']) ? NULL : $jsonData['belezka'],
            'belezka_private' => empty($jsonData['adres']) ? NULL : $jsonData['adres'],
            'product_json'    => json_encode($orderDetailData),
        ];

        $MODEL__order = new MODEL__order();
        $orderId      = $MODEL__order -> insertData($orderData);

        if (!$orderId) {
            return $this -> response -> setJSON([
                        'success'    => false,
                        'errMessage' => 'Грешка при създаване на поръчка.'
            ]);
        }

        $response = [
            'success' => true,
            'message' => "Вашата поръчка е приета и ще бъде изпълнена в най скоро време!",
        ];

        return $this -> response -> setJSON($response);

    }

}

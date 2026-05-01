<?php

namespace App\Controllers;

use App\Models\MODEL__order;

class Payment extends BaseController
{
    protected $orderModel;

    public function __construct()
    {
        parent::__construct();
        $this->orderModel = new MODEL__order();
    }

    public function success()
    {
        $orderId = $this->request->getGet('orderId');
        $user_id = session('user_id');

        if ($orderId) {
            $db = \Config\Database::connect();
            $db->table('_order')
               ->where('order_id', $orderId)
               ->update([
                   'date_update' => date('Y-m-d H:i:s')
               ]);

            $order = $this->orderModel->getOrderByOrderId($orderId);

            if ($order) {
                $products = json_decode($order->product_json, true);
                $delivery = json_decode($order->delivery_json, true);
                $email = $delivery['email'] ?? null;

                if (!empty($email)) {
                    $currency = '€';
                    $rows = '';
                    $total = 0;
                    $i = 1;

                    $isSimpleFormat = isset($products[array_key_first($products)]['qty']);
                    
                    foreach ($products as $productId => $productData) {
                        if ($isSimpleFormat) {
                            $qty = $productData['qty'];
                            $price = (float) $productData['price'];
                            $name = "Продукт #{$productId}";
                            $model = '';
                            $code = '';
                            $image = '';
                        } else {
                            $qty = $productData['quantity'] ?? 1;
                            $price = (float) ($productData['item_price'] ?? 0);
                            $name = $productData['product_info']['product_name'] ?? "Продукт #{$productId}";
                            $model = $productData['product_info']['model'] ?? '';
                            $code = $productData['product_info']['kod'] ?? '';
                            $image = $productData['product_info']['image'] ?? '';
                        }

                        $sum = $qty * $price;
                        $total += $sum;

                        $imgUrl = !empty($image)
                            ? $_ENV['app.imageDir'] . $image
                            : $_ENV['app.noImage'];

                        $productUrl = base_url() . '/product/' . $productId;

                        $rows .= "
                        <tr>
                            <td style='text-align:center'>{$i}</td>
                            <td><img src='{$imgUrl}' style='max-width:60px'></td>
                            <td><a href='{$productUrl}'>{$name}</a></td>
                            <td style='text-align:center'>{$model}</td>
                            <td style='text-align:center'>{$code}</td>
                            <td style='text-align:center'>{$qty}</td>
                            <td style='text-align:center'>" . number_format($price, 2) . " {$currency}</td>
                            <td style='text-align:right'>" . number_format($sum, 2) . " {$currency}</td>
                        </tr>
                        ";
                        $i++;
                    }

                    $deliveryHtml = "
                    <b>Лице за контакт:</b> " . ($delivery['lice_zaKont'] ?? '') . "<br>
                    <b>Телефон:</b> " . ($delivery['tel'] ?? '') . "<br>
                    <b>Град:</b> " . ($delivery['grad'] ?? '') . "<br>
                    <b>Офис/Адрес:</b> " . ($delivery['ofis'] ?? ($delivery['street'] ?? '')) . " " . ($delivery['street_num'] ?? '') . "<br>
                    <b>Куриер:</b> " . ($delivery['izborKurier'] ?? '') . "<br>
                    ";

                    $message = "
                    <div style='font-family:Verdana;padding:10px'>
                        <a href='{$_ENV['app.baseURL']}'>
                            <img src='{$_ENV['app.imagePortalDir']}logo/logo-email.png' style='width:200px'>
                        </a>
                        <br><br>
                        <table width='100%' border='1' cellspacing='0' cellpadding='5'>
                            <tr><td colspan='2'><b>Данни за поръчката</b></td></tr>
                            <tr>
                                <td><b>Поръчка №:</b> {$orderId}<br><b>Дата:</b> " . date('d-m-Y H:i:s') . "<br><b>Метод:</b> Плащане с карта</div></td>
                                <td><b>Имейл:</b> {$email}<br><b>Телефон:</b> " . ($delivery['tel'] ?? '') . "<br><b>Статус:</b> Платена</div></td>
                            </tr>
                        </table>
                        <br>
                        <table width='100%' border='1' cellspacing='0' cellpadding='5'>
                            <td><td colspan='2'><b>Доставка</b></td></tr>
                            <tr><td>{$deliveryHtml}</td></tr>
                        </table>
                        <br>
                        <table width='100%' border='1' cellspacing='0' cellpadding='5'>
                            <thead><tr><th>#</th><th>Снимка</th><th>Продукт</th><th>Модел</th><th>Код</th><th>Кол.</th><th>Цена</th><th>Общо</th></tr></thead>
                            <tbody>{$rows}</tbody>
                            <tfoot><tr><td colspan='7' align='right'><b>Тотал с ДДС:</b></td><td><b>" . number_format($total, 2) . " {$currency}</b></td></tr></tfoot>
                        </table>
                    </div>
                    ";

                    $this->sendEmail($email, "Поръчка №{$orderId} - успешно плащане", $message);
                }
            }
        }


        $this->setSessionProperty([]);
        
        if ($user_id) {
           // session()->destroy(); 
        }
        
        return redirect()->to('/')->with('success', 'Поръчката е платена успешно!');
    }

    public function fail()
    {
        $user_id = session('user_id');
        
        if ($user_id) {
           // session()->destroy();
        }
        
        return redirect()->to('/')->with('error', 'Неуспешно плащане. Моля опитайте отново.');
    }
}
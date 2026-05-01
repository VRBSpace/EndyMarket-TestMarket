<?php
helper('euroRate');
$_userId = session()->has('user_id');
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f5f7;
            color: #111213;
            font-family: Arial, Helvetica, sans-serif;
        }

        img {
            border: 0;
            display: block;
            outline: none;
            text-decoration: none;
        }

        a {
            color: #111213;
            text-decoration: none;
        }

        .card {
            background-color: #ffffff;
            border: 1px solid #eeeeee;
            border-radius: 12px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #111213;
        }

        .muted {
            color: #6b6f76;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <?php
    // ако е клиентска поръчка без логин
    $val = null;
    if (!empty($orderData['delivery_json'])) {
        $val = json_decode($orderData['delivery_json']) ?? null;
    }
    $emailVal = $val->email ?? '';
    $telVal   = $val->tel ?? '';
    $baseUrlRaw = trim($_ENV['app.baseURL'] ?? '', " \t\n\r\0\x0B'\"");
    $baseUrl = rtrim($baseUrlRaw, '/');
    $logoBase = trim($_ENV['app.imagePortalDir'] ?? '', " \t\n\r\0\x0B'\"");
    $logoUrl = 'https://media.testmarketbg.com/portal_images/logo/ND_market_logotype_2025.webp';
    $truckUrl = rtrim($logoBase, '/') . '/logo/email-cart.png';
    ?>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f5f7; padding: 20px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width: 640px; width: 100%;">
                    <tr>
                        <td style="padding: 0 20px 16px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" class="card">
                                <tr>
                                    <td align="center" style="padding: 24px 20px;">
                                        <a href="<?= $baseUrl ?>" style="display: inline-block;">
                                            <img src="<?= $logoUrl ?>" alt="Лого" style="max-width: 220px; width: 60%; height: auto;">
                                        </a>
                                        <div style="font-size: 24px; font-weight: bold; margin-bottom: 6px; color:#111213;">
                                            Благодарим ви за вашата поръчка!
                                        </div>
                                        <div style="font-size: 14px; color:#6b6f76; margin-bottom: 16px;">
                                            Вашата поръчка е приета и се подготвя за изпращане.
                                        </div>
                                        <img src="<?= $truckUrl ?>" alt="Пратката се подготвя" style="width: 64px; height: 64px;">
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 0 20px 16px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" class="card">
                                <tr>
                                    <td style="padding: 14px 20px; background-color:#fff7e6; border-bottom:1px solid #f1e2c3;">
                                        <span class="section-title">Данни за поръчката</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 14px 20px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td valign="top" style="width:50%; padding-right:10px; font-size:13px; color:#111213;">
                                                    <div><strong>Поръчка №:</strong> <?= $orderId ?? '' ?></div>
                                                    <div><strong>Дата:</strong> <?= date("d-m-Y") ?></div>
                                                    <div><strong>Метод на плащане:</strong>
                                                        <?php
                                                        echo match ($orderData['payment_method']) {
                                                            'N' => 'в брой',
                                                            'B' => 'банков превод',
                                                            default => ''
                                                        }
                                                        ?>
                                                    </div>
                                                </td>
                                                <td valign="top" style="width:50%; padding-left:10px; font-size:13px; color:#111213;">
                                                    <div><strong>Имейл:</strong> <?= $emailVal ?></div>
                                                    <div><strong>Телефон:</strong> <?= $telVal ?></div>
                                                    <div><strong>Статус:</strong> В процес на изчакване</div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 0 20px 16px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" class="card">
                                <tr>
                                    <td style="padding: 14px 20px; background-color:#fff7e6; border-bottom:1px solid #f1e2c3;">
                                        <span class="section-title">Данни за доставка</span>
                                    </td>
                                </tr>

                                <?php if (!empty($orderData['delivery_json'])): ?>
                                    <?php
                                    $_ofis          = $val->ofis ?? '';
                                    $_grad          = $val->grad ?? '';
                                    $_postCode      = $val->postCode ?? '';
                                    $_kvartal       = $val->quarter ?? '';
                                    $_ulica         = $val->street ?? '';
                                    $_ulicaNo       = $val->street_num ?? '';
                                    $_blockNo       = $val->block_no ?? '';
                                    $_floorNo       = $val->floor_no ?? '';
                                    $_apartmentNo   = $val->apartment_no ?? '';
                                    $_entranceNo    = $val->entrance_no ?? '';
                                    $_shipping_code = isset($val->izborKurier) ? $val->izborKurier : '';
                                    $_other         = $val->other ?? '';
                                    ?>
                                    <tr>
                                        <td style="padding: 14px 20px;">
                                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td valign="top" style="width:50%; padding-right:10px; font-size:13px; color:#111213;">
                                                        <?php if (!empty($firma ?? '')): ?>
                                                            <div><strong>Фирма:</strong> <?= $firma ?? '' ?></div>
                                                        <?php endif ?>
                                                        <div><strong>Лице за контакт:</strong> <?= $val->lice_zaKont ?? '' ?></div>
                                                        <div><strong>Телефон:</strong> <?= $val->tel ?? '' ?></div>
                                                        <div><strong>Населено място:</strong> <?= $_grad ?></div>
                                                        <div><strong>Пощенски код:</strong> <?= $_postCode ?></div>
                                                    </td>
                                                    <td valign="top" style="width:50%; padding-left:10px; font-size:13px; color:#111213;">
                                                        <div><strong>Куриер:</strong>
                                                            <?php
                                                            echo match ($_shipping_code) {
                                                                'speedy_machina' => 'до автомат на Speedy',
                                                                'speedy_office' => 'до офис на Speedy',
                                                                'speedy_door' => 'до адрес на Speedy',
                                                                'econt_machina' => 'до еконтомат на Еконт',
                                                                'econt_office' => 'до офис на Еконт',
                                                                'econt_door' => 'до адрес на Еконт',
                                                                default => ''
                                                            }
                                                            ?>
                                                        </div>
                                                        <?php if (!empty($_ofis)): ?>
                                                            <div><strong>Офис:</strong> <?= $_ofis ?></div>
                                                        <?php else: ?>
                                                            <div><strong>Квартал:</strong> <?= $_kvartal ?></div>
                                                            <div><strong>Улица:</strong> <?= $_ulica ?> № <?= $_ulicaNo ?></div>
                                                            <div><strong>Блок:</strong> <?= $_blockNo ?>, вх. <?= $_entranceNo ?>, ет. <?= $_floorNo ?>, ап. <?= $_apartmentNo ?></div>
                                                            <div><strong>Друго:</strong> <?= $_other ?></div>
                                                        <?php endif ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <tr>
                                        <td style="padding: 14px 20px; font-size:13px; color:#111213;">
                                            <?= $orderData['delivery_method'] ?? '' ?>
                                        </td>
                                    </tr>
                                <?php endif ?>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 0 20px 16px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" class="card">
                                <tr>
                                    <td style="padding: 14px 20px; background-color:#fff7e6; border-bottom:1px solid #f1e2c3;">
                                        <span class="section-title">Продукти</span>
                                    </td>
                                </tr>
                                <?php
                                $_totalOrderPrice = 0;
                                foreach ($products as $product):
                                    if (isset($product['product_id'])):
                                        $_totalOrderPrice += $product['total_price'];
                                ?>
                                        <tr>
                                            <td style="padding: 16px 20px; border-bottom: 1px solid #f1f1f1;">
                                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td width="80" valign="top" style="padding-right: 12px;">
                                                            <a href="<?= site_url() . route_to('Shop-singleProduct', $product['product_id']) ?>">
                                                                <img src="<?= $product['product_info']->image ? $_ENV['app.imageDir'] . $product['product_info']->image : '' ?>" onerror="this.onerror=null;this.src='<?= $_ENV['app.noImage'] ?>';" alt="Продукт" style="width: 70px; height: auto; border: 1px solid #eeeeee; border-radius: 6px;">
                                                            </a>
                                                        </td>
                                                        <td valign="top" style="font-size: 13px; color:#111213;">
                                                            <div style="font-weight: bold; margin-bottom: 6px;">
                                                                <a href="<?= base_url() . route_to('Shop-singleProduct', $product['product_id']) ?>">
                                                                    <?= $product['product_info']->product_name ?>
                                                                </a>
                                                            </div>
                                                            <div class="muted">Модел: <?= $product['product_info']->model ?></div>
                                                            <div class="muted">Код: <?= $product['product_info']->kod ?></div>
                                                            <div class="muted">Кол.: <?= $product['quantity'] ?></div>
                                                        </td>
                                                        <td valign="top" align="right" style="font-size: 13px; color:#111213; white-space: nowrap;">
                                                            <div class="muted">Цена</div>
                                                            <div style="font-weight: bold; color:#ee1a19;"><?= priceDual($product['item_price']) ?></div>
                                                            <div class="muted" style="margin-top: 6px;">Общо</div>
                                                            <div style="font-weight: bold;"><?= priceDual($product['total_price']) ?></div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    <?php endif ?>
                                <?php endforeach ?>

                                <tr>
                                    <td style="padding: 12px 20px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                            <?php if ($_userId): ?>
                                                <tr>
                                                    <td align="right" style="font-size: 13px; color:#111213; padding: 4px 0;">Междинна сума:</td>
                                                    <td align="right" style="font-size: 13px; color:#111213; padding: 4px 0; width: 160px;"><?= priceDual($_totalOrderPrice) ?></td>
                                                </tr>
                                                <tr>
                                                    <td align="right" style="font-size: 13px; color:#111213; padding: 4px 0;">ДДС:</td>
                                                    <td align="right" style="font-size: 13px; color:#111213; padding: 4px 0; width: 160px;"><?= priceDual($_totalOrderPrice * 0.2) ?></td>
                                                </tr>
                                            <?php endif ?>

                                            <?php if ($orderData['transportenRazhod']): ?>
                                                <tr>
                                                    <td align="right" style="font-size: 13px; color:#111213; padding: 4px 0;">Доставка:</td>
                                                    <td align="right" style="font-size: 13px; color:#111213; padding: 4px 0; width: 160px;"><?= priceDual($orderData['transportenRazhod']) ?></td>
                                                </tr>
                                            <?php endif ?>

                                            <tr>
                                                <td align="right" style="font-size: 14px; color:#111213; padding: 6px 0; font-weight:bold;">Тотал с ДДС:</td>
                                                <td align="right" style="font-size: 14px; color:#111213; padding: 6px 0; font-weight:bold; width: 160px;"><?= priceDual($_totalOrderPrice * ($_userId ? 1.2 : 1) + ($orderData['transportenRazhod'] ?? 0)) ?></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <?php if (!empty($belezka)): ?>
                        <tr>
                            <td style="padding: 0 20px 16px;">
                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" class="card">
                                    <tr>
                                        <td style="padding: 14px 20px; background-color:#fff7e6; border-bottom:1px solid #f1e2c3;">
                                            <span class="section-title">Коментар</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 14px 20px; font-size:13px; color:#111213;">
                                            <?= $belezka ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    <?php endif ?>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
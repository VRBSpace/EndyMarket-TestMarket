<?php $_userId = session() -> has('user_id') ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <style>
            table tbody td{
                padding: 2px;
            }
            .float-right{
                text-align: right;
            }

        </style>
    </head>

    <body style="padding:0px; margin:0px">
        <div style="width: 100%">

            <a href="<?php echo $_ENV['app.baseURL'] ?>">
                <img src="<?= $_ENV['app.imagePortalDir'] . 'logo/logo-email.png' ?>" alt="..."  style="width: 50%;margin-bottom: 10px; border: none;">
            </a>

            <?php
            // ако е клиентска поръчка без логин
            if (!empty($orderData['delivery_json'])) {
                $val = json_decode($orderData['delivery_json']) ?? null;
            }
            ?>

            <!--[if gte MSO 9]>
                <table width="640">
                   <tr>
                      <td>
              <![endif]-->
            <table role="presentation" style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 10px;">
                <thead>
                    <tr>
                        <td style="font-size: 16px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 7px; color: #222222;" colspan="2">Данни за поръчката</td>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td style="font-size: 12px;border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;">
                            <b>Поръчка №: </b><?php echo $orderId ?? '' ?><br>
                            <b>Дата на добавяне: </b><?php echo date("d-m-Y") ?><br>

                            <b>Метод на плащане: </b><?php
                            echo match ($orderData['payment_method']) {
                                'N' => 'в брой',
                                'B' => 'банков превод',
                                default => ''
                            }
                            ?><br>
                        </td>

                        <td style="font-size: 12px;border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;">
                            <b>Имейл: </b><?php echo $val -> email ?? '' ?><br>
                            <b>Телефон: </b><?php echo $val -> tel ?? '' ?><br>
                            <b>Статус на поръчката: </b> В процес на изчакване<br>
                        </td>
                    </tr>
                </tbody>
            </table>
            <!--[if gte MSO 9]>
                          </td>
                        </tr>
                      </table>
                    <![endif]-->

            <?php
            if (!empty($orderData['delivery_json'])):

                $_ofis          = $val -> ofis ?? '';
                $_grad          = $val -> grad ?? '';
                $_postCode      = $val -> postCode ?? '';
                $_kvartal       = $val -> quarter ?? '';
                $_ulica         = $val -> street ?? '';
                $_ulicaNo       = $val -> street_num ?? '';
                $_blockNo       = $val -> block_no ?? '';
                $_floorNo       = $val -> floor_no ?? '';
                $_apartmentNo   = $val -> apartment_no ?? '';
                $_entranceNo    = $val -> entrance_no ?? '';
                $_shipping_code = isset($val -> izborKurier) ? $val -> izborKurier : '';
                $_other         = $val -> other ?? '';
                ?>

                <!--[if gte MSO 9]>
                  <table width="640">
                     <tr>
                        <td>
                <![endif]-->
                <table role="presentation" style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 10px;">
                    <thead>
                        <tr>
                            <td style="font-size: 16px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 7px; color: #222222;" colspan="2">Данни за доставка</td>
                        </tr>
                    </thead>

                   <tbody class="notAutoNum">
                        <tr>  
                            <td style="font-size: 12px;border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;">
                                <b>Фирма: </b><?php echo $firma ?? '' ?><br>
                                <b>Лице за контакт: </b><?php echo $val -> lice_zaKont ?? '' ?><br>
                                <b>Телефон: </b><?php echo $val -> tel ?? '' ?><br>
                                <b>Населено място: </b><?php echo $_grad ?><br>
                                <b>Пощенски код: </b><?php echo $_postCode ?><br>
                            </td>

                            <td style="font-size: 12px;border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;">
                                <b>Куриер </b><?php
                                echo match ($_shipping_code) {
                                    'speedy_machina' => 'до автомат на Speedy',
                                    'speedy_office' => 'до офис на Speedy',
                                    'speedy_door' => 'до адрес на Speedy',
                                    'econt_machina' => 'до еконтомат на Еконт',
                                    'econt_office' => 'до офис на Еконт',
                                    'econt_door' => 'до адрес на Еконт',
                                    default => ''
                                }
                                ?><br>
                                <?php if (!empty($_ofis)): ?>
                                    <b>Офис </b><?php echo $_ofis ?><br>
                                    <br><br>
                                <?php else: ?>
                                    <b>Квартал </b><?php echo $_kvartal ?><br>
                                    <b>Улица </b><div class="input-group-prepend">
                                        <?= $_ulica ?>
                                        <span class="p-1">№</span>
                                        <?= $_ulicaNo ?>
                                    </div><br>

                                    <b>Блок </b><div class="w-40 input-group-prepend justify-content-between">
                                        <?= $_blockNo ?>
                                        <span>вх.</span>
                                        <?= $_entranceNo ?>
                                        <span>ет.</span>
                                        <?= $_floorNo ?>
                                        <span>ап.</span>
                                        <?= $_apartmentNo ?>
                                    </div><br>

                                    <b>Друго </b><?php echo $_other ?><br>
                                <?php endif ?>
                            </td>
                        </tr> 
                    </tbody>
                </table>
                <!--[if gte MSO 9]>
                         </td>
                       </tr>
                     </table>
                   <![endif]-->
            <?php else: echo '<div style="font-size: 16px; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 7px; color: #222222;">Данни за доставка</div><br>' . ($orderData['delivery_method'] ?? '') . '<br>' ?>
            <?php endif ?>

            <!--[if gte MSO 9]>
                <table width="640">
                   <tr>
                      <td>
              <![endif]-->
            <table role="presentation" align="center" width="100%" border="1" cellpadding="0" cellspacing="0" style="table-layout:fixed;border-collapse:collapse;  font-family:Verdana, Geneva, sans-serif;">
                <thead>
                    <tr>
                        <th width="30"></th>
                        <th width="70">Снимка</th>
                        <th width="400">Продукт</th>
                        <th width="200">Модел</th>
                        <th width="100">Код</th>
                        <th width="50">Кол.</th>
                        <th width="80">Цена</th>
                        <th width="80">Общо</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $_totalOrderPrice = 0;
                    $i                = 0;

                    foreach ($products as $product):
                        if (isset($product['product_id'])):
                            $_totalOrderPrice += $product['total_price'];
                            ?>
                            <tr>
                                <td style="text-align: center;"><?= $i                = ($i + 1) ?></td>  
                                <td style="text-align: start;">
                                    <a href="<?= site_url() . route_to('Shop-singleProduct', $product['product_id']) ?>">
                                        <img style="max-width: 100%" src="<?= $product['product_info'] -> image ? $_ENV['app.imageDir'] . $product['product_info'] -> image : '' ?>" onerror="this.onerror=null;this.src='<?= $_ENV['app.noImage'] ?>';" alt="...">
                                    </a>
                                </td>

                                <td>          
                                    <a href="<?= base_url() . route_to('Shop-singleProduct', $product['product_id']) ?>" class="text-blue"><?= $product['product_info'] -> product_name ?></a>
                                </td>

                                <td style="text-align: center;word-wrap: break-word;"><?= $product['product_info'] -> model ?></td>
                                <td style="text-align: center;word-wrap: break-word;"><?= $product['product_info'] -> kod ?></td>

                                <td style="text-align: center;"><?= $product['quantity'] ?></td>
                                <td style="text-align: center;"><?= sprintf("%.2f", $product['item_price']) ?> <?= get_valuta() ?></td>
                                <td style="text-align: right;"><?= sprintf("%.2f", $product['total_price']) ?> <?= get_valuta() ?></td>                                                  
                            </tr>
                        <?php endif ?>
                    <?php endforeach ?>
                </tbody>

                <tfoot>
                    <?php if ($_userId): ?>
                        <tr>
                            <td colspan="7" style="text-align: right;">
                                <span class="float-right">Междинна сума:</span>
                            </td>

                            <td><?= sprintf("%.2f", $_totalOrderPrice) ?> <?= get_valuta() ?> </td>
                        </tr>

                        <tr>
                            <td colspan="7" style="text-align: right;">
                                <span class="float-right">ДДС:</span>
                            </td>

                            <td><?= sprintf("%.2f", ($_totalOrderPrice * 0.2)) ?> <?= get_valuta() ?></td>
                        </tr>
                    <?php endif ?>

                    <!-- транспортни разходи -->
                    <?php if ($orderData['transportenRazhod']): ?>
                        <tr>
                            <td colspan="7" style="text-align: right;">
                                <span class="float-right">Доставка:</span>
                            </td>

                            <td><?= sprintf("%.2f", $orderData['transportenRazhod']) ?> <?= get_valuta() ?></td>
                        </tr>
                    <?php endif ?>

                    <tr>
                        <td colspan="7" style="text-align: right;">
                            <span class="float-right">Тотал с ДДС:</span>
                        </td>

                        <td><?= sprintf("%.2f", ($_totalOrderPrice * ($_userId ? 1.2 : 1) + ($orderData['transportenRazhod'] ?? 0))) ?> <?= get_valuta() ?></td>
                    </tr>
                </tfoot>
            </table>
            <!--[if gte MSO 9]>
                </td>
              </tr>
            </table>
          <![endif]-->

            <br>

            <?php if (!empty($belezka)): ?>
                <label>Коментар към поръчката</label>
                <div><?= $belezka ?></div>
            <?php endif ?>
        </div>
    </body>
</html>

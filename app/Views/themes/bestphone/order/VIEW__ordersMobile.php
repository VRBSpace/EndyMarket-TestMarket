<!-- ========== MAIN CONTENT ========== -->
<main class="conteiner-fluid" role="main">
    <div class="col">
        <div class="row mb-4 px-3">
            <h3 class="section-title mb-0 text-center border-bottom border-color-1">ПОРЪЧКИ <small>СПИСЪК</small></h3>
        </div>

        <a id="sidebarNavToggler1" class="btn px-1 font-weight-normal" href="javascript:;" role="button"
           data-unfold-event="click"
           data-unfold-hide-on-scroll="false"
           data-unfold-target="#sidebarOrderContent"
           data-unfold-type="css-animation"
           data-unfold-animation-in="fadeInLeft"
           data-unfold-animation-out="fadeOutLeft"
           data-unfold-duration="500">
            <i class="fas fa-sliders-h"></i> 

            <span class="ml-1">Филтри</span>
        </a>

        <?php
        if (session('message')) {
            $msg = session('message');
            echo "<div class='alert text-danger'>$msg</div>";
        }
        ?>

        <style>
            .order-row {
                border: 1px solid #ccc;
                padding: 10px;
                background: #fff;
                border-radius: 5px;
                box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
                display: flex;
                flex-direction: column;
            }
            /* Скритата част (допълнителна информация) */
            .order-details {
                margin-top: 10px;
                font-size: 14px;
            }
        </style>

        <?php
        $_status = array_column($status, null, 'sp_status_id');

        foreach ($orders as $order):
            $_invoiceNo      = $order['invoice_no'] ?? '';
            $_bgClass        = $_status[$order['sp_status_id']]['color'] ?? '';
            $_textColor      = $_status[$order['sp_status_id']]['text_color'] ?? 'text-white';
            $timestamp       = strtotime($order['date_added']);
            $dateWithoutTime = date('Y-m-d', $timestamp);
            ?>

            <div class="mb-2">
                <div class="order-row">
                    <!-- Видима част -->
                    <div>
                        <div>
                            <strong>Поръчка №:&nbsp;</strong>
                            <span class="js-orderId"><?= $order['order_id'] ?></span>
                        </div>

                        <div>
                            <strong>Статус:&nbsp;</strong>
                            <span class="js-status px-1 rounded border <?= $_textColor . ' ' . $_bgClass ?>" style="<?= $order['sp_status_id'] == 6 ? 'background:#faf74c' : '' ?>"><?= $_status[$order['sp_status_id']]['status_name'] ?? 'не приключена' ?>
                            </span>
                        </div>

                        <div class="mt-2">
                            <button class="mobile-details btn border p-1 rounded-0 float-right"><i class="fa fa-info-circle"></i>&nbsp;Инфо</button>

                            <a class="btn border p-1 rounded-0 float-right" href="<?= route_to('OrderDetail-getById', $order['order_id']) ?>">
                                <i class="fa fa-eye"></i>&nbsp;Преглед
                            </a>
                        </div>
                    </div>

                    <!-- Скритата част (допълнителни колони) -->
                    <div class="order-details" style="display: none;">
                        <div><strong>Обща цена с ДДС:&nbsp;</strong><?= sprintf("%.2f", ($order['total_price'] * 1.2 + $order['transportenRazhod'])) ?> <?= get_valuta() ?></div>

                        <div>
                            <strong>Документ №:&nbsp;</strong>
                            <span class="js-doc"> <?= !empty($_invoiceNo) ? $order['invoice_tip'] . ' №: ' . $_invoiceNo : '' ?>
                            </span>
                        </div>

                        <div>
                            <strong>Товарителница №:&nbsp;</strong>
                            <span class="js-tovaritelniza">
                                <?= $order['tavaritelniza_no'] ?? '' ?>
                            </span>
                        </div>

                        <div><strong>Документи:&nbsp;</strong> <?php
                            if (!empty($order['documents'])):
                                foreach ($order['documents']['path'] as $path):

                                    $_orderId = $order['documents']['orderId'];
                                    $pathinfo = pathinfo($path);

                                    $_docPatch = $_ENV['app.imageDataDir'] . 'documents/order/site/' . $_orderId . '/' . $pathinfo['filename'] . '.' . $pathinfo['extension'];
                                    ?>
                                    <a href="<?= $_docPatch ?>" class="far fa-file" target="_blank" title="<?= $pathinfo['filename'] . '.' . $pathinfo['extension'] ?>"></a>    
                                    <?php
                                endforeach;
                            endif;
                            ?></div>

                        <div><strong>Плащане:&nbsp;</strong><?=
                            match ($order['payment_method']) {
                                'N' => 'в брой',
                                'B' => 'банков превод',
                                default => ''
                            }
                            ?>
                        </div>

                        <div><strong>Метод на доставка:&nbsp;</strong><?php
                            $_mapDeliveryMetod = [
                                'firmCar' => 'с фирмен транспорт',
                                'selfCar' => 'със собствен транспорт',
                            ];

                            $_deliveryObekt  = json_decode($order['delivery_json'] ?? '[]', true);
                            $_deliveryMethod = $order['delivery_method'] ?? '';

                            if (!empty($_deliveryObekt) && $_deliveryMethod == 'curier') {
                                echo match ($_deliveryObekt['izborKurier'] ?? '') {
                                    'speedy_machina' => 'до автомат на Speedy',
                                    'speedy_office' => 'до офис на Speedy',
                                    'speedy_door' => 'до адрес със Speedy',
                                    'econt_machina' => 'до еконтомат на Еконт',
                                    'econt_office' => 'до офис на Еконт',
                                    'econt_door' => 'до адрес със Еконт',
                                    default => ''
                                };
                            }

                            echo $_mapDeliveryMetod[$_deliveryMethod] ?? '';
                            ?>
                        </div>

                        <div>
                            <strong>Дата на създаване:&nbsp;</strong>
                            <span class="js-date" data-date="<?= $dateWithoutTime ?>"><?= $order['date_added'] ?> </span>
                        </div>
                    </div>
                </div>

            </div>
        <?php endforeach ?>
    </div>
</main>

<?php
echo view("{$_ENV['app.theme']}/layouts/sidebar/VIEW__sidebar-account");  // Account Sidebar Navigation
echo view("{$_ENV['app.theme']}/layouts/sidebar/VIEW__sidebar-order_filters"); //Sidebar Navigation
echo view("{$_ENV['app.theme']}/layouts/footer/VIEW__footer");
?>

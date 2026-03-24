<?php if ($customConfig -> isVisible['show_logo_inExpOrder']): ?>
    <div>
        <img id="headerImg" src="<?= $_ENV['app.imageDataDir'] . '/eshop/logo/pdfPrintLogo.jpg' ?>" onerror="this.onerror=null;this.src='<?= $_ENV['app.noImage'] ?>';" alt="">
    </div>
<?php endif ?>

<div id="printArea" class="container">
    <div class="row mb-4 justify-content-center">
        <h2 class="section-title text-center">Детайлна поръчка N: <?= $orderData -> order_id ?>
        </h2>
    </div>

    <div class="col-xl-12 col-wd-12gdot5">
        <div class="mb-8">

            <? // dd($products)  ?>
            <div class="bg-white border border-color-1 rounded p-3 mb-4">
                <div class="table-responsive">
                    <table class="css-product css-cart-table table table-hover table-sm mb-0">
                        <thead class="banner-bg">
                            <tr>
                                <th class="col-1" style="width:4%"></th>
                                <th style="width:10%">Снимка</th>
                                <th>Име на продукта</th>
                                <th style="width:10%">Кол.</th>
                                <th style="width:10%">Ед. цена</th>
                                <th style="width:15%">Обща цена без ДДС</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            foreach ($products as $k => $product):
                                $_productJson = json_decode($product['product_json']) -> {$product['product_id']};
                                $_qty         = $_productJson -> qty;
                                $_price       = sprintf("%.2f", $_productJson -> price);
                                $_total_price = sprintf("%.2f", $_qty * $_price);
                                ?>
                                <tr>
                                    <td class="text-center"><?= $k + 1 ?></td>  

                                    <td class="text-center">
                                        <img class="css-img img-fluid max-width-100 p-1 border border-color-1" src="<?= $product['image'] ? $_ENV['app.imageDir'] . $product['image'] : '' ?>" onerror="this.onerror=null;this.src='<?= $_ENV['app.noImage'] ?>';" alt="...">
                                    </td>

                                    <td>          
                                        <a href="<?= route_to('Shop-singleProduct', $product['product_id']) ?>" class="text-blue"><?= $product['product_name'] ?></a>
                                    </td>

                                    <td class="text-center"><?= $_qty ?></td>
                                    <td class="text-center"><?= $_price ?> <?= get_valuta() ?></td>
                                    <td class="text-right"><?= $_total_price ?> <?= get_valuta() ?></td>                                                  
                                </tr>
                            <?php endforeach; ?>
                        </tbody>

                        <tfoot>
                            <tr class="text-dark">
                                <td colspan="5">
                                    <span class="float-right fw-bold text-dark">Сума без ДДС:</span>
                                </td>

                                <td class="fw-bold text-dark"><?= sprintf("%.2f", $orderData -> total_price) ?> <?= get_valuta() ?> </td>
                            </tr>

                            <tr class="text-dark">
                                <td colspan="5">
                                    <span class="float-right fw-bold text-dark">ДДС:</span>
                                </td>

                                <td class="fw-bold text-dark"><?= sprintf("%.2f", ( $orderData -> total_price * 0.2)) ?> <?= get_valuta() ?></td>
                            </tr>

                            <?php if ($orderData -> transportenRazhod): ?>
                                <tr class="text-dark">
                                    <td colspan="5">
                                        <span class="float-right fw-bold text-dark">Доставка:</span>
                                    </td>

                                    <td class="fw-bold text-dark"><?= sprintf("%.2f", $orderData -> transportenRazhod) ?> <?= get_valuta() ?></td>
                                </tr>
                            <?php endif ?>

                            <tr class="text-dark">
                                <td colspan="5">
                                    <span class="float-right fw-bold text-dark">Тотал с ДДС:</span>
                                </td>

                                <td class="fw-bold text-dark"><?= sprintf("%.2f", ( $orderData -> total_price * 1.2)) ?> <?= get_valuta() ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <?php
            $val = json_decode($orderData -> delivery_json ?? '[]');

            $_ofis                 = $val -> ofis ?? '';
            $_grad                 = $val -> grad ?? '';
            $_postCode             = $val -> postCode ?? '';
            $_kvartal              = $val -> quarter ?? '';
            $_ulica                = $val -> street ?? '';
            $_ulicaNo              = $val -> street_num ?? '';
            $_blockNo              = $val -> block_no ?? '';
            $_floorNo              = $val -> floor_no ?? '';
            $_apartmentNo          = $val -> apartment_no ?? '';
            $_entranceNo           = $val -> entrance_no ?? '';
            $_shipping_code        = isset($val -> izborKurier) ? $val -> izborKurier : '';
            $_other                = $val -> other ?? '';
            $_showAdresBlock       = in_array($_shipping_code, ['econt_door', 'speedy_door']) ? '' : 'hide';
            $_showOfficeBlock      = in_array($_shipping_code, ['econt_office', 'econt_machina', 'speedy_office', 'speedy_machina']) ? '' : 'hide';
            $_showBtnLocatorEcont  = in_array($_shipping_code, ['econt_office', 'econt_machina']) ? '' : 'hide';
            $_showBtnLocatorSpeedy = in_array($_shipping_code, ['speedy_office', 'speedy_machina']) ? '' : 'hide';
            $_isAllowed            = in_array($_shipping_code, ['econt_door', 'speedy_door']) ? '' : 'css-pointer-events-none';
            ?>

            <div class="bg-white border border-color-1 rounded p-3 mb-4">
                <h3 class="section-title">Метод на плащане</h3>
                <div class="mb-4">
                    <?=
                    match ($orderData -> payment_method) {
                        'N' => 'в брой',
                        'B' => 'банков превод',
                        default => ''
                    }
                    ?>
                </div>

                <h3 class="section-title">Данни за доставка</h3>

                <?php if (!empty($val)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-sm w-40 mb-0">
                            <thead class="banner-bg">
                                <tr>
                                    <th class="col-2"></th>
                                    <th class="col-4"></th>
                                </tr>
                            </thead>

                            <tbody class="notAutoNum">
                                <tr>
                                    <td>Лице за контакт:</td>
                                    <td><?= $val -> lice_zaKont ?></td>
                                </tr>

                                <tr>
                                    <td>Телефон:</td>
                                    <td><?= $val -> tel ?></td>
                                </tr>

                                <?php if ($orderData -> delivery_method): ?>
                                    <tr>
                                        <td>Метод на доставка:</td>

                                        <td>
                                            <?php
                                            $_mapDeliveryMetod = [
                                                'firmCar' => 'с фирмен транспорт',
                                                'selfCar' => 'със собствен транспорт',
                                                'curier'  => 'с куриер'
                                            ];
                                            $_deliveryMetod    = $orderData -> delivery_method ?? '';
                                            echo $_mapDeliveryMetod[$_deliveryMetod] ?? '';
                                            ?>
                                        </td>
                                    </tr>

                                <?php else: ?>
                                    <tr>
                                        <td>Куриер:</td>
                                        <td> 
                                            <?php
                                            echo match ($_shipping_code) {
                                                'speedy_machina' => 'до автомат на Speedy',
                                                'speedy_office' => 'до офис на Speedy',
                                                'speedy_door' => 'до адрес на Speedy',
                                                'econt_machina' => 'до еконтомат на Еконт',
                                                'econt_office' => 'до офис на Еконт',
                                                'econt_door' => 'до адрес на Еконт',
                                                default => ''
                                            };
                                            ?>
                                        </td>
                                    </tr>
                                <?php endif ?>

                                <tr>
                                    <td>Населено място:</td>
                                    <td><?= $_grad ?></td>
                                </tr>

                                <tr>
                                    <td>Пощенски код:</td>
                                    <td><?= $_postCode ?></td>
                                </tr>

                                <tr class="<?= $_showOfficeBlock ?>">
                                    <td>Офис:</td>
                                    <td><?= $_ofis ?></td>
                                </tr>

                                <tr class="<?= $_showAdresBlock ?>">
                                    <td>Квартал</td>
                                    <td><?= $_kvartal ?></td>
                                </tr>

                                <tr class="<?= $_showAdresBlock ?>">
                                    <td>Улица</td>

                                    <td>
                                        <div class="input-group-prepend">
                                            <?= $_ulica ?>
                                            <span class="p-1">№</span>
                                            <?= $_ulicaNo ?>
                                        </div>
                                    </td>
                                </tr>

                                <tr class="<?= $_showAdresBlock ?>">
                                    <td>Блок</td>
                                    <td>
                                        <div class="w-40 input-group-prepend justify-content-between">
                                            <?= $_blockNo ?>
                                            <span>вх.</span>
                                            <?= $_entranceNo ?>
                                            <span>ет.</span>
                                            <?= $_floorNo ?>
                                            <span>ап.</span>
                                            <?= $_apartmentNo ?>
                                        </div>  
                                    </td>
                                </tr>

                                <tr class="<?= $_showAdresBlock ?>">
                                    <td>Друго</td>
                                    <td><?= $_other ?> </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                <?php else: echo $orderData -> delivery_method ?>

                <?php endif ?>
            </div>
        </div>
    </div>
</div>

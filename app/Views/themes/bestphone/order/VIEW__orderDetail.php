<?= view("{$_ENV['app.theme']}/layouts/header/VIEW__head") ?>
<?= view("{$_ENV['app.theme']}/layouts/header/VIEW__header") ?>

<!-- ========== MAIN CONTENT ========== -->
<main class="container" role="main">
    <div class="row mb-4 justify-content-center">
        <h2 class="section-title text-center">Детайлна поръчка N: <?= $orderData -> order_id ?></h2>
    </div>

    <div class="col-xl-12 col-wd-12gdot5">
        <div id="msg-place"></div>

        <div class="bg-white border border-color-1 rounded p-3 mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                <a class="btn btn-outline-orange rounded-0 p-2" href="<?= route_to('Order-index') ?>">
                  <i class="fas fa-angle-left"></i> Към поръчките
                </a>

                <div class="d-flex align-items-center flex-wrap">
                    <?php
                    $_status           = array_column($status, null, 'sp_status_id');
                    $_bgClass          = $_status[$orderData -> sp_status_id]['color'] ?? 'bg-red';
                    $_textColor        = $_status[$orderData -> sp_status_id]['text_color'] ?? 'text-white';
                    ?>
                    <h6 class="mb-0 p-2 <?= $_textColor . ' ' . $_bgClass ?>">Статус: <?= $_status[$orderData -> sp_status_id]['status_name'] ?? 'не приключена' ?></h6> 
                    <a id="createPdf" class="btn btn-primary-orange rounded-0 ml-2" data-route="<?= route_to('Order-pdf_export', $orderData -> order_id) ?>" data-name="<?= $orderData -> order_id ?>" href="javascript:;">
                        Печат
                    </a>
                </div>
            </div>

            <?php if (ISMOBILE): ?>
                <?= view($views['orderDetail-itemsMobile']) ?>
            <?php else: ?>
                <div class="table-responsive">
                    <?= view($views['orderDetail-items']) ?>
                </div>
            <?php endif; ?>
        </div>


                <?php
                $_mapDeliveryMetod = [
                    'firmCar' => 'с фирмен транспорт',
                    'selfCar' => 'със собствен транспорт',
                    'curier'  => 'с куриер'
                ];

                $_deliveryMetod = $orderData -> delivery_method ?? '';

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

                <?php if (!empty($get_documents['docs'])): ?>
                    <div class="bg-white border border-color-1 rounded p-3 mb-4">
                        <h3 class="section-title">Прикачени файлове  <small id="totalFiles"><?= !empty($get_documents['docs']) ? count($get_documents['docs']) : '' ?></small></h3>

                        <div class="table-responsive">
                            <table id="tbl_images" class="table table-hover table-sm mb-0 w-40" cellspacing="0">
                                <thead class="banner-bg">
                                    <tr>
                                        <th class="w-2"></th>
                                        <th class="imgAction w-5"></th>
                                        <th class="imgName">Име на прикаченият файл</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $_orderId = $get_documents['orderId'];

                                    if (!empty($get_documents['docs'])) {
                                        foreach ($get_documents['docs'] as $doc) {
                                            $pathinfo = pathinfo($doc);

                                            $docPatch = $_ENV['app.imageDataDir'] . 'documents/order/site/' . $_orderId . '/' . $pathinfo['filename'] . '.' . $pathinfo['extension'];
                                            ?> 

                                            <tr> 
                                                <td class="align-middle text-center"></td>

                                                <td class="align-middle">
                                                    <a class="btn-outline-success p-1 border-0 fa fa-download" href="<?= $docPatch ?>" title="Свали" download target="_blank"> 
                                                    </a>
                                                </td>

                                                <td class="imgName align-middle"> <?php echo $pathinfo['filename'] . '.' . $pathinfo['extension'] ?></td>
                                            </tr> 
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif ?>

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
                    <table class="table table-hover table-sm mb-0 <?= ISMOBILE ? '' : 'w-40' ?>">
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
                                        <?= $_mapDeliveryMetod[$_deliveryMetod] ?? '' ?>
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

            <?php else: echo $_mapDeliveryMetod[$_deliveryMetod] ?? ''  ?>
            <?php endif ?>
        </div>

        <?php if (!empty($orderData -> belezka)): ?>
            <div class="bg-white border border-color-1 rounded p-3 mb-4">
                <h3 class="section-title">Коментар</h3>
                <div> <?= $orderData -> belezka ?></div>
            </div>
        <?php endif ?>
    </div>
</main>
<!-- ========== END MAIN CONTENT ========== -->
<?php
echo view("{$_ENV['app.theme']}/layouts/sidebar/VIEW__sidebar-account"); // Account Sidebar 
echo view("{$_ENV['app.theme']}/layouts/footer/VIEW__footer");
?>

<?= view("{$_ENV['app.theme']}/layouts/header/VIEW__head") ?>
<?= view("{$_ENV['app.theme']}/layouts/header/VIEW__header") ?>

<?php

$_isDDS    = !empty($settingsJson -> prices_with_dds);
// Етикет за ДДС
$_ddsLabel = $_isDDS ? 'с ДДС' : 'без ДДС';

if (ISMOBILE) {
    echo view($views['ordersMobile']);
    return;
}
?>
<!-- ========== MAIN CONTENT ========== -->
<main class="container min-vh-50" role="main">
    <div class="row mb-4 justify-content-center">
        <h3 class="section-title text-center"><span class="fw-bold">ПОРЪЧКИ</span> - <small>СПИСЪК</small></h3>
    </div>

    <?php

    if (session('message')) {
        $msg = session('message');
        echo "<div class='alert text-danger'>$msg</div>";
    }
    ?>

    <div class="row">
        <div class="col-12">
            <div class="bg-white border border-color-1 rounded p-3">
                <div class="table-responsive">
                    <table id="table-orders" class="table table-hover table-sm mb-0 text-center">
                        <thead class="bg-red">
                            <tr>
                                <th class="col-1 py-0"> 
                                    <input id="orderId" class="js-filter-orderByName my-1 form-control h-2rem" type="search" placeholder="филтър">
                                </th>

                                <th></th>

                                <th class="col-1 py-0 align-middle">
                                    <select class="js-filter-orderByName form-control py-1 h-2rem">
                                        <option value="">филтър</option>
                                        <option value="не приключена">не приключена</option>
                                        <option value="обработва се">обработва се</option>
                                        <option value="авансово">авансово</option>
                                        <option value="завършена">завършена</option>
                                    </select>
                                </th>

                                <th class="col-1 py-0">
                                    <input class="js-filter-orderByName my-1 form-control h-2rem" type="text" placeholder="филтър">
                                </th>

                                <th class="col-2 py-0">
                                    <input class="js-filter-orderByName my-1 form-control h-2rem" type="text" placeholder="филтър">
                                </th>

                                <th colspan="3"></th>


                                <th class="col-1 py-0">
                                    <input  id="date" class="js-filter-orderByName my-1 form-control h-2rem" type="date">
                                </th>

                                <th></th>
                            </tr>

                            <tr class="bg-red">
                                <th scope="col" class="text-white text-center align-middle">Поръчка №</th>
                                <th scope="col" class="text-white text-center align-middle">Обща цена <?= $_ddsLabel ?></th>
                                <th scope="col" class="text-white text-center align-middle">Статус</th>
                                <th scope="col" class="text-white text-center align-middle">Документ №</th>
                                <th scope="col" class="text-white text-center align-middle">Товарителница №</th>
                                <th scope="col" class="text-white text-center align-middle">Документи</th>
                                <th scope="col" class="text-white text-center align-middle">Плащане</th>
                                <th scope="col" class="text-white text-center align-middle">Метод на доставка</th>
                                <th scope="col" class="text-white text-center align-middle">Дата на създаване</th>
                                <th scope="col" class="text-white text-center align-middle">Детайли</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php

                            $_status = array_column($status, null, 'sp_status_id');

                            foreach ($orders as $order):
                                $_bgClass   = $_status[$order['sp_status_id']]['color'] ?? '';
                                $_textColor = $_status[$order['sp_status_id']]['text_color'] ?? 'text-white';
                                ?>

                                <tr>
                                    <td class="orderId"><?= $order['order_id'] ?></td>

                                    <td>
                                        <?php

                                        $_price      = (float) ($order['total_price'] ?? 0);
                                        $_transport  = (float) ($order['transportenRazhod'] ?? 0);
                                        $_grandPrice = empty($_isDDS) ? $_price / $dds : $_price;

                                        echo sprintf('%.2f %s', ($_grandPrice + $_transport), get_valuta());
                                        ?>
                                    </td>

                                    <td class="<?= $_textColor . ' text-center ' . $_bgClass ?> " style="<?= $order['sp_status_id'] == 6 ? 'background:#faf74c' : '' ?>"><?= $_status[$order['sp_status_id']]['status_name'] ?? 'не приключена' ?></td>

                                    <td>
                                        <?php

                                        $_invoiceNo = $order['invoice_no'] ?? '';

                                        if (!empty($_invoiceNo)) {
                                            echo $order['invoice_tip'] . ' №: ' . $_invoiceNo;
                                        }
                                        ?>
                                    </td>

                                    <td><?= $order['tavaritelniza_no'] ?? '' ?></td>

                                    <td>
                                        <?php

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
                                        ?>
                                    </td>

                                    <td><?=

                                        match ($order['payment_method']) {
                                            'E' => 'в брой',
                                            //'B' => 'банков превод',
                                            'D' => 'с карта',
                                            default => ''
                                        }
                                        ?>
                                    </td>

                                    <td><?php

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
                                    </td>
                                    <?php

                                    $timestamp       = strtotime($order['date_added']);
                                    $dateWithoutTime = date('Y-m-d', $timestamp);
                                    ?>
                                    <td class="date" data-date="<?= $dateWithoutTime ?>"><?= $order['date_added'] ?></td>

                                    <td>
                                        <a class="mr-2 text-red" href="<?= route_to('OrderDetail-getById', $order['order_id']) ?>">
                                            <i class="fa fa-eye"></i>
                                        </a>

                                        <a id="createPdf" class="text-red" data-route="<?= route_to('Order-pdf_export', $order['order_id']) ?>" data-name="<?= $order['order_id'] ?>" href="javascript:;">
                                            <i class="fa fa-print"></i>
                                        </a>
                                    </td>                                       
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
<!-- ========== END MAIN CONTENT ========== --> 

<?php

echo view("{$_ENV['app.theme']}/layouts/sidebar/VIEW__sidebar-account");  // Account Sidebar Navigation
echo view("{$_ENV['app.theme']}/layouts/footer/VIEW__footer");
?>

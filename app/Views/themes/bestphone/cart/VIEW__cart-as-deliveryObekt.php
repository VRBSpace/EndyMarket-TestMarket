<?php

function renderDeliveryObject($val, $i) {
    $_default = $val -> default ?? '';
    //$_obj     = $val -> obj ?? '';

    $_ofis            = $val -> ofis ?? '';
    $_grad            = $val -> grad ?? '';
    $_postCode        = $val -> postCode ?? '';
    $_kvartal         = $val -> quarter ?? '';
    $_ulica           = $val -> street ?? '';
    $_ulicaNo         = $val -> street_num ?? '';
    $_blockNo         = $val -> block_no ?? '';
    $_floorNo         = $val -> floor_no ?? '';
    $_apartmentNo     = $val -> apartment_no ?? '';
    $_entranceNo      = $val -> entrance_no ?? '';
    $_shipping_code   = isset($val -> izborKurier) ? $val -> izborKurier : '';
    $_other           = $val -> other ?? '';
    $_showAdresBlock  = in_array($_shipping_code, ['econt_door', 'speedy_door']) ? '' : 'hide';
    $_showOfficeBlock = in_array($_shipping_code, ['econt_office', 'econt_machina', 'speedy_office', 'speedy_machina']) ? '' : 'hide';

    $_logoKurier = empty($_shipping_code) ? null : (in_array($_shipping_code, ['econt_door', 'econt_office', 'econt_machina']) ? 'EcontLogo' : 'SpeedyLogo');

    //$i += 1;
    ob_start();
    ?>

    <input id="deliveryObj" type="hidden" value="<?= $i ?>">

    <div class="mb-2">
        <h5 class="underline-title" style="border-bottom: 1px solid #ee1a19;">
            <span class="fa fa-truck checkout-icon orange-bg" style="width: 30px;text-align: center;color: #fff;line-height: 30px;font-size: 16px;transition: background-color 333ms;">
            </span>

            <span class="title-content">Данни за доставка <img src="<?= $_ENV['app.imageDataDir'] . "eshop/logo/$_logoKurier.png" ?>" onerror="this.onerror=null;this.src='';" alt=" " style="width: 20%;float:right"></span>
        </h5> 
    </div>  

    <small id="js-open-deliveryObekt" class="cursor-pointer d-inline-block text-white px-2 py-1 btn-primary-orange rounded-0" data-route="<?= route_to('Cart-get_klientObekt') ?>">Избор на обект</small>
    <label class="float-right" ><?= $_default == 1 ? 'основен обект' : '' ?></label>

    <table id="js-deliveryTable" class="css-tbl-deliveryObekt w-100 table-hover" >

        <tbody>
            <tr>
                <td>Лице за контакт:</td>
                <td><?= $val -> lice_zaKont ?></td>
            </tr>

            <tr>
                <td>Телефон:</td>
                <td><?= $val -> tel ?></td>
            </tr>

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
                    <div class="input-group-prepend justify-content-between">
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


    <?php
    return ob_get_clean();
}

$_klientObektJson = json_decode($customerData?->klient_obekt_json) ?? [];
$i                = 0;

if (empty($_klientObektJson)) {
    echo '<div id="missingObekt" class="text-danger">Липсва обект на доставка с куриер</div>';
    echo '<a href="' . route_to('Account-index') . '">Линк към профила</a>';
}

foreach ($_klientObektJson as $k => $val) {

    $_default = $val -> default ?? '';
    $i++;

    if (empty($_default) && empty($obekt)) {
        continue;
    } elseif (!empty($obekt) && $obekt == $i) {
        echo renderDeliveryObject($val, $i);
        break;
    } elseif (!empty($_default) && empty($obekt)) {
        echo renderDeliveryObject($val, $i);
        break;
    }
}
?>

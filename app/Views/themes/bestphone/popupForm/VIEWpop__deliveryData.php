<div class="d-flex row">

    <?php
    $_klientObektJson = json_decode($customerData?->klient_obekt_json);
    $i                = 0;

    foreach (($_klientObektJson ?? [0]) as $k => $val) :
        $_default              = $val -> default ?? '';
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

        $i                 += 1;
        ?>

        <div class="col-md-6">
            <table class="table text-left table-hover table-bordered table-sm" id="myTable">
                <thead>
                    <tr>
                        <th colspan="2" class="col-5">
                            <div class="btn-group mb-1" role="group">
                                <button class="js-delivery_izborObekt btn btn-primary-orange rounded-0 py-2 px-3" type="button" data-obekt="<?= $i ?>" data-route="<?= route_to('Cart-set_klientObekt') ?>">Избери обект за доставка</button>
                            </div>

                            <div>
                                <input  type="radio" <?= $_default || $k == 0 ? 'checked' : '' ?>  value="1" disabled>
                                <label>основен обект</label>
                            </div>
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>Лице за контакт</td>

                        <td><?= $val -> lice_zaKont ?></td>
                    </tr>

                    <tr>
                        <td>Телефон</td>

                        <td><?= $val -> tel ?></td>
                    </tr>

                    <tr>
                        <td>Еmail</td>

                        <td><?= $val -> email ?? '' ?></td>
                    </tr>

                    <tr>
                        <td>Метод на доставка</td>
                        <td>
                            <?php
                            $_mapDeliveryMetod = [
                                'firmCar' => 'с фирмен транспорт',
                                'selfCar' => 'със собствен транспорт',
                                'curier'  => 'с куриер'
                            ];

                            $_deliveryMetod = $val -> deliveryMetod ?? '';
                            echo $_mapDeliveryMetod[$_deliveryMetod] ?? '';
                            ?>
                        </td>
                    </tr>

                    <?php if ($_deliveryMetod == 'curier'): ?>
                        <tr>
                            <td>Куриер</td>

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

                        <tr class="toCity notAllowed">
                            <td>Населено място</td>

                            <td><?= $_grad ?></td>
                        </tr>

                        <tr class="toCity notAllowed">
                            <td>Пощенски код</td>

                            <td><?= $_postCode ?></td>
                        </tr>

                        <tr class="toOfice notAllowed <?= $_showOfficeBlock ?>">
                            <td>Офис</td>

                            <td><?= $_ofis ?></td>
                        </tr>

                        <tr class="toAdres <?= $_showAdresBlock ?>">
                            <td>Квартал</td>

                            <td><?= $_kvartal ?></td>
                        </tr>

                        <tr class="toAdres <?= $_showAdresBlock ?>">
                            <td>Улица</td>

                            <td>
                                <div class="input-group-prepend">
                                    <?= $_ulica ?>
                                    <span class="p-1">№</span>
                                    <?= $_ulicaNo ?>
                                </div>
                            </td>
                        </tr>

                        <tr class="toAdres <?= $_showAdresBlock ?>">
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

                        <tr class="toAdres <?= $_showAdresBlock ?>">
                            <td>Друго</td>

                            <td><?= $_other ?></td>
                        </tr>
                    <?php endif ?>
                </tbody>
            </table>
        </div>

    <?php endforeach ?>

</div>

<?php $_klientObektJson = json_decode($customerData?->klient_obekt_json) ?>

<form id="form-deliveryData" class="js-validate table-form d-flex row justify-content-center" data-route="<?= route_to('Account-changeDeliveryData') ?>" data-skip-preloader method="post">

    <?php
    $i                = 0;

    foreach (($_klientObektJson ?? [0]) as $k => $val) :
        $_default       = $val -> default ?? '';
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
        $_classRowHide  = '';

        $_showAdresBlock       = in_array($_shipping_code, ['econt_door', 'speedy_door']) ? '' : 'hide';
        $_showOfficeBlock      = in_array($_shipping_code, ['econt_office', 'econt_machina', 'speedy_office', 'speedy_machina']) ? '' : 'hide';
        $_showBtnLocatorEcont  = in_array($_shipping_code, ['econt_office', 'econt_machina']) ? '' : 'hide';
        $_showBtnLocatorSpeedy = in_array($_shipping_code, ['speedy_office', 'speedy_machina']) ? '' : 'hide';
        $_isAllowed            = in_array($_shipping_code, ['econt_door', 'speedy_door']) ? '' : 'css-pointer-events-none';

        $i += 1;
        // d($val);
        ?>

        <div class="col-<?= ISMOBILE ? '' : '6' ?> clone <?= empty($_klientObektJson) ? 'hide' : '' ?>">
            <div id="tables-container">
                <table class="table table-hover table-bordered table-sm" id="myTable">
                    <thead>
                        <tr>
                            <th class="col-3">
                                <button class="js-deleteTable btn p-2 btn-danger" type="button"><i class="fa fa-times"></i></button> 
                            </th>

                            <th>
                                <div class="col align-self-center">
                                    <input class="js-defaultObekt" 
                                           name="[<?= $i ?>][default]" type="radio" <?= $_default || $k == 0 ? 'checked' : '' ?> 
                                           onclick="$('input[type=radio]:checked').not(this).prop('checked', false)" 
                                           value="1">

                                    <label class="js-defaultObj cursor-pointer">Основен адрес</label>
                                </div> 
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>Лице за контакт <b class="text-danger">*</b></td>

                            <td>
                                <div class="js-form-message js-focus-state input-group">
                                    <input class="form-control h-2rem rounded-left-pill" value="<?= $val -> lice_zaKont ?? '' ?>" 
                                           name="[<?= $i ?>][lice_zaKont]" type="text" 
                                           data-msg="Задълтелно поле"
                                           data-error-class="u-has-error"
                                           data-success-class="u-has-success"
                                           required> 
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>Телефон <b class="text-danger">*</b></td>

                            <td>
                                <div class="js-form-message js-focus-state input-group">
                                    <input class="form-control h-2rem rounded-left-pill" 
                                           name="[<?= $i ?>][tel]" 
                                           value="<?= $val -> tel ?? '' ?>" 
                                           type="number"  
                                           data-msg="Въведете валиден телефонен номер"
                                           data-error-class="u-has-error"
                                           data-success-class="u-has-success"
                                           required> 
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>Еmail</td>

                            <td>
                                <input class="form-control rounded-left-pill h-2rem" name="[<?= $i ?>][email]" type="email"  value="<?= $val -> email ?? '' ?>">  
                            </td>
                        </tr>

                        <tr class="d-none">
                            <td colspan="2">
                                <input type="hidden" name="[<?= $i ?>][deliveryMetod]" value="curier">
                            </td>
                        </tr>

                        <tr class="curier <?= $_classRowHide ?>">
                            <td>Куриер <b class="text-danger">*</b></td>

                            <td> 
                                <div class="js-form-message js-focus-state input-group">
                                    <select class="js-izborKurier validate form-control px-3 py-1 h-2rem w-100 rounded-left-pill" name="[<?= $i ?>][izborKurier]"  
                                            data-msg="Моля изберете куриер"
                                            data-error-class="u-has-error"
                                            data-success-class="u-has-success">

                                        <option value="">--Избор на куриер--</option>
                                        <option data-arg="office" <?= $_shipping_code == 'speedy_machina' ? 'selected' : '' ?> value="speedy_machina">до автомат на Speedy</option>

                                        <option data-arg="office" <?= $_shipping_code == 'speedy_office' ? 'selected' : '' ?> value="speedy_office">до офис на Speedy</option>
                                        <option data-arg="door" <?= $_shipping_code == 'speedy_door' ? 'selected' : '' ?> value="speedy_door">до адрес на Speedy</option>
                                        <option data-arg="office" <?= $_shipping_code == 'econt_machina' ? 'selected' : '' ?> value="econt_machina">до еконтомат на Еконт</option>

                                        <option data-arg="office"  <?= $_shipping_code == 'econt_office' ? 'selected' : '' ?> value="econt_office">до офис на Еконт</option>
                                        <option data-arg="door" <?= $_shipping_code == 'econt_door' ? 'selected' : '' ?> value="econt_door">до адрес на Еконт</option>
                                    </select>

                                </div>

                                <button class="econtLocator btn fa fa-map-marker <?= $_showBtnLocatorEcont ?>" type="button">&nbsp;Еконт локатор</button>

                                <button class="speedyLocator btn fa fa-map-marker <?= $_showBtnLocatorSpeedy ?>" data-route="<?= route_to('Account-get_speeedyOficeData') ?>"  type="button">&nbsp;Спиди локатор</button> 

                            </td>
                        </tr>

                        <tr class="toCity <?= $_classRowHide ?> notAllowed">
                            <td>Населено място <b class="text-danger">*</b></td>

                            <td>
                                <div class="js-form-message js-focus-state input-group">
                                    <input class="js-grad form-control h-2rem rounded-left-pill <?= $_isAllowed ?>" 
                                           name="[<?= $i ?>][grad]" 
                                           value="<?= $_grad ?>"
                                           type="text"  
                                           data-msg="Задълтелно поле"
                                           data-error-class="u-has-error"
                                           data-success-class="u-has-success"
                                           required>

                                    <i class="js-clearCity <?= in_array($_shipping_code, ['econt_door', 'speedy_door'], true) ? '' : 'hide' ?> rounded-0 btn btn-outline-danger p-2 fa fa-times"></i>      
                                </div>

                                <span class="js-autocomplete" data-route="<?= route_to('ApiQurier-econt_action') ?>"></span> 
                            </td>
                        </tr>

                        <tr class="toCity <?= $_classRowHide ?> notAllowed">
                            <td>Пощенски код <b class="text-danger">*</b></td>

                            <td>
                                <div class="js-form-message js-focus-state input-group">
                                    <input 
                                        class="js-postCode form-control rounded-left-pill h-2rem <?= $_isAllowed ?>"
                                        name="[<?= $i ?>][postCode]" 
                                        type="text" 
                                        value="<?= $_postCode ?>"
                                        data-msg="Задълтелно поле"
                                        data-error-class="u-has-error"
                                        data-success-class="u-has-success"
                                        required>
                                </div>
                            </td>
                        </tr>

                        <tr class="toOfice <?= $_classRowHide ?> notAllowed <?= $_showOfficeBlock ?>">
                            <td>Офис
                                <b class="text-danger">*</b>
                            </td>

                            <td>
                                <div class="js-form-message js-focus-state input-group">
                                    <input class="js-izborOfice form-control rounded-left-pill h-2rem <?= $_isAllowed ?>"
                                           name="[<?= $i ?>][ofis]" 
                                           value="<?= $_ofis ?>"
                                           type="text" 
                                           data-msg="Задълтелно поле"
                                           data-error-class="u-has-error"
                                           data-success-class="u-has-success"
                                           required>
                                </div>
                            </td>
                        </tr>

                        <tr class="toAdres <?= $_classRowHide . ' ' . $_showAdresBlock ?>">
                            <td>Квартал</td>

                            <td>
                                <input name="[<?= $i ?>][quarter]" type="text" class="form-control h-2rem rounded-left-pill" value="<?= $_kvartal ?>">
                            </td>
                        </tr>

                        <tr class="toAdres <?= $_classRowHide . ' ' . $_showAdresBlock ?>">
                            <td>Улица <b class="text-danger">*</b></td>

                            <td>
                                <div class="js-form-message js-focus-state input-group-prepend">
                                    <input name="[<?= $i ?>][street]" type="text" class="form-control h-2rem" value="<?= $_ulica ?>"
                                           data-msg="Задълтелно поле"
                                           data-error-class="u-has-error"
                                           data-success-class="u-has-success"
                                           required>

                                    <span class="p-1">№</span>

                                    <input id="ulicaNo" class="w-20 form-control h-2rem rounded-left-pill" name="[<?= $i ?>][street_num]" type="text" value="<?= $_ulicaNo ?>">
                                </div>
                            </td>
                        </tr>

                        <tr class="toAdres <?= $_classRowHide . ' ' . $_showAdresBlock ?>">
                            <td>Блок</td>

                            <td>
                                <div class="input-group-prepend justify-content-between">
                                    <input name="[<?= $i ?>][block_no]" type="text" class="w-20 form-control h-2rem" value="<?= $_blockNo ?>">

                                    <span>вх.</span>
                                    <input id="entranceNo" class="w-20 form-control h-2rem" name="[<?= $i ?>][entrance_no]" type="text" value="<?= $_entranceNo ?>">
                                    <span>ет.</span>
                                    <input id="floorNo" class="w-20 form-control h-2rem" name="[<?= $i ?>][floor_no]" type="text" value="<?= $_floorNo ?>">
                                    <span>ап.</span>
                                    <input id="apartmentNo" class="w-20 form-control h-2rem rounded-left-pill" name="[<?= $i ?>][apartment_no]" type="text" value="<?= $_apartmentNo ?>">
                                </div>  
                            </td>
                        </tr>

                        <tr class="toAdres <?= $_classRowHide . ' ' . $_showAdresBlock ?>">
                            <td>Друго</td>

                            <td>
                                <input name="[<?= $i ?>][other]" type="text" class="form-control h-2rem rounded-left-pill" value="<?= $_other ?>">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endforeach ?>

    <button class="js-duplicateTable rounded-0 btn btn-secondary-white-orange m-auto p-2" type="button">
        <i class="d-flex justify-content-center fa fa-plus fa-2x"></i>Добави адрес за доставка</button>
</form>

<button class="d-block btn btn-primary-orange rounded-0 w-100 mt-3 mx-auto" form="form-deliveryData" type="submit">Запис</button>

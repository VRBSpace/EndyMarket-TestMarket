<!-- доставчик  modal -->
<div class="modal fade" id="modal_klient" tabindex="-1"  data-backdrop="static" data-keyboard="false" role="dialog">

    <?php $_klientJson = isset($list -> klientData_json) ? json_decode($list -> klientData_json) : null ?>

    <?php $hide        = '' ?>
    <div class="modal-dialog mx-auto m-0" role="document" style="max-width: 40vw;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?= empty($list -> klient_id) ? lang('popup/LANGpop__klient.title') : lang('popup/LANGpop__klient.title2') ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <nav class="col bg-warning">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a id="link-info" class="nav-link active" href="#tab-info" data-toggle="tab" role="tab">Основни данни</a></li>
                    <li class="nav-item">
                        <a id="link-komentar" class="nav-link" href="#tab-komentar" data-toggle="tab" role="tab">Лице за контакти</a>
                    </li>

                    <li class="nav-item">
                        <a id="link-obekt" class="nav-link" href="#tab-obekt" data-toggle="tab" role="tab">Данни за доставка</a>
                    </li>
                </ul>
            </nav>

            <div class="modal-body overflow-auto" style="max-height: 75vh">
                <form id="form-klient" name="form-klient" action="<?= route_to('POPup_klient-save') ?>" method="post">

                    <div class="container-fluid">
                        <div class="row">
                            <div class="col">

                                <div class="tab-content" id="nav-tabContent">

                                    <!-- таб осн данни -->
                                    <div id="tab-info" class="tab-pane fade show active"  role="tabpanel">
                                        <input name="form-klient[klient_id]" type="hidden" value="<?= $list?->klient_id ?>"> 

                                        <section class="d-flex mb-1">
                                            <label  class="col-3"><?= lang('popup/LANGpop__klient.label.klient_name') ?><b class="text-red">*</b></label>

                                            <div class="col">
                                                <input class="validate" name="form-klient[klient_name]" type="text" value="<?= $list?->klient_name ?>"> 
                                            </div>
                                        </section>

                                        <section class="d-flex mb-1">
                                            <label  class="col-sm-3"><?= lang('popup/LANGpop__klient.label.postCode') ?><b class="text-red">*</b></label>

                                            <div class="col">
                                                <input class="validate" name="form-klient[klient_postCode]" type="text" value="<?= $list?->klient_postCode ?>">
                                            </div>
                                        </section>

                                        <section class="d-flex mb-1">
                                            <label class="col-3"><?= lang('popup/LANGpop__klient.label.grad') ?><b class="text-red">*</b></label>

                                            <div class="col">
                                                <input class="validate" name="form-klient[klient_grad]" type="text" value="<?= $list?->klient_grad ?>">
                                            </div>
                                        </section>

                                        <section class="d-flex mb-1">
                                            <label class="col-3"><?= lang('popup/LANGpop__klient.label.adres') ?><b class="text-red">*</b></label>

                                            <div class="col">
                                                <textarea class="validate" name="form-klient[klient_adres]" rows="1"><?= $list?->klient_adres ?></textarea>
                                            </div>
                                        </section>

                                        <section class="d-flex mb-1">
                                            <label  class="col-3"><?= lang('popup/LANGpop__klient.label.tel') ?><b class="text-red">*</b></label>

                                            <div class="col">
                                                <input class="validate" name="form-klient[klient_tel]" type="text" value="<?= $list?->klient_tel ?>"> 
                                            </div>
                                        </section>

                                        <section class="d-flex flex-row">
                                            <label  class="col-3"><?= lang('popup/LANGpop__klient.label.email') ?></label>

                                            <div class="col">
                                                <input type="text" name="form-klient[email]" value="<?= $list?->email ?>">
                                            </div>
                                        </section>

                                        <section class="d-flex flex-row">
                                            <label class="col-3">Сайт</label>
                                            <div class="col">
                                                <input class="" name="form-klient[website]" type="text" value="<?= $list?->website ?>">
                                            </div>  
                                        </section>

                                        <section class="d-flex mb-1">
                                            <label class="col-3"><?= lang('popup/LANGpop__klient.label.mol') ?><b class="text-red">*</b></label>

                                            <div class="col">
                                                <input class="validate" name="form-klient[klient_mol]" type="text" value="<?= $list?->klient_mol ?>">
                                            </div>
                                        </section>

                                        <section class="d-flex mb-1">
                                            <label class="col-sm-3"><?= lang('popup/LANGpop__klient.label.bulstat') ?><b class="text-red">*</b></label>

                                            <div class="col">
                                                <input type="text" name="form-klient[bulstat]" value="<?= $list?->bulstat ?>"> 
                                            </div>
                                        </section>
                                    </div>

                                    <!-- таб лице за контакти -->
                                    <div id="tab-komentar" class="tab-pane fade" role="tabpanel"> 
                                        <p><label>Управител</label></p>
                                        <div class="row">
                                            <div class="col">
                                                <div class="d-flex">
                                                    <label class="col-3">Име <b class="text-red">*</b></label>
                                                    <p class="col mb-0">
                                                        <input type="text" name="form-json[direktor][name]" value="<?= $_klientJson?->direktor -> name ?>">
                                                    </p>  
                                                </div> 

                                                <div class="d-flex">
                                                    <label  class="col-3">Телефон <b class="text-red  <?= $hide ?>">*</b></label>
                                                    <p class="col mb-0">
                                                        <input type="text" name="form-json[direktor][tel]" value="<?= $_klientJson?->direktor -> tel ?>">
                                                    </p>  
                                                </div>

                                                <div class="d-flex">
                                                    <label  class="col-3">E-mail</label>
                                                    <p class="col mb-0">
                                                        <input type="text" name="form-json[direktor][email]" value="<?= $_klientJson?->direktor -> email ?>">
                                                    </p>  
                                                </div>

                                                <div class="d-flex">
                                                    <label  class="col-3">Skype</label>
                                                    <p class="col mb-0">
                                                        <input type="text" name="form-json[direktor][skype]" value="<?= $_klientJson?->direktor -> skype ?>">
                                                    </p>  
                                                </div>

                                                <div class="d-flex">
                                                    <label  class="col-3">Viber</label>
                                                    <p class="col mb-0">
                                                        <input type="text" name="form-json[direktor][viber]" value="<?= $_klientJson?->direktor -> viber ?>">
                                                    </p>  
                                                </div>

                                                <div class="d-flex">
                                                    <label  class="col-3">Messanger</label>
                                                    <p class="col mb-0">
                                                        <input type="text" name="form-json[direktor][messanger]" value="<?= $_klientJson?->direktor -> messanger ?>">
                                                    </p>  
                                                </div>

                                                <div class="d-flex">
                                                    <label  class="col-3">Други</label>
                                                    <p class="col mb-0">
                                                        <input type="text" name="form-json[direktor][else]" value="<?= $_klientJson?->direktor -> else ?>">
                                                    </p>  
                                                </div>

                                                <div class="d-flex">
                                                    <label  class="col-3">Коментар</label>
                                                    <p class="col mb-1">
                                                        <textarea class="w-100" name="form-json[direktor][koment]" rows="1"><?= $_klientJson?->direktor -> koment ?></textarea>
                                                    </p>  
                                                </div>
                                            </div>
                                        </div> 
                                    </div>

                                    <!-- таб данни за доставка -->
                                    <div id="tab-obekt" class="tab-pane fade" role="tabpanel"> 
                                        <div class="row" style="margin-top: 20px;padding: 0;">
                                            <?php foreach (($_klientJson -> obekt ?? [0]) as $k => $val) { ?>
                                                <div class="col-4">
                                                    <div class="d-flex">
                                                        <label class="col">Обект</label> 
                                                        <button class="clone-block btn mb-2 p-1" type="button" style="float:right">>></button> 
                                                        <button class="remove-block btn mb-2 p-1" type="button" style="float:right;color:red;" >x</button> 
                                                    </div>


                                                    <div class="d-flex">
                                                        <label  class="col-4">Лице за контакт</label>
                                                        <p class="col mb-0">
                                                            <input class="" name="form-json[obekt][<?= $k ?>][lice_zaKont]" type="text" value="<?= $val -> lice_zaKont ?? '' ?>">
                                                        </p>  
                                                    </div>

                                                    <div class="d-flex">
                                                        <label  class="col-4">Телефон</label>
                                                        <p class="col mb-0">
                                                            <input class="" name="form-json[obekt][<?= $k ?>][tel]" type="text" value="<?= $val -> tel ?? '' ?>">
                                                        </p>  
                                                    </div>

                                                    <div class="d-flex">
                                                        <label  class="col-4">Еmail</label>
                                                        <p class="col mb-0">
                                                            <input class="" name="form-json[obekt][<?= $k ?>][email]" type="text" value="<?= $val -> email ?? '' ?>">
                                                        </p>  
                                                    </div>

                                                    <div class="d-flex">
                                                        <label  class="col-4">Адрес на обекта</label>
                                                        <p class="col mb-1">
                                                            <textarea class="w-100" name="form-json[obekt][<?= $k ?>][adrObekt]" rows="1"><?= $val -> adrObekt ?? '' ?></textarea>
                                                        </p>  
                                                    </div>

                                                    <label class="col-4 mb-2 text-decoration-underline" style="text-decoration: underline;">Адрес за доставка</label>

                                                    <div class="d-flex">
                                                        <label  class="col-4">Метод на доставка</label>
                                                        <p class="col mb-1">
                                                            <select id="izborKurier" class="validate w-100" name="order[izborKurier]">
                                                                <option value="">--Избор на куриер--</option>
                                                                <option data-arg="office" value="speedy_machina">до автомат на Speedy</option>

                                                                <option data-arg="office" value="speedy_office">до офис на Speedy</option>

                                                                <option data-arg="door" value="speedy_door">до адрес на Speedy</option>

                                                                <option data-arg="office" value="econt_machina">до еконтомат на Еконт</option>

                                                                <option data-arg="office" value="econt_office">до офис на Еконт</option>

                                                                <option data-arg="door" value="econt_door">до адрес на Еконт</option>
                                                            </select>
                                                        </p>  
                                                    </div>

                                                    <div id="toOfice" class="hide">
                                                        <label class="mt-1">
                                                            <i class="fa fa-address-card"></i>&nbsp;Офис
                                                            <span class="text-danger" style="font-size: 20px">*</span>
                                                        </label>

                                                        <input id="pickupOfficeId" class="w-50 border-0 bg-transparent pointer-events-none"  name="tk_order[office_id]" type="text" value="">

                                                        <select id="izborOfice" class="w-100" name="tk_oficeAdres">
                                                            <option value="" selected="selected"> --- Моля, изберете --- </option>
                                                            <?php
                                                            // foreach ($allOfices as $row) {
                                                            //     $selected   = '';
                                                            //     $_fullAdres = $row['office_id'] . ' ' . $row['name'];

                                                            //     if ($row['office_id'] == $curier -> office_id) {
                                                            //         $_selectedAdres = $_fullAdres;
                                                            //         $selected       = 'selected';
                                                            //     }

                                                            //     echo '<option data-is-apt="' . $curier -> is_machine . '" value="' . ($row['office_id']) . '"' . $selected . '>' . $_fullAdres . '</option>';
                                                            // }
                                                            ?>
                                                        </select>

                                                        <input id="tk_oficeAdres" name="tk_oficeAdres" type="hidden" value="<?= $_selectedAdres ?? '' ?>">
                                                    </div>

                                                    <div id="toAdres" >
                                                        <div class="my-1 d-flex">
                                                            <label class="col-4">Квартал</label>

                                                            <p class="col mb-0">
                                                                <input id="kvartal" name="tk_order[quarter]" type="text" value="<?= $_kvartal ?? '' ?>">
                                                            </p>  
                                                        </div>

                                                        <div class="my-1 d-flex">
                                                            <label  class="col-4">Улица</label>

                                                            <div class=" col">
                                                                <div class="input-group-prepend">
                                                                    <input id="ulica" class="mr-1" name="tk_order[street]" type="text" value="<?= $_ulica ?? '' ?>">

                                                                    <span>№&nbsp;</span>

                                                                    <input id="ulicaNo" class="w-20" name="tk_order[street_num]" type="text" value="<?= $_ulicaNo ?? '' ?>">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div id="blokContainer" class="my-1 d-flex">
                                                            <label class="col-4">Блок</label>

                                                            <div class="col">
                                                                <div class="input-group-prepend justify-content-between">
                                                                    <input id="blockNo" class="w-15" name="tk_order[block_no]" type="text" value="<?= $_blockNo ?? '' ?>">
                                                                    <span>вх.</span>
                                                                    <input id="entranceNo" class="w-10" name="tk_order[entrance_no]" type="text" value="<?= $_entranceNo ?? '' ?>">

                                                                    <span>ет.</span>
                                                                    <input id="floorNo" class="w-10" name="tk_order[floor_no]" type="text" value="<?= $_floorNo ?? '' ?>">

                                                                    <span>ап.</span>
                                                                    <input id="apartmentNo" class="w-10" name="tk_order[apartment_no]" type="text" value="<?= $_apartmentNo ?? '' ?>">
                                                                </div>  
                                                            </div>
                                                        </div>

                                                        <div class="my-1 d-flex">
                                                            <label  class="col-4">Друго</label>

                                                            <p class="col mb-0">
                                                                <input id="other" name="tk_order[other]" type="text" value="<?= $_other ?? '' ?>">
                                                            </p>  
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div> 
                                    </div>   

                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <b class="col text-red"><?= lang('LANG__global.requiredFields') ?></b>
                <div class="btn-group  btn-group"> 
                    <button id="save" class="btn btn-primary" type="submit"  form="form-klient"><?= lang('LANG__global.btn.save') ?></button>
                    <button class="btn btn-default" type="button"  data-dismiss="modal"><?= lang('LANG__global.btn.cansel') ?></button> 
                </div>
            </div>


        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->

    <?php
    if (isset($addJS) && is_array($addJS)) {

        function auto($file = '') {
            if (!file_exists($file))
                return base_url() . $file;

            $mtime = filemtime($file);
            return base_url() . $file . '?' . $mtime;
        }
        ?>
        <?php foreach ($addJS as $js) { ?>
            <script src="<?= auto('assets/' . $js . '.js'); ?>"></script>
        <?php } ?>
    <?php } ?>  
</div><!-- /.modal -->

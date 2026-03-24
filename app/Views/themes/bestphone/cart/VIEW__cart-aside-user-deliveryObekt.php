<h5 style="border-bottom: 1px solid #ee1a19;">
    <span class="fa fa-truck orange-bg" style="width: 30px;text-align: center;color: #fff;line-height: 30px;font-size: 16px;transition: background-color 333ms;">
    </span>

    <span class="title-content">Метод за доставка</span>
</h5> 
<?php
// Дефинираме масив с методите за доставка
$_speedyArr = [
    [
        'value' => 'speedy_office',
        'label' => 'Speedy - офис',
        'logo'  => 'SpeedyLogo.png',
        'route' => route_to('ApiQurier-speedy_action'),
    ],
    [
        'value' => 'speedy_door',
        'label' => 'Speedy - адрес',
        'logo'  => 'SpeedyLogo.png',
        'route' => route_to('ApiQurier-speedy_action'),
    ],
];

$_econtArr = [
    [
        'value' => 'econt_office',
        'label' => 'Econt - офис',
        'logo'  => 'EcontLogo.png',
        'route' => route_to('ApiQurier-econt_action'),
    ],
    [
        'value' => 'econt_door',
        'label' => 'Econt - адрес',
        'logo'  => 'EcontLogo.png',
        'route' => route_to('ApiQurier-econt_action'),
    ]
];

$_deliveryMethods = array_merge($_econtArr, $customConfig -> isVisible['speedyRadioBtn'] ? $_speedyArr : []);
?>
<ul class="js-form-message list-group">
    <?php foreach ($_deliveryMethods as $index => $method): ?>
        <li class="list-group-item py-0 my-1 border border-primary rounded-0">
            <label class="d-flex justify-content-between align-items-center">
                <input class="js-deliveryMethod" name="delivery_json[izborKurier]" data-route="<?= $method['route'] ?>" type="radio" value="<?= $method['value'] ?>"
                       <?= $index === 0 ? 'checked' : '' ?>
                       required  
                       data-msg="изберете куриер за доставка"
                       data-error-class="u-has-error"
                       data-success-class="u-has-success">
                <span><?= $method['label'] ?></span>
                <span>
                    <img class="w-75" src="<?= $_ENV['app.imageDataDir'] . "eshop/logo/" . $method['logo'] ?>" alt="...">
                </span>
            </label>
        </li>
    <?php endforeach; ?>
</ul>

<br>

<h5 style="border-bottom: 1px solid #ee1a19;">
    <span class="fa fa-map-marker orange-bg" style="width: 30px;text-align: center;color: #fff;line-height: 30px;font-size: 16px;transition: background-color 333ms;">
    </span>

    <span class="title-content">Данни за доставка</span>
</h5> 

<div class="js-form-message">
    <label>Населено място <b class="text-danger">*</b></label>
    <input id="js-grad" class="w-100" name="delivery_json[grad]" type="text" 
           data-msg="Задълтелно поле за населено място"
           data-error-class="u-has-error"
           data-success-class="u-has-success"
           required>

    <span class="autocomplete">
</div>

<div>
    <input id="js-postCode" name="delivery_json[postCode]" type="hidden">
</div>

<!-- до офис -->
<div id="toOfis" class="hide">

    <br>

    <div class="js-form-message">
        <label>Офис <b class="text-danger">*</b></label>
        <textarea id="js-ofis" class="w-100" name="delivery_json[ofis]"  
                  placeholder="може да филтрирате по офис"
                  data-msg="Задълтелно поле за офис"
                  data-error-class="u-has-error"
                  data-success-class="u-has-success"
                  required></textarea>

        <span class="autocomplete">
    </div>
</div>
<!-- край до офис -->

<!-- до адрес -->
<div id="toDoor" class="row hide">

    <div class="col-md-6 px-0 pr-1">
        <label>Квартал</label>
        <input class="w-100" name="delivery_json[quarter]" type="text">
    </div>

    <div class="col-md-6 px-0 pl-1">
        <label>Улица</label>
        <input class="w-100" name="delivery_json[street]" type="text">
    </div>

    <div class="js-form-message col-md-6 px-0 pr-1">
        <label>Номер/Блок <b class="text-danger">*</b></label>
        <input class="w-100" name="delivery_json[street_num]" type="text" 
               data-msg="Задълтелно поле за номер"
               data-error-class="u-has-error"
               data-success-class="u-has-success"
               required>
    </div>

    <div class="col px-0 pl-1">
        <label>Друго</label>
        <input class="w-100" name="delivery_json[other]" type="text">
    </div>
</div>
<!-- край до адрес -->

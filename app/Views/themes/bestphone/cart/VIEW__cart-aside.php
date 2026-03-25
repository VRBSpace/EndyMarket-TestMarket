<?php
if (!ISMOBILE) {
    echo '<h4 class="title section-title fw-bold">Детайли на заявката</h4>';
}
?>

<br>

<div class="mb-2">
    <h5 class="underline-title" style="border-bottom: 1px solid #ee1a19;">
        <span class="fa fa-taxi checkout-icon orange-bg" style="width: 30px;text-align: center;color: #fff;line-height: 30px;font-size: 16px;transition: background-color 333ms;">
        </span>

        <span class="title-content">Метод на доставка</span>
    </h5> 

    <?php
    $_klientObektJson = json_decode($customerData -> klient_obekt_json ?? '[]', true);
    $_hasDeliveryObjects = !empty($_klientObektJson);
    $_selected = 'curier';
    $_defaultDeliverySource = $_hasDeliveryObjects ? 'object' : 'custom';
    ?>

    <select id="js-deliveryMethod" class="form-control px-3 py-1 h-2rem w-100">
        <option value="firmCar">с транспорт на <?= $customConfig -> comany ?? '' ?></option>
        <option value="curier" selected>с куриер</option>
    </select>

    <small>Ако желаете да не ползвате speedy или еконт за доставката, изберете от падащото меню желаното</small>

    <?php if(empty($_klientObektJson)): ?>
       <br><br>
       <small class="text-danger">Липсва запазен адрес за доставка. Може да попълните „Друг адрес“ или да добавите адрес от меню „Моят профил“.
       </small>
     <?php endif ?>
</div> 

<section>
    <div id="js-deliveryObekt-block" style="<?= $_selected != 'curier' ? 'display:none' : '' ?>">
        <div class="mb-2 mt-2">
            <div class="custom-control custom-radio custom-control-inline">
                <input id="delivery-source-object" class="custom-control-input js-delivery-source" type="radio" name="delivery_source" value="object" <?= $_defaultDeliverySource == 'object' ? 'checked' : '' ?> <?= $_hasDeliveryObjects ? '' : 'disabled' ?>>
                <label class="custom-control-label" for="delivery-source-object">Адрес от профила</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
                <input id="delivery-source-custom" class="custom-control-input js-delivery-source" type="radio" name="delivery_source" value="custom" <?= $_defaultDeliverySource == 'custom' ? 'checked' : '' ?>>
                <label class="custom-control-label" for="delivery-source-custom">Друг адрес</label>
            </div>
        </div>

        <div id="js-deliveryObekt-existing" class="<?= $_defaultDeliverySource == 'object' ? '' : 'hide' ?>">
            <?= view($views['cart-as-deliveryObekt']) ?>
        </div>

        <div id="js-deliveryObekt-custom" class="<?= $_defaultDeliverySource == 'custom' ? '' : 'hide' ?>">
            <?= view($views['cart-aside-user-deliveryObekt']) ?>
        </div>
    </div>
</section>

<br>

<div class="mb-2">
    <h5 class="checkout-step-title" style="border-bottom: 1px solid #ee1a19;">
        <span class="fa fa-dollar-sign checkout-icon orange-bg" style="width: 30px;text-align: center;color: #fff;line-height: 30px;font-size: 16px;transition: background-color 333ms;">
        </span>

        <span class="title-content">Плащане</span>
    </h5>

    <input id="cash" class="js-payment_method mb-2" type="radio" name="name" value="N" checked>
    <label for="cash">В брой</label>
    <br>
    <input id="card" class="js-payment_method mb-2" type="radio" name="name" value="C">
    <label for="card">Плащане с карта</label>
</div>

<div class="mb-2">
    <h5 class="checkout-step-title" style="border-bottom: 1px solid #ee1a19;">
        <span class="fa fa-comment checkout-icon orange-bg" style="width: 30px;text-align: center;color: #fff;line-height: 30px;font-size: 16px;transition: background-color 333ms;">
        </span>

        <span class="title-content">Коментар</span>
    </h5>

    <textarea id="js-belezka" class="w-100"></textarea>
</div>

<div class="mb-2">
    При разминаване на договорените цени заявката ще бъде коригирана при реализирането и изпълнени от търговец или офис
</div>

<div class="text-center">
    <?php
    $_minPrice = $settings_portal['order']['order']['minPrice'] ?? 0;

    // ако поръчката е на стойност по < от цена 50лв
    echo "<input type='hidden' id='js-settingMinPrice' value='$_minPrice'>";

    $_classHide_textMinPrice = 'hide';
    if (!empty($cartSession) && ($cartSession['cart_all_products_info']['price']) * 1.2 < $_minPrice) {
        $_classHide_btnCheckout  = 'hide';
        $_classHide_textMinPrice = '';
    }
    ?>

    <span id="js-text_minPrice" class="<?= $_classHide_textMinPrice ?> text-danger">Минималната сума за поръчка е <?= $_minPrice ?> <?= get_valuta() ?></span>

    <?php if (!empty($cartSessionProductsList)): ?>
        <button type="button" class="js-checkout-btn <?= $_classHide_btnCheckout ?? '' ?> btn btn-primary-orange rounded-0 ml-md-2 px-5 px-md-4 px-lg-5 w-100 w-md-auto text-white" data-route="<?= route_to('Checkout-checkout') ?>">Завършване на поръчката</button>
    <?php endif ?>

    <?php if (empty($sessionAccountId)): ?>
        <a class=" btn btn-primary-dark-w ml-md-2 px-5 px-md-4 px-lg-5 w-100 w-md-auto" href="<?= site_url('/account') ?>" role="button">Влез в системата</a>
    <?php endif ?>
</div>

<br>

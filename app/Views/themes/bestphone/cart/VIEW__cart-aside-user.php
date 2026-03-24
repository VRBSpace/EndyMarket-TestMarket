<?php
if (!ISMOBILE) {
    echo '<h4 class="title section-title fw-bold">Детайли на заявката</h4>';
}
?>

<br>

<form id="form-chekout" class="js-validate table-form" action="post">
    <div>
        <h5 style="border-bottom: 1px solid #ee1a19;">
            <span class="fa fa-user orange-bg" style="width: 30px;text-align: center;color: #fff;line-height: 30px;font-size: 16px;transition: background-color 333ms;">
            </span>

            <span class="title-content">Лични данни</span>
        </h5> 

        <div class="js-form-message">
            <label class="">Име и Фамилия <b class="text-danger">*</b></label>
            <input class="w-100" name="delivery_json[lice_zaKont]" type="text" 
                   data-msg="Задълтелно поле за име"
                   data-error-class="u-has-error"
                   data-success-class="u-has-success"
                   required>
        </div>

        <div class="row">
            <div class="js-form-message col-md-6 p-0 pr-2">
                <label>Телефон <b class="text-danger">*</b></label>
                <input class="w-100" name="tel" type="tel"  
                       data-error-class="u-has-error"
                       data-success-class="u-has-success"
                       required>
            </div>

            <div class="js-form-message col-md-6 p-0 pl-2">
                <label class="">Имейл <b class="text-danger">*</b></label>
                <input class="w-100" name="delivery_json[email]" type="email" 
                       data-msg="email адресът не е валиден"
                       data-error-class="u-has-error"
                       data-success-class="u-has-success"
                       required>
            </div>
        </div>
    </div> 

    <br>

    <section>
        <?= view($views['cart-aside-user-deliveryObekt']) ?>
    </section>

    <br>

    <div class="mb-2">
        <h5 style="border-bottom: 1px solid #ee1a19;">
            <span class="fa fa-dollar-sign orange-bg" style="width: 30px;text-align: center;color: #fff;line-height: 30px;font-size: 16px;transition: background-color 333ms;">
            </span>

            <span class="title-content">Плащане</span>
        </h5>

        <input id="cash" class="js-payment_method mb-2" type="radio" name="payment_method" checked value="N">
        <label for="cash">Наложен платеж</label>
    </div>

    <div class="mb-2">
        <h5 style="border-bottom: 1px solid #ee1a19;">
            <span class="fa fa-comment orange-bg" style="width: 30px;text-align: center;color: #fff;line-height: 30px;font-size: 16px;transition: background-color 333ms;">
            </span>

            <span class="title-content">Коментар</span>
        </h5>

        <textarea id="js-belezka" name="belezka" class="w-100"></textarea>
    </div>

    <div> 
        <input id="is_agree" name="is_agree" type="checkbox" value="1">
        <a href="<?= route_to('Pages-uslovia') ?>" target="_blank" class="text-red">Съгласяване с общите правила и условия</a>
    </div>

    <div class="text-center mt-3">
        <?php if (!empty($cartSessionProductsList)): ?>
            <button id="js-checkout-klient-btn" type="button" class="btn btn-primary-orange ml-md-2 px-5 px-md-4 px-lg-5 w-100 w-md-auto text-white rounded-0" data-route="<?= route_to('Checkout-klient') ?>">Завършване на поръчката</button>
        <?php endif ?>
    </div>
</form>

<br>

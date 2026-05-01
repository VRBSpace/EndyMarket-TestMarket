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

<div id="js-deliveryEstimate" class="alert alert-warning mt-3 mb-2 hide" data-route="<?= site_url('/ApiQurier/calculateDostavka') ?>">
    <strong>Ориентировъчна цена за доставка:</strong>
    <span id="js-deliveryEstimatePrice"></span>
    <div class="small mt-1">Цената е информативна и се заплаща при получаване на пратката. Не се включва в общата сума на поръчката.</div>
</div>

<br>

<div class="mb-2">
    <h5 class="checkout-step-title" style="border-bottom: 1px solid #ee1a19;">
        <span class="fa fa-dollar-sign checkout-icon orange-bg" style="width: 30px;text-align: center;color: #fff;line-height: 30px;font-size: 16px;transition: background-color 333ms;">
        </span>
 
        <span class="title-content">Плащане</span>
    </h5>
 
    <input id="cash" class="js-payment_method mb-2" type="radio" name="name" value="E" checked>
    <label for="cash">В брой</label>
    <br>
    <input id="card" class="js-payment_method mb-2" type="radio" name="name" value="D">
    <label for="card">Плащане с карта (онлайн)</label>
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

<!-- Общи условия -->
<div class="mb-2">
    <div> 
        <input id="is_agree" name="is_agree" type="checkbox" value="1" autocomplete="off">
        <span>
            Съгласяване с
            <a href="<?= route_to('Pages-uslovia') ?>" target="_blank" rel="noopener noreferrer" class="text-red">общите правила и условия</a>
            и
            <a href="<?= route_to('Pages-privacyData') ?>" target="_blank" rel="noopener noreferrer" class="text-red">политика за поверителност</a>
        </span>
    </div>
</div>


<div class="text-center">
    <?php
    $_minPrice = $settings_portal['order']['order']['minPrice'] ?? 0;

    // ако поръчката е на стойност по < от цена 50лв
    echo "<input type='hidden' id='js-settingMinPrice' value='$_minPrice'>";

    $_classHide_textMinPrice = 'hide';
    if (!empty($cartSession) && ($cartSession['cart_all_products_info']['price'] ?? 0) < $_minPrice) {
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// ============================================
// ПОСТОЯННА ВАЛИДАЦИЯ ЗА ВСИЧКИ ВИДИМИ ПОЛЕТА
// ============================================
$(document).ready(function() {
    setTimeout(function() {
        $('.js-checkout-btn').off('click').on('click', function(e) {
            
            let paymentMethod = $('input[name="name"]:checked').val();
            
            // ========== ОБЩА ВАЛИДАЦИЯ (работи и за карта, и за наложен) ==========
            let isValid = true;
            let errorMessages = [];
            
            // 1. Проверка на Населено място (ако е видимо)
            
            let agreeCheckbox = $('#is_agree');
            if (agreeCheckbox.length > 0 && !agreeCheckbox.is(':checked')) {
                isValid = false;
                errorMessages.push('Моля, съгласете се с общите правила и условия и политиката за поверителност');
            }
            
            let city = $('#js-grad');
            if (city.is(':visible') && (!city.val() || !city.val().trim())) {
                isValid = false;
                errorMessages.push('Моля, въведете населено място');
                city.addClass('is-invalid');
            } else if (city.is(':visible')) {
                city.removeClass('is-invalid');
            }
            
            // 2. Проверка на Офис (само ако е видим)
            let office = $('#js-ofis');
            if (office.is(':visible') && (!office.val() || !office.val().trim())) {
                isValid = false;
                errorMessages.push('Моля, изберете офис за доставка');
                office.addClass('is-invalid');
            } else if (office.is(':visible')) {
                office.removeClass('is-invalid');
            }
            
            // 3. Проверка на Номер/Блок (само ако е видим - при доставка до адрес)
            let streetNum = $('input[name="delivery_json[street_num]"]');
            if (streetNum.is(':visible') && (!streetNum.val() || !streetNum.val().trim())) {
                isValid = false;
                errorMessages.push('Моля, въведете номер/блок за адреса');
                streetNum.addClass('is-invalid');
            } else if (streetNum.is(':visible')) {
                streetNum.removeClass('is-invalid');
            }
            
            // 4. Ако има грешки - спираме
            if (!isValid) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Внимание',
                    html: errorMessages.join('<br>'),
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#ee1a19'
                });
                return false;
            }

            // ========== АКО Е ПЛАЩАНЕ С КАРТА (D) ==========
            if (paymentMethod === 'D') {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                
                let btn = $(this);
                btn.prop('disabled', true);
                
                // СЪЗДАВАМЕ ФОРМА ЗА ИЗПРАЩАНЕ
                let form = $('<form>', {
                    'method': 'POST',
                    'action': '/cart/checkout_card'
                });
                
                // Събираме всички данни
                $('[name^="delivery_json"]').each(function() {
                    let $el = $(this);
                    let name = $el.attr('name');
                    if ($el.attr('type') === 'radio') {
                        if ($el.is(':checked')) {
                            $('<input>').attr({ type: 'hidden', name: name, value: $el.val() }).appendTo(form);
                        }
                    } else {
                        let value = $el.val();
                        if (value && value.trim() !== '') {
                            $('<input>').attr({ type: 'hidden', name: name, value: value }).appendTo(form);
                        }
                    }
                });
                
                // Добавяме коментар
                $('<input>').attr({ type: 'hidden', name: 'belezka', value: $('#js-belezka').val() || '' }).appendTo(form);
                
                // Добавяме метода на плащане
                $('<input>').attr({ type: 'hidden', name: 'name', value: 'D' }).appendTo(form);
                
                // Добавяме телефона (ако има)
                if ($('[name="tel"]').length) {
                    $('<input>').attr({ type: 'hidden', name: 'tel', value: $('[name="tel"]').val() }).appendTo(form);
                }
                
                console.log('Картово плащане - изпращам към:', form.attr('action'));
                
                // ДИРЕКТЕН SUBMIT КЪМ БАНКАТА
                Swal.fire({
                    title: 'Пренасочване към банката...',
                    text: 'Моля изчакайте',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                        form.appendTo('body').submit();
                    }
                });
                
                return false;
            }
        });
    }, 200);
});
</script>

<style>
.is-invalid {
    border: 1px solid #ee1a19 !important;
    background-color: #fff0f0 !important;
}
.is-invalid:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(238, 26, 25, 0.2);
}
</style>

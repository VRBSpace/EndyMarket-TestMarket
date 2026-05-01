<?php
if (!ISMOBILE) {
    echo '<h4 class="title section-title fw-bold">Детайли на заявката</h4>';
}
?>

<br>

<form id="form-chekout" class="js-validate table-form guest-cart-aside" method="POST">
    
    <!-- ЛИЧНИ ДАННИ -->
    <div>
        <h5 style="border-bottom: 1px solid #ee1a19;">
            <span class="fa fa-user orange-bg"></span>
            <span class="title-content">Лични данни</span>
        </h5> 

        <div class="js-form-message">
            <label>Име и Фамилия <b class="text-danger">*</b></label>
            <input class="w-100" name="delivery_json[lice_zaKont]" type="text" required>
        </div>

        <div class="row">
            <div class="col-md-6 p-0 p-0">
                <label>Телефон <b class="text-danger">*</b></label>
                <input class="w-100" name="tel" type="tel" required>
            </div>

            <div class="col-md-6 p-0 p-0">
                <label>Имейл <b class="text-danger">*</b></label>
                <input class="w-100" name="delivery_json[email]" type="email" required>
            </div>
        </div>
    </div> 

    <br>

    <!-- ДОСТАВКА -->
    <section>
        <?= view($views['cart-aside-user-deliveryObekt']) ?>
    </section>

    <div id="js-deliveryEstimate" class="alert alert-warning mt-3 mb-2 hide" data-route="<?= site_url('/ApiQurier/calculateDostavka') ?>">
        <strong>Ориентировъчна цена за доставка:</strong>
        <span id="js-deliveryEstimatePrice"></span>
        <div class="small mt-1">Цената е информативна и се заплаща при получаване на пратката. Не се включва в общата сума на поръчката.</div>
    </div>

    <br>

    <!-- ПЛАЩАНЕ -->
    <div class="mb-2">
        <h5 style="border-bottom: 1px solid #ee1a19;">
            <span class="fa fa-dollar-sign orange-bg"></span>
            <span class="title-content">Плащане</span>
        </h5>

        <input id="cash" type="radio" name="payment_method" value="CASH" checked>
        <label for="cash">Наложен платеж</label>

        <br>

        <input id="card" type="radio" name="payment_method" value="CARD">
        <label for="card">Плащане с карта (онлайн)</label>
    </div>

    <!-- КОМЕНТАР -->
    <div class="mb-2">
        <h5 style="border-bottom: 1px solid #ee1a19;">
            <span class="fa fa-comment orange-bg"></span>
            <span class="title-content">Коментар</span>
        </h5>

        <textarea name="belezka" class="w-100"></textarea>
    </div>

    <!-- TERMS -->
    <div> 
        <input id="is_agree" name="is_agree" type="checkbox" value="1">
        <span>
            Съгласяване с
            <a href="<?= route_to('Pages-uslovia') ?>" target="_blank" rel="noopener noreferrer" class="text-red">общите правила и условия</a>
            и
            <a href="<?= route_to('Pages-privacyData') ?>" target="_blank" rel="noopener noreferrer" class="text-red">политика за поверителност</a>
        </span>
    </div>

    <!-- BUTTON -->
    <div class="text-center mt-3">
        <?php if (!empty($cartSessionProductsList)): ?>
            <button id="js-checkout-btn" type="button"
                class="btn btn-primary-orange w-100 text-white"
                data-route="<?= route_to('Checkout-klient') ?>">
                Завършване на поръчката
            </button>
        <?php endif ?>
    </div>

</form>

<br>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$('#js-checkout-btn').on('click', function (e) {

    e.preventDefault();

    let isValid = true;
    let errorMessages = [];

    // 1. Проверка на Име и Фамилия
    let name = $('input[name="delivery_json[lice_zaKont]"]');
    if (!name.val().trim()) {
        isValid = false;
        errorMessages.push('Моля, въведете име и фамилия');
        name.addClass('is-invalid');
    } else {
        name.removeClass('is-invalid');
    }

    // 2. Проверка на Телефон
    let phone = $('input[name="tel"]');
    if (!phone.val().trim()) {
        isValid = false;
        errorMessages.push('Моля, въведете телефонен номер');
        phone.addClass('is-invalid');
    } else {
        phone.removeClass('is-invalid');
    }

    // 3. Проверка на Имейл
    let email = $('input[name="delivery_json[email]"]');
    let emailValue = email.val().trim();
    let emailPattern = /^[^\s@]+@([^\s@]+\.)+[^\s@]+$/;
    
    if (!emailValue) {
        isValid = false;
        errorMessages.push('Моля, въведете имейл адрес');
        email.addClass('is-invalid');
    } else if (!emailPattern.test(emailValue)) {
        isValid = false;
        errorMessages.push('Моля, въведете валиден имейл адрес');
        email.addClass('is-invalid');
    } else {
        email.removeClass('is-invalid');
    }

    // 4. Проверка на избран куриер
    let deliveryMethod = $('input[name="delivery_json[izborKurier]"]:checked');
    if (deliveryMethod.length === 0) {
        isValid = false;
        errorMessages.push('Моля, изберете метод за доставка');
    }

    // 5. Проверка на Населено място
    let city = $('#js-grad');
    if (!city.val().trim()) {
        isValid = false;
        errorMessages.push('Моля, въведете населено място');
        city.addClass('is-invalid');
    } else {
        city.removeClass('is-invalid');
    }

    // 6. Проверка според тип доставка
    let selectedMethod = deliveryMethod.val();
    
    if (selectedMethod === 'econt_office' || selectedMethod === 'speedy_office') {
        // До офис - проверка на Офис
        let office = $('#js-ofis');
        if (!office.val().trim()) {
            isValid = false;
            errorMessages.push('Моля, изберете офис за доставка');
            office.addClass('is-invalid');
        } else {
            office.removeClass('is-invalid');
        }
    } else if (selectedMethod === 'econt_door' || selectedMethod === 'speedy_door') {
        // До адрес - проверка на Номер/Блок
        let streetNum = $('input[name="delivery_json[street_num]"]');
        if (!streetNum.val().trim()) {
            isValid = false;
            errorMessages.push('Моля, въведете номер/блок за адреса');
            streetNum.addClass('is-invalid');
        } else {
            streetNum.removeClass('is-invalid');
        }
    }

    // 7. Проверка на Общи условия
    if (!$('#is_agree').is(':checked')) {
        isValid = false;
        errorMessages.push('Трябва да се съгласите с общите правила и условия и политиката за поверителност');
    }

    // Ако има грешки - показваме ги и спираме
    if (!isValid) {
        Swal.fire({
            icon: 'warning',
            title: 'Внимание',
            html: errorMessages.join('<br>'),
            confirmButtonText: 'OK',
            confirmButtonColor: '#ee1a19'
        });
        return;
    }

    // Всичко е валидно - продължаваме
    let paymentMethod = $('input[name="payment_method"]:checked').val();
    let form = $('#form-chekout');
    let btn = $(this);

    btn.prop('disabled', true);

    if (paymentMethod === 'CARD') {

        form.attr('action', '/cart/checkout2');
        form.attr('method', 'POST');
        form[0].submit();

    } else {

        $.ajax({
            url: '/Checkout/checkout_klient',
            type: 'POST',
            data: form.serialize(),

            success: function (res) {

                if (res.success) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Поръчката е приета! 🎉',
                        text: res.message,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#ee1a19',
                        backdrop: true
                    }).then((result) => {

                        if (result.isConfirmed) {
                            window.location.href = '/';
                        }
                    });

                } else {

                    Swal.fire({
                        icon: 'error',
                        title: 'Грешка',
                        text: res.errMessage || 'Възникна проблем!'
                    });

                    btn.prop('disabled', false);
                }
            },

            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Сървърна грешка',
                    text: 'Моля опитайте отново.'
                });

                btn.prop('disabled', false);
            }
        });
    }
});

// Допълнително: махаме инвалид стиловете при писане
$('#form-chekout input, #form-chekout textarea').on('input', function() {
    $(this).removeClass('is-invalid');
});

// При смяна на метода за доставка, трием грешките
$('input[name="delivery_json[izborKurier]"]').on('change', function() {
    $('#js-ofis, input[name="delivery_json[street_num]"]').removeClass('is-invalid');
});
</script>

<style>
    .guest-cart-aside .orange-bg{
        background-color:white;
    }
</style>

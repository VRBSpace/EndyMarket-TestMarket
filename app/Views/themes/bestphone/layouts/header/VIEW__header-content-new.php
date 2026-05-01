<section class="section2 sticky py-1 py-xl-1 bg-primary-down-lg">
    <!-- ISMOBILE съобщение за логнатия потребител -->
    <?php if (ISMOBILE): ?>
        <div class="row align-items-center justify-content-around">
            <!-- безпл. доставка -->
            <?php
            // Извличаме настройките за поръчки
            $_settingsOrder = $settings_portal['order']['order'] ?? [];

            $_freeDostRange = (float) ($_settingsOrder['freeDostavkaLeftPrice'] ?? 0);

            // Избираме подходящата стойност
            $_isFree = $_settingsOrder['freeDostavkaPrice'] ?? '';

            // Показваме лентата само ако има зadadена стойност
            if (!empty($_isFree)):
            ?>
                <div class="d-flex mx-2">
                    <img id="css-carLogo"
                        src="<?= $_ENV['app.imageDataDir'] ?>eshop/logo/delivery-truck-bl.png"
                        alt="Доставка">

                    <div id="css-ribbon" class="text-center">БЕЗПЛАТНА ДОСТАВКА <?= session()->has('user_id') ? '<br>' : '' ?> НАД <span id="js-freeDostavkaPrice"><?= $_isFree ?></span> <?= get_valuta() ?></div>

                    <input id="js-freeDostRange" type="hidden" value="<?= $_freeDostRange ?>">
                </div>
            <?php endif; ?>
            <!-- End безпл. доставка -->

            <?php if (session()->has('user_id')): ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>

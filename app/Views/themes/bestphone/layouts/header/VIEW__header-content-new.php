<section class="section2 sticky py-1 py-xl-1 bg-primary-down-lg">
    <!-- ISMOBILE съобщение за логнатия потребител -->
    <?php if (ISMOBILE): ?>
        <div class="row align-items-center justify-content-around">
            <!-- безпл. доставка -->
            <?php
            $_isLoggedIn = session()->has('user_id');

            // Извличаме настройките за поръчки
            $_settingsOrder = $settings_portal['order']['order'] ?? [];

            // Създаваме масив със стойности за безплатна доставка според статуса
            $_freePrices = [
                true  => $_settingsOrder['freeDostavkaPrice'] ?? '',
                false => $_settingsOrder['freeDostavkaKlPrice'] ?? '',
            ];

            $_freeDostRange = (float) ($_settingsOrder[$_isLoggedIn ? 'freeDostavkaLeftPrice' : 'freeDostavkaKlLeftPrice'] ?? 0);

            // Избираме подходящата стойност
            $_isFree = $_freePrices[$_isLoggedIn];

            // Показваме лентата само ако има зadadена стойност
            if (!empty($_isFree)):
            ?>
                <div class="d-flex mx-2">
                    <img id="css-carLogo"
                        src="<?= $_ENV['app.imageDataDir'] ?>eshop/logo/delivery-truck-bl.png"
                        alt="Доставка">

                    <div id="css-ribbon" class="text-center">БЕЗПЛАТНА ДОСТАВКА <?= session()->has('user_id') ? '<br>' : '' ?> НАД <span id="js-freeDostavkaPrice"><?= $_isFree ?></span> лв.</div>

                    <input id="js-freeDostRange" type="hidden" value="<?= $_freeDostRange ?>">
                </div>
            <?php endif; ?>
            <!-- End безпл. доставка -->

            <?php if (session()->has('user_id')): ?>

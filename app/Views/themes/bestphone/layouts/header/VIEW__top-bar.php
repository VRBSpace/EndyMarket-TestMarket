<!-- Topbar -->
<section class="section1 u-header-topbar py-1 pt-2 d-none d-xl-block">
    <div class="container1">
        <div class="d-flex align-items-center justify-content-end">
            <div class="topbar-left mr-3">
                <!-- безпл. доставка -->
                <?php
                $_isLoggedIn = session() -> has('user_id');

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

                // Показваме лентата само ако има зададена стойност
                if (!empty($_isFree)):
                    ?>
                    <div class="d-flex align-items-center">
                        <img id="css-carLogo" src="<?= $_ENV['app.imageDataDir'] ?>eshop/logo/delivery-truck-bl.png" alt="Доставка">

                        <div id="css-ribbon">БЕЗПЛАТНА ДОСТАВКА НАД <span id="js-freeDostavkaPrice"><?= $_isFree ?></span> <?= get_valuta() ?>
                        </div>

                        <input id="js-freeDostRange" type="hidden" value="<?= $_freeDostRange ?>">
                    </div>
                <?php endif; ?>
                <!-- End безпл. доставка -->
            </div>

            <div class="topbar-right">
                <ul class="list-inline mb-1">

                    <li class="list-inline-item mr-0 u-header-topbar__nav-item u-header-topbar__nav-item-border">
                        <!-- Account Sidebar Toggle Button -->
                        <?php if (session() -> has('user_id')): ?>
                            <!-- Покажете съдържанието, което трябва да види логнатият потребител -->
                            <p class="mb-0">Добре дошли: <strong><?= session('username') ?> </strong>  <a href="<?= base_url('logout') ?>">| Logout</a></p>

                            <!-- Пример на линк за изход, променете го според вашите нужди -->
                        <?php else: ?>
                            <a id="sidebarNavToggler" href="javascript:;" role="button" class="u-header-topbar__nav-link"
                               aria-controls="sidebarContent"
                               aria-haspopup="true"
                               aria-expanded="false"
                               data-unfold-event="click"
                               data-unfold-hide-on-scroll="false"
                               data-unfold-target="#sidebarContent"
                               data-unfold-type="css-animation"
                               data-unfold-animation-in="fadeInRight"
                               data-unfold-animation-out="fadeOutRight"
                               data-unfold-duration="500">
                                <i class="ec ec-user mr-1"></i>  Вход за дилъри
                            </a>
                        <?php endif; ?>
                        <?php if (session() -> has('error')): ?>
                            <br />
                            <span style="color: red"><?php echo session('error'); ?></span>
                        <?php endif; ?>
                        <!-- End Account Sidebar Toggle Button -->
                    </li>

                </ul>
            </div>
        </div>
    </div>
</section>
<!-- End Topbar -->

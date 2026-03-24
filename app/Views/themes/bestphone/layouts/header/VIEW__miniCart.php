<?php
$_unfoldTtarget   = '#basicDropdownHover';
$_unfoldTtargetId = 'basicDropdownHover';

if (!empty($isCloned)) {
    $_unfoldTtarget   = '#basicDropdownHover1';
    $_unfoldTtargetId = 'basicDropdownHover1';
}
?>

<?php if ($customConfig -> btn['showCart'] || session() -> has('user_id')) { ?>
    <div id="basicDropdownHoverInvoker" class="text-gray-90 position-relative d-flex" title="Количка"
         data-toggle="tooltip" 
         data-placement="top" 
         aria-controls="basicDropdownHover"
         aria-haspopup="true"
         aria-expanded="false"
         data-unfold-event="click"
         data-unfold-target="<?= $_unfoldTtarget ?>"
         data-unfold-type="jquery-slide"
         data-unfold-duration="300"
         data-unfold-delay="300"
         data-unfold-hide-on-scroll="true"
         data-unfold-animation-in="slideInUp"
         >
        <i class="bi bi-cart-check-fill font-size-28 text-red"></i>

        <span class="js-cart-quantity <?= count($cartSessionProductsList) !== 0 ? 'cart-effect' : '' ?> bg-lg-down-black width-22 height-22 bg-primary position-absolute d-flex align-items-center justify-content-center rounded-circle top-0 font-weight-bold font-size-12 text-white" style="top: -4px !important; right: -4px !important; "><?= count($cartSessionProductsList) ?></span>
    </div>

    <div id="<?= $_unfoldTtargetId ?>" class="js-miniCart-content mr-3 border cart-dropdown dropdown-menu dropdown-unfold border-top border-top-primary mt-3 border-width-2 border-right-0 border-bottom-0 left-auto right-0" aria-labelledby="basicDropdownHoverInvoker">

        <fieldset style="overflow-y: auto;overflow-x: hidden;max-height: 400px;">
            <?php if ($cartSession['cart_all_products_info']['quantity'] > 0): ?>    

                <div class="text-center px-4 mb-2">
                    <a href="<?= route_to('Cart-index') ?>" class="btn btn-primary mb-3 mb-md-0 font-weight-normal px-5 px-md-4 px-lg-5 rounded-0 transition-3d-hover">Към количката</a>
                </div>

                <div class="border-bottom border-bottom-black font-size-12 d-flex col ml-4">
                    <div class=" ml-5 col-4">Продукт</div>
                    <div class="col-1">Кол.</div>
                    <div class="col-1">Ед.цена</div>
                </div>

                <ul class="list-unstyled px-3 pt-3">
                    <?php foreach ($cartSessionProductsList as $cartProduct): ?>                                               
                        <li class="border-bottom mb-1">
                            <div>
                                <ul class="list-unstyled row mx-n2">
                                    <li class="px-2 col-auto">
                                        <img class="size55x55" src="<?= $_ENV['app.imageDir'] . $cartProduct['product_info'] -> image ?>" onerror="this.onerror=null;this.src='<?= $_ENV['app.noImage'] ?>';" alt="...">
                                    </li>

                                    <li class="px-2 col">
                                        <h5 class="text-blue font-size-11 font-weight-bold"> <?= mb_strimwidth($cartProduct['product_info']->product_name, 0, 20, '...') ?></h5>
                                    </li>

                                    <li class="px-2 col">
                                        <span class="font-size-12">
                                            <?= $cartProduct['quantity'] ?> × <?= number_format($cartProduct['item_price'], 2, '.', ' ') . get_valuta(); echo ' (' . priceToEur2($cartProduct['item_price']) . ')'; ?>
                                        </span>
                                    </li>

                                    <li class="px-2 col-auto">
                                        <a href="#" class="text-gray-90 remove-product" data-route="<?= route_to('Cart-removeFromCart') ?>" data-product-id="<?= $cartProduct['product_info'] -> product_id ?>">
                                            <i class="ec ec-close-remove"></i>
                                        </a>                                  
                                    </li>
                                </ul>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </fieldset>

            <!-- безплатна доставка -->
            <?php
            // Проверка за потребител
            $_isLoggedIn = session() -> has('user_id');

            $_settingsOrder = $settings_portal['order']['order'] ?? [];

            // Граници за безплатна доставка
            $_totalFreeDostLimit = (float) ($_settingsOrder[$_isLoggedIn ? 'freeDostavkaPrice' : 'freeDostavkaKlPrice'] ?? 0);

            $_freeDostRange = (float) ($_settingsOrder[$_isLoggedIn ? 'freeDostavkaLeftPrice' : 'freeDostavkaKlLeftPrice'] ?? 0);

            // Обща сума в количката
            $_totalPrice = (float) ($cartSession['cart_all_products_info']['price'] ?? 0);

            // Колко остава до безплатна доставка
            $_amountLeft = $_totalFreeDostLimit - $_totalPrice;

            // Етикет за ДДС
            $_ddsLabel = $_isLoggedIn ? 'БЕЗ ДДС' : 'С ДДС';

            // Показване на съобщение
            if ($_totalFreeDostLimit > 0 && $_amountLeft >= 0 && $_amountLeft <= $_freeDostRange):
                ?>
                <div class="css-glow-animate text-center bg-primary text-white p-2 font-size-12">
                    ОСТАВАТ <?= number_format($_amountLeft, 2) ?> <?= get_valuta() ?> <?= $_ddsLabel ?> ДО БЕЗПЛАТНА ДОСТАВКА
                </div>
            <?php endif ?>
            <!-- край  -->

            <div class="text-center fw-bold">
                <span class="col-1 label-total">Общо:</span>
                <span class="col-2"><span class="cart-count"><?= count($cartSessionProductsList) ?></span> продукт/a за</span>
                <span class="col-3">
                    <span><?php echo number_format($cartSession['cart_all_products_info']['price'], 2, '.', '') ?> <?= get_valuta() ?></span>
                    <?= '/ ' . priceToEur2($cartSession['cart_all_products_info']['price']) ?>
                </span>
            </div>



        <?php else: ?>
            <p style="text-align:center">Вашата количка е празна</p>
        <?php endif ?>


    </div>
<?php } ?>

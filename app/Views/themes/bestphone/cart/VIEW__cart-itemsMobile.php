<?php $_userId = session() -> has('user_id') ?>

<div class="css-cart-mobile">
    <h3 class="text-center mb-2">Вашата количка</h3>

    <?php
    $_userId = session() -> has('user_id');

    $_settingsOrder = $settings_portal['order']['order'] ?? [];

    // Граници за безплатна доставка
    $_totalFreeDostLimit = (float) ($_settingsOrder[$_userId ? 'freeDostavkaPrice' : 'freeDostavkaKlPrice'] ?? 0);

    $_freeDostRange = (float) ($_settingsOrder[$_userId ? 'freeDostavkaLeftPrice' : 'freeDostavkaKlLeftPrice'] ?? 0);

    // Обща сума в количката
    $_totalPrice = (float) ($cartSession['cart_all_products_info']['price'] ?? 0);

    // Колко остава до безплатна доставка
    $_amountLeft = $_totalFreeDostLimit - $_totalPrice;

    // Етикет за ДДС
    $_ddsLabel = $_userId ? 'БЕЗ ДДС' : 'С ДДС';

    // Определяне дали да се показва блока (показваме ако сумата е над 0 и оставащата сума е в зададения диапазон)
    $_showBlock = $_totalFreeDostLimit > 0 && $_amountLeft > 0 && $_amountLeft <= $_freeDostRange;
    ?>

    <div id="js-blockTotalFreePrice" class="css-glow-animate text-center bg-red text-white fw-bold font-size-12 py-2 mb-2 <?= $_showBlock ? '' : 'hide' ?>"> 
        ОСТАВАТ <span id="js-totalFreePrice"><?= number_format($_amountLeft, 2) ?></span> <?= get_valuta() ?> <?= $_ddsLabel ?> ДО БЕЗПЛАТНА ДОСТАВКА
    </div>

    <form class="mb-4" action="#" method="post">

        <table id="cart-table" class="js-printTable w-100">
            <tbody>
                <?php foreach ($cartSessionProductsList as $cartProduct): ?>
                    <tr class="js-product-item" data-product-id="<?= $cartProduct['product_info'] -> product_id ?>">
                        <td class="border-0 p-0 pb-2">
                            <div class="border rounded-0 bg-white">
                                <div class="d-flex align-items-start justify-content-between p-2 border-bottom">
                                    <a class="text-gray-90 pr-2 css-cart-mobile-title" href="<?= site_url('product/' . $cartProduct['product_info'] -> product_id) ?>">
                                        <?= $cartProduct['product_info'] -> product_name ?>
                                    </a>
                                    <a href="#" class="js-remove-from-cart btn btn-sm css-btn-red-outline rounded-circle css-cart-mobile-remove" data-route="<?= route_to('Cart-removeFromCart') ?>" data-product-id="<?= $cartProduct['product_info'] -> product_id ?>" aria-label="Премахни продукт">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </div>

                                <div class="p-2 d-flex align-items-start">
                                    <a class="img-fancybox mr-2" href="<?= !is_null($cartProduct['product_info'] -> image) ? $_ENV['app.imageDir'] . $cartProduct['product_info'] -> image : '' ?>">
                                        <img class="img-fluid border border-color-1 css-cart-mobile-image" src="<?= $_ENV['app.imageDir'] . $cartProduct['product_info'] -> image ?>" onerror="this.onerror=null;this.src='<?= $_ENV['app.noImage'] ?>';" alt="...">
                                    </a>

                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <small class="text-dark font-weight-bold">Количество</small>
                                            <div class="js-quantity d-flex align-items-center border rounded-pill px-1">
                                                <span class="hidden"><?= $cartProduct['quantity'] ?></span>

                                                <a class="js-minus btn btn-icon btn-xs btn-outline-secondary rounded-circle border-0 p-1" href="javascript:;" data-route="<?= route_to('Cart-changeQty') ?>" data-product-id="<?= $cartProduct['product_info'] -> product_id ?>">
                                                    <small class="fas fa-minus btn-icon__inner"></small>
                                                </a>

                                                <input size="4" class="js-result form-control h-auto border-0 p-0 shadow-none text-center css-background-transparent css-cart-mobile-qty" data-route="<?= route_to('Cart-changeQty') ?>" data-product-id="<?= $cartProduct['product_info'] -> product_id ?>" type="text" value="<?= $cartProduct['quantity'] ?>">

                                                <a class="js-plus btn btn-icon btn-xs btn-outline-secondary rounded-circle border-0 p-1" href="javascript:;" data-route="<?= route_to('Cart-changeQty') ?>" data-product-id="<?= $cartProduct['product_info'] -> product_id ?>">
                                                    <small class="fas fa-plus btn-icon__inner"></small>
                                                </a>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between font-size-13 mb-1 css-cart-mobile-price-row">
                                            <span class="text-dark">Ед. цена <?= $_ddsLabel ?>:</span>
                                            <strong class="js-zenaProdava text-dark"><?= number_format($cartProduct['item_price'], 2) ?> <?= get_valuta() ?></strong>
                                        </div>

                                        <div class="d-flex justify-content-between font-size-13 css-cart-mobile-price-row">
                                            <span class="text-dark">Общо <?= $_ddsLabel ?>:</span>
                                            <strong class="js-total text-dark"><?= number_format($cartProduct['total_price'], 2) ?> <?= get_valuta() ?></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach ?>
                <tr class="notAutoNum">
                    <td class="text-right pb-2">
                        <a class="js-empty-cart text-red font-size-13" href="<?= route_to('Cart-emptyCart') ?>">
                            изтрий всички
                        </a>
                    </td>
                </tr>
            </tbody>

            <tfoot class="bg-red text-white">
                <tr>
                    <td class="px-2 pt-2"><h5 class="mb-1 text-white">Поръчка</h5></td>
                </tr>
                <?php if ($_userId): ?>
                    <tr id="js-total_bez_dds">
                        <td>
                            <div class="d-flex justify-content-between px-2">
                                <div class="text-white">Сума без ДДС:</div> 
                                <div class="suma text-right text-white">
                                    <?= number_format($cartSession['cart_all_products_info']['price'], 2) ?> <?= get_valuta() ?>
                                    <small><br> <?= priceToEur2($cartSession['cart_all_products_info']['price']) ?></small>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr id="js-dds">
                        <td>
                            <div class="d-flex justify-content-between px-2">
                                <div class="text-white">ДДС:</div> 
                                <div class="suma text-right text-white">
                                    <?= number_format($cartSession['cart_all_products_info']['price'] * 0.2, 2) ?> <?= get_valuta() ?>
                                    <small><br> <?= priceToEur2($cartSession['cart_all_products_info']['price'] * 0.2) ?></small> 
                                </div>
                            </div> 
                        </td>
                    </tr> 
                <?php endif ?>

                <!-- транспортни разходи -->
                <?php
                $_deliveryPrice = $settings_portal['order']['order']['deliveryPrice'] ?? 0;

                if (!empty($_deliveryPrice) && $_amountLeft > 0):
                    ?>
                    <tr id="deliveryRow">
                        <td>
                            <div class="d-flex justify-content-between px-2">
                                <div class="text-white">Доставка:</div> 
                                <div class="text-right text-white">
                                    <?= number_format($_deliveryPrice, 2) ?> <?= get_valuta() ?>
                                    <small><br> <?= priceToEur2($_deliveryPrice) ?></small> 
                                    <input id="deliveryPrice" type="hidden" value="<?= number_format($_deliveryPrice, 2) ?>">
                                </div>
                            </div>  
                        </td>
                    </tr>

                 <?php elseif (!empty($_totalFreeDostLimit) && !empty($_freeDostRange) && $_amountLeft <= 0): ?>
                    <tr>
                        <td>
                            <div class="d-flex justify-content-between px-2">
                                <div class="text-white">Доставка:</div> 
                                <div class="text-right text-white">безплатна
                                </div>
                            </div>  
                        </td>
                    </tr>
                <?php endif ?>

                <tr id="js-total_s_dds">
                    <td>
                        <div class="d-flex justify-content-between px-2 pb-2">
                            <div class="text-white">Тотал с ДДС:</div> 
                            <div class="suma text-right text-white">
                                <?= number_format(($cartSession['cart_all_products_info']['price'] * ($_userId ? 1.2 : 1)), 2) ?> <?= get_valuta() ?>
                                <small><br> <?= priceToEur2($cartSession['cart_all_products_info']['price'] * ($_userId ? 1.2 : 1)) ?></small>
                            </div>
                        </div>  
                    </td>
                </tr>
            </tfoot>
        </table>
    </form>

</div>

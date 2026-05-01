<?php

$_userId = session() -> has('user_id');

$_settingsOrder = $settings_portal['order']['order'] ?? [];

// Граници за безплатна доставка
$_totalFreeDostLimit = (float) ($_settingsOrder['freeDostavkaPrice'] ?? 0);
$_freeDostRange      = (float) ($_settingsOrder['freeDostavkaLeftPrice'] ?? 0);

// Обща сума в количката с ДДС - смятаме я от редовете в количката.
$_grandTotalPrice  = (float) array_sum(array_map(
                        static fn($item) => (float) ($item['total_price'] ?? 0),
                        $cartSessionProductsList ?? []
                ));
$_totalPrice       = $_grandTotalPrice;
$_sesionTotalPrice = $_grandTotalPrice;

$_ddsLabel = !empty($settingsJson -> prices_with_dds) ? 'с ДДС' : 'без ДДС';

$_subtotalPrice = $_grandTotalPrice / $dds;
$_ddsPrice      = $_grandTotalPrice - $_subtotalPrice;

// freeDostavkaPrice вече се третира като праг в основната валута на сайта
$_amountLeft = $_totalFreeDostLimit - $_totalPrice;

// Определяне дали да се показва блока (показваме ако сумата е над 0 и оставащата сума е в зададения диапазон)
$_showBlock = $_totalFreeDostLimit > 0 && $_amountLeft > 0 && $_amountLeft <= $_freeDostRange;
?>

<div id="js-blockTotalFreePrice" class="css-glow-animate text-center bg-primary text-white fw-bold py-2 font-size-18   <?= $_showBlock ? '' : 'hide' ?>">
    ОСТАВАТ <span id="js-totalFreePrice"><?= number_format($_amountLeft, 2) ?></span> <?= get_valuta() ?> <?= $_ddsLabel ?> ДО БЕЗПЛАТНА ДОСТАВКА
</div>
<form class="mb-4" action="#" method="post">
    <table id="cart-table" class="js-printTable table table-hover" cellspacing="0">
        <thead class="orange-bg">
            <tr>
                <th class="product-remove">&nbsp;</th>
                <th></th>
                <th class="product-thumbnail text-center text-white">Снимка</th>
                <th class="product-name text-white">Име на продукт</th>
                <th class="product-quantity w-lg-15 text-white">Количество</th>
                <th class="product-price text-white">Ед.цена&nbsp;<div class=""><?= $_ddsLabel ?></div></th>
                <th class="product-subtotal w-10 text-white">Общо&nbsp;<div class=""><?= $_ddsLabel ?></div></th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($cartSessionProductsList as $cartProduct): ?>
                <tr class="js-product-item" data-product-id="<?= $cartProduct['product_info'] -> product_id ?>">
                    <td class="text-center">
                        <a href="#" class="js-remove-from-cart btn-primary-orange font-size-26 px-2 rounded" data-route="<?= route_to('Cart-removeFromCart') ?>" data-product-id="<?= $cartProduct['product_info'] -> product_id ?>">×</a>
                    </td>

                    <td class="text-center"></td>

                    <td class="d-none d-md-table-cell">
                        <a href="<?= site_url('product/' . $cartProduct['product_info'] -> product_id) ?>">
                            <img class="img-fluid max-width-174 p-1 border border-color-1" src="<?= $_ENV['app.imageDir'] . $cartProduct['product_info'] -> image ?>" onerror="this.onerror=null;this.src='<?= $_ENV['app.noImage'] ?>';" alt="...">
                        </a>
                    </td>

                    <td data-title="Продукт">
                        <a href="<?= site_url('product/' . $cartProduct['product_info'] -> product_id) ?>" class="text-gray-90"><?= $cartProduct['product_info'] -> product_name ?></a>
                    </td>

                    <!-- Quantity -->
                    <td data-title="Количество">
                        <span class="hidden"><?= $cartProduct['quantity'] ?></span>

                        <div class="border rounded-pill px-1 py-1 w-75 ">
                            <div class="js-quantity d-flex align-items-center">
                                <div>
                                    <input  class="js-result form-control h-auto border-0 p-0 shadow-none text-center css-background-transparent" data-route="<?= route_to('Cart-changeQty') ?>" data-product-id="<?= $cartProduct['product_info'] -> product_id ?>" type="text" value="<?= $cartProduct['quantity'] ?>">
                                </div>

                                <div class="col-auto px-1">
                                    <a class="js-minus btn btn-icon btn-xs btn-outline-secondary rounded-circle border-0" href="javascript:;" data-route="<?= route_to('Cart-changeQty') ?>" data-product-id="<?= $cartProduct['product_info'] -> product_id ?>">
                                        <small class="fas fa-minus btn-icon__inner"></small>
                                    </a>

                                    <a class="js-plus btn btn-icon btn-xs btn-outline-secondary rounded-circle border-0" href="javascript:;" data-route="<?= route_to('Cart-changeQty') ?>" data-product-id="<?= $cartProduct['product_info'] -> product_id ?>">
                                        <small class="fas fa-plus btn-icon__inner"></small>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- End Quantity -->
                    </td>

                    <td data-title="Цена">
                        <span class="js-zenaProdava"><?= number_format($cartProduct['item_price'], 2) ?> <?= get_valuta() ?></span>
                        <br>(<?= priceToEur2($cartProduct['item_price']) ?>)
                    </td>

                    <td class="js-total">
                        <?= number_format($cartProduct['total_price'], 2) ?> <?= get_valuta() ?>
                        <br>(<?= priceToEur2($cartProduct['total_price']) ?>)
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>

        <tfoot class="orange-bg">
            <tr id="js-total_bez_dds">
                <td colspan="6">
                    <span class="float-right text-white">Сума без ДДС:</span>
                </td>

                <td class="suma text-white"><?= number_format($_subtotalPrice, 2) ?> <?= get_valuta() ?>
                    <br>(<?= priceToEur2($_subtotalPrice) ?>)
                </td>
            </tr>

            <tr id="js-dds">
                <td colspan="6">
                    <span class="float-right text-white">ДДС:</span>
                </td>

                <td class="suma text-white"><?= number_format($_ddsPrice, 2) ?> <?= get_valuta() ?>
                    <br>(<?= priceToEur2($_ddsPrice) ?>)
                </td>
            </tr>

            <!-- транспортни разходи -->
            <?php

            $_deliveryPrice = $settings_portal['order']['order']['deliveryPrice'] ?? 0;
            // ако $_amountLeft > 0 значи остават еди колко си до безп дост
            // ако $_amountLeft <= 0 значи имаме безп дост
            if (!empty($_deliveryPrice) && $_amountLeft > 0):
                ?>
                <tr id="deliveryRow">
                    <td colspan="6">
                        <span class="float-right text-white">Доставка:</span>
                    </td>

                    <td class="text-white">
                        <?= number_format($_deliveryPrice, 2) ?> <?= get_valuta() ?>
                        <br>(<?= priceToEur2($_deliveryPrice) ?>)
                        <input id="deliveryPrice" type="hidden" value="<?= number_format($_deliveryPrice, 2) ?>">
                    </td>
                </tr>

            <?php elseif (!empty($_totalFreeDostLimit) && $_amountLeft <= 0): ?>
                <tr>
                    <td colspan="6">
                        <span class="float-right text-white">Доставка:</span>
                    </td>

                    <td class="text-white">безплатна</td>
                </tr>
            <?php endif ?>

            <tr id="js-total_s_dds">
                <td colspan="6">
                    <span class="float-right text-white">Тотал с ДДС:</span>
                </td>

                <td class="suma text-white"><?= number_format($_grandTotalPrice, 2) ?> <?= get_valuta() ?>
                    <br>(<?= priceToEur2($_grandTotalPrice) ?>)
                </td>
            </tr>
        </tfoot>
    </table>
</form>

<div>
    <a class="js-empty-cart btn btn-danger rounded-0" href="<?= route_to('Cart-emptyCart') ?>">
        <i class="fa fa-trash"></i>&nbsp;изтрий цялата поръчка</a>

    <a id="exportXls" class="btn btn-primary rounded-0 transition-3d-hover" href="javascript;" data-route="<?= route_to('Cart-xls_export') ?>">
        <i class="fa fa-file-excel"></i>&nbsp;Ексел експорт на поръчката</a>  
</div>

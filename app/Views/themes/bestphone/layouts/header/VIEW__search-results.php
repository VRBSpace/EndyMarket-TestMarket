
<?php
helper('price');
?>

<?php if (empty($products)): ?>
    <div class="product-search">
        <div class="product-details">
            <h5>Не бяха открити продукти, отговарящи на критериите от търсенето.</h5>
        </div>
    </div>
<?php else: ?>

    <?php foreach ($products as $val): ?>
        <a href="/product/<?= $val['product_id'] ?>" class="product-search-result">
            <div class="product-search">
                <img class="product-image d-block w-auto h-auto float-left mr-5" style="max-width:50px;max-height:50px;" src="<?= $_ENV['app.imageDir'] . $val['image'] ?>" onerror="this.onerror=null;this.src='<?= $_ENV['app.noImage'] ?>';" alt="...">

                <div class="product-details" style="overflow: hidden">
                    <h6 class="product-search-title"><?= $val['product_name'] ?></h6>

                    <div class="row col my-3">
                        <?php
                        $_basePrice = $val[$productPriceLevel] ?? ($val['cenaKKC'] ?? 0);

                        $_promoPercent = getPromoPercentFromGensoftJson($val['gensoft_json'] ?? null);
                        $_promoPrice   = calculatePromoPrice($_basePrice, $_promoPercent);
                        $_price        = $_promoPrice !== null ? $_promoPrice : $_basePrice;
                        $_percent      = $_promoPrice !== null ? abs((float) $_promoPercent) : null;
                        ?>

                        <div class="prodcut-price d-flex align-items-center">
                            <?php if ($_percent !== null): ?>
                                <div class="css-old-price price-old font-size-14 mr-2">
                                 <?= preg_replace('/\\.([0-9]*)/', '<sup class="font-size-12">.$1</sup>', number_format($_basePrice, 2)) ?>
                                    <small><?= get_valuta() ?></small>
                                </div>
                            <?php endif; ?>

                            <div class="font-size-16 price-current">
                                <?= !empty($_price) ? '/ ' . priceToEur($_price, 12) : '' ?>
                                <small class="fw-bold"><?= get_valuta() ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    <?php endforeach ?>
<?php endif ?>

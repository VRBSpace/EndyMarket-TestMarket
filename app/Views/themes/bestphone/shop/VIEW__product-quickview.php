<?php
helper('price');
$_w         = ISMOBILE ? 'w-50' : '';
$_brojBlock = <<<HTML
 <div class="border py-2 px-3 $_w">
   <div class="js-quantity d-flex align-items-center">
        <div>
            <input class="js-result form-control h-auto border-0 rounded p-0 shadow-none text-center" type="text" value="1">
        </div>

        <div class="col-auto pr-1">
            <a class="js-minus btn btn-icon btn-xs btn-outline-secondary rounded-circle border-0" href="javascript:;">
                <small class="fas fa-minus btn-icon__inner"></small>
            </a>
            <a class="js-plus btn btn-icon btn-xs btn-outline-secondary rounded-circle border-0" href="javascript:;">
                <small class="fas fa-plus btn-icon__inner"></small>
            </a>
        </div>
    </div>
</div>
HTML;
?>

<div class="js-product-item row">
    <div class="col-md-6 mb-4 mb-md-0">
        <?php
        // Определяне на цената на продукта
        $_basePrice = $product->cenaKKC ?? 0;
        $_percent   = null;
        $_price     = $_basePrice;

        $_basePrice = $product->$productPriceLevel ?? $_basePrice;

        $_promoPercent = getPromoPercentFromGensoftJson($product->gensoft_json ?? null);
        $_promoPrice   = calculatePromoPrice($_basePrice, $_promoPercent);
        if ($_promoPrice !== null) {
            $_price   = $_promoPrice;
            $_percent = abs((float) $_promoPercent);
        } else {
            $_price = $_basePrice;
        }

        // Показване процента на отстъпката
        if ($_percent !== null) {
            echo '<span class="label-latest label-sale"><b>- ' . ($_percent ?? '') . ' %</b></span>';
        }
        ?>

        <!-- MAIN slider -->
        <div id="sliderSyncingNav"
             class="js-slick-carousel css-product-images u-slick mb-2"
             data-infinite="true"
             data-arrows-classes="d-none d-lg-inline-block u-slick__arrow-classic u-slick__arrow-centered--y rounded-circle"
             data-arrow-left-classes="fas fa-arrow-left u-slick__arrow-classic-inner u-slick__arrow-classic-inner--left ml-lg-2 ml-xl-4"
             data-arrow-right-classes="fas fa-arrow-right u-slick__arrow-classic-inner u-slick__arrow-classic-inner--right mr-lg-2 mr-xl-4"
             data-nav-for="#sliderSyncingThumb">
            <figure class="js-slide" style="cursor: pointer;">
                <a class="img-fancybox" href="<?= !is_null($product->image) ? $_ENV['app.imageDir'] . $product->image : '' ?>">
                    <img class="lazy img-fluid" src="<?= !is_null($product->image) ? $_ENV['app.imageDir'] . $product->image : '' ?>" alt="...">
                </a>
            </figure>

            <?php if (!empty($additional_mages)): ?>
                <?php foreach ($additional_mages as $image): ?>
                    <div class="js-slide" style="cursor: pointer;">
                        <a class="img-fancybox" href="<?= $_ENV['app.imageDir'] . $image ?>">
                            <img class="lazy img-fluid" src="<?= $_ENV['app.imageDir'] . $image ?>" alt="...">
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- THUMBS slider -->
        <?php if (!empty($additional_mages)): ?>
            <figure id="sliderSyncingThumb"
                    class="js-slick-carousel u-slick u-slick--slider-syncing u-slick--gutters-1 u-slick--transform-off"
                    data-infinite="true"
                    data-slides-show="5"
                    data-is-thumbs="true"
                    data-nav-for="#sliderSyncingNav"><!-- FIX: сочи към main -->
                <picture class="js-slide" style="cursor: pointer;">
                    <img class="lazy img-fluid" src="<?= $_ENV['app.imageDir'] . $product->image ?>" alt="...">
                </picture>

                <?php foreach ($additional_mages as $image): ?>
                    <picture class="js-slide" style="cursor: pointer;">
                        <img class="lazy img-fluid" src="<?= $_ENV['app.imageDir'] . $image ?>" alt="Image Description">
                    </picture>
                <?php endforeach; ?>
            </figure>
        <?php endif; ?>
    </div>

    <div class="col-md-6 mb-md-6 mb-lg-0">
        <div class="mb-2">
            <div class=" <?= ISMOBILE ? '' : 'mb-3 pb-md-1 pb-3' ?>">
                <?php
                $_badges     = [1 => 'НОВО', 2 => 'ОЧАКВАН', 4 => 'ФИКСИРАНА ЦЕНА'];
                $_badgeClass = match ($product->badge_index) {
                    '1' => 'danger',
                    '2' => 'warning', //success
                    default => ''
                }
                ?>
                <div class="mb-2">
                    <span class="badge badge-<?= $_badgeClass ?> font-size-15" style="<?= $product->badge_index == 4 ? 'background: #e697fd;' : '' ?>">
                        <span><?= $_badges[$product->badge_index] ?? '' ?></span>
                </div>

                <h1 class="text-dark text-lh-1dot2 fw-bold my-4 <?= ISMOBILE ? 'font-size-18' : 'font-size-20' ?>"><?= $product->product_name ?>
                    </span>
                </h1>

                <div class="md-3 text-gray-9 font-size-14">Наличност:
                    <?php
                    $nalichClass = 'red';
                    $nalichTxt   = 'не';

                    if ($product->nalichnost > 0) {
                        $nalichClass = 'green';
                        $nalichTxt   = 'да';
                    }
                    ?>
                    <span class="text-<?= $nalichClass ?> font-weight-bold"><?= $nalichTxt ?></span>
                </div>

                <div class="border-bottom mb-3 pb-md-1 pb-3"></div>

                <div class="my-2">
                    <span>Кратко описание:</span>
                    <br>
                    <?= $product->short_description ?>
                </div>

                <div class="d-flex flex-column flex-md-row justify-content-start <?= ISMOBILE ? 'align-items-start' : 'align-items-center' ?>">
                    <div class="<?= ISMOBILE ? '' : 'mb-4 mr-6' ?>">
                        <div class="d-flex align-items-start flex-column <?= ISMOBILE ? 'justify-content-around' : '' ?>">
                            <ins class="font-size-36 text-decoration-none fw-bold mr-4 <?= $_percent !== null ? 'text-danger' : '' ?>">
                                <?= preg_replace('/\.([0-9]*)/', '<sup>.$1</sup>', number_format($_price, 2)) ?>
                                <small class="font-size-20 fw-bold">лв.</small>
                            </ins>

                            <?php if ($_percent !== null): ?>
                                <div class="css-old-price text-gray-100 font-size-18">
                                    <?= preg_replace('/\.([0-9]*)/', '<sup class="font-size-12">.$1</sup>', number_format($_basePrice, 2)) ?>
                                    <small>лв.</small>
                                    <small class="ml-1">(- <?= $_percent ?? '' ?> %)</small>
                                </div>
                            <?php endif ?>
                            <div class="vat-status">Цените са <?= $productPriceLevel == 'cenaKKC' ? 'с' : 'без' ?> ДДС</div>
                        </div>
                    </div>

                    <?php if ($customConfig->btn['showCart']): ?>
                        <div class="d-md-flex align-items-end mb-3 <?= ISMOBILE ? 'w-100 mt-3' : 'w-40' ?>">
                            <?php $_isPrice = $_price ?>

                            <?php if (!empty($_isPrice) && $product->nalichnost > 0): ?>
                                <div class="w-100">
                                    <div class="input-group prodcut-add-cart mb-1 <?= ISMOBILE ? 'w-100' : 'w-auto' ?>">
                                        <div class="css-add-to-cart-quantity col height-40 py-2 px-3">
                                            <div class="js-quantity d-flex align-items-center">
                                                <div>
                                                    <input
                                                        class="js-result text-dark css-add-to-cart-quantity-input form-control h-auto border-0 rounded p-0 shadow-none text-center"
                                                        type="text"
                                                        value="1"
                                                        autocomplete="off"
                                                        name="qty"
                                                        form="form_fastOrder"
                                                        id="qty_ui">
                                                </div>

                                                <div class="col-auto px-1">
                                                    <a class="js-minus btn btn-icon btn-xs btn-outline-secondary rounded-circle border-0" href="javascript:;">
                                                        <small class="fas fa-minus btn-icon__inner"></small>
                                                    </a>

                                                    <a class="js-plus btn btn-icon btn-xs btn-outline-secondary rounded-circle border-0" href="javascript:;">
                                                        <small class="fas fa-plus btn-icon__inner"></small>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <a
                                            id="btn-add-cart-single"
                                            class="css-btn-add-cart btn-add-cart text-white btn-primary height-40 transition-3d-hover1"
                                            data-route="<?= route_to('Cart-addToCart') ?>"
                                            data-product-id="<?= $product->product_id ?>"
                                            href="javascript:;">
                                            <i class="ec ec-add-to-cart mr-2"></i> Купи
                                        </a>
                                    </div>
                                </div>
                            <?php endif ?>
                        </div>
                    <?php endif ?>
                </div>

                <?php if (!empty($product->brandTxt)) : ?>
                    <div class="mb-1">
                        <span>Производител:</span>
                        <b><?= $product->brandTxt ?></b>
                    </div>
                <?php endif ?>

                <?php if (!empty($product->model)) : ?>
                    <div class="mb-1">
                        <span>Модел:</span>
                        <b> <?= $product->model ?></b>
                    </div>
                <?php endif ?>

                <?php if (!empty($product->additional_models)) : ?>
                    <div class="mb-1">
                        <span>Съвместимост с модели:</span>
                        <b> <?= $product->additional_models ?></b>
                    </div>
                <?php endif ?>

                <?php if (!empty($product->kod)) : ?>
                    <div class="mb-1">
                        <span>Код:</span>
                        <b> <?= $product->kod ?></b>
                    </div>
                <?php endif ?>

                <?php if (!empty($product->oem)) : ?>
                    <div class="mb-1">
                        <span>OEM:</span>
                        <b> <?= $product->oem ?></b>
                    </div>
                <?php endif ?>

                <?php if (!empty($product->upc)) : ?>
                    <div class="mb-1">
                        <span>UPC:</span>
                        <b> <?= $product->upc ?></b>
                    </div>
                <?php endif ?>

                <?php if (!empty($product->ean)) : ?>
                    <div class="mb-1">
                        <span>EAN:</span>
                        <b> <?= $product->ean ?></b>
                    </div>
                <?php endif ?>

                <div class="d-md-flex align-items-center">
                    <?php if (isset($product->img_path)) : ?>
                        <figure>
                            <a href="#" class="max-width-150 ml-n2 mb-2 mb-md-0 d-block">
                                <img class="lazy img-fluid" src="<?= base_url($product->img_path) ?>" alt="...">
                            </a>
                        </figure>
                    <?php endif; ?>
                </div>
            </div>

            <div class="border-bottom mb-3 pb-md-1 pb-3"></div>
        </div>
    </div>
</div>
</div>

<script>
  var PRODUCT_ID = '<?= $product->product_id ?>';
</script>

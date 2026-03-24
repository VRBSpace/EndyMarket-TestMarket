<?php
helper('price');
$_sizeTitleProduct = !empty($settings_portal['func']['sizeTitleProduct']) ? "font-size:{$settings_portal['func']['sizeTitleProduct']}px" : '';
?>
<div id="js-products-list" class="tab-pane fade pt-2" role="tabpanel" aria-labelledby="list-tab" data-target-group="groups">

    <ul class="row list-unstyled products-group prodcut-list-view-small no-gutters">
        <?php foreach ($products as $product): ?>
            <?php
            $_badges     = [1 => 'НОВО', 2 => 'ОЧАКВАН', 4 => 'ФИКСИРАНА ЦЕНА'];
            $_badgeClass = match ($product['badge_index']) {
                '1' => 'danger',
                '2' => 'warning',
                default => ''
            };

            $_basePrice = $product['cenaKKC'] ?? 0;
            $_percent   = null;
            $_price     = $_basePrice;

            $_basePrice = $product[$productPriceLevel] ?? $_basePrice;

            $_promoPercent = getPromoPercentFromGensoftJson($product['gensoft_json'] ?? null);
            $_promoPrice   = calculatePromoPrice($_basePrice, $_promoPercent);
            if ($_promoPrice !== null) {
                $_price   = $_promoPrice;
                $_percent = abs((float) $_promoPercent);
            } else {
                $_price = $_basePrice;
            }
            ?>

            <li class="col-12 mb-4">
                <div class="js-product-item css-product-item product-item h-100" data-id="<?= $product['product_id'] ?>">
                    <div class="product-item__outer w-100">
                        <div class="css-product-item__inner px-xl-4 p-3">
                            <div class="row align-items-stretch">
                                <div class="col-12 col-md-4 col-lg-3 mb-3 mb-md-0">
                                    <figure class="mb-0 position-relative overflow-hidden h-100">
                                        <div class="product-badge-stack position-absolute">
                                            <?php if ($_percent !== null): ?>
                                                <span class="badge badge-warning rounded-top-pill rounded-bottom-left-pill font-size-15">
                                                    <small class="fw-600">- <?= $_percent ?? '' ?> %</small>
                                                </span>
                                            <?php endif; ?>

                                            <?php if (!empty($_badges[$product['badge_index']] ?? '')): ?>
                                                <span class="badge badge-<?= $_badgeClass ?> rounded-top-pill rounded-bottom-left-pill font-size-15" style="<?= $product['badge_index'] == 4 ? 'background: #e697fd;' : '' ?>">
                                                    <?= $_badges[$product['badge_index']] ?? '' ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <a class="d-block text-center img-fancybox1 h-100" href="<?= site_url('product/' . $product['product_id']) ?>">
                                            <img class="lazy img-fluid product-image-height1 h-100 w-100" src="<?= route_to('ResizeImage-thumb') ?>?img=<?= $product['image'] ?>" onerror="this.onerror=null;this.src='<?= $_ENV['app.noImage'] ?>';" alt="...">
                                        </a>
                                        <button
                                            type="button"
                                            class="btn btn-sm rounded-3 h5 quickview-btn <?= ISMOBILE ? 'px-0 py-1 font-size-1' : '' ?>"
                                            data-quick-url="<?= site_url('product/quick-view/' . $product['product_id']) ?>"
                                            aria-label="Бърз преглед">
                                            <i class="fas fa-search mr-1"></i> Бърз преглед
                                        </button>
                                    </figure>
                                </div>

                                <div class="col-12 col-md-5 col-lg-6 d-flex flex-column">
                                    <div class="border-top pt-2 flex-center-between flex-wrap"></div>

                                    <h5 class="mb-1 product-item__title">
                                        <a href="<?= site_url('product/' . $product['product_id']) ?>" class="text-dark font-weight-bold-600 text-start lh-2 h4" style="<?= $_sizeTitleProduct ?>"><?= $product['product_name'] ?></a>
                                    </h5>

                                    <?php if (!empty($product['additional_models'])): ?>
                                        <div class="mb-2">
                                            <span class="text-gray-9 font-size-14">Съвместимост с модели:</span>
                                            <b> <?= $product['additional_models'] ?></b>
                                        </div>
                                    <?php endif; ?>

                                    <div class="text-gray-9 font-size-14 mb-2">Наличност:
                                        <?php
                                        $nalichClass = 'red';
                                        $nalichTxt   = 'не';

                                        if ($product['nalichnost'] > 0) {
                                            $nalichClass = 'green';
                                            $nalichTxt   = 'да';
                                        }
                                        ?>
                                        <span class="text-<?= $nalichClass ?> font-weight-bold"><?= $nalichTxt ?></span>
                                    </div>

                                    <?php
                                    $_desc = $product['short_description'] ?? '';
                                    $_descLabel = '';
                                    if (!empty($_desc)) {
                                        $_descLabel = 'Кратко описание:';
                                    } elseif (!empty($product['description'])) {
                                        $_desc = $product['description'];
                                        $_descLabel = ''; // без допълнително заглавие, за да не се дублира
                                    }
                                    ?>
                                    <?php if (!empty($_desc)): ?>
                                        <div class="text-gray-9 font-size-14">
                                            <?php if (!empty($_descLabel)): ?>
                                                <span class="d-block"><?= $_descLabel ?></span>
                                            <?php endif; ?>
                                            <?= $_desc ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                             <div class="col-12 col-md-3 d-flex flex-column justify-content-center">
                                    <div class="mb-2">
        <div class="prodcut-price d-flex align-items-center justify-content-center">
            <?php if ($_percent !== null): ?>
                <div class="css-old-price text-gray font-size-15 mr-2">
                    <?= preg_replace('/\\.([0-9]*)/', '<sup class="font-size-12">.$1</sup>', number_format($_basePrice, 2)) ?>
                    <small><?= get_valuta() ?></small>
              </div>
            <?php endif ?>

            <div class="text-center font-size-24 text-red fw-bold <?= ($_percent !== null) ? 'text-danger' : '' ?>">
                <?= preg_replace('/\\.([0-9]*)/', '<sup class="font-size-14">.$1</sup>', number_format($_price, 2)) ?>
                <small class="fw-bold"><?= get_valuta() ?></small>
                <div class="ml-3 text-gray-100 font-size-24 fw-bold">
                    <?= priceToEur($_price) ?>
                </div>
            </div>
        </div>
                                    </div>

                                    <div class="mb-1 d-flex">
                                        <?php if ($customConfig->btn['showCart']): ?>
                                            <div class="w-100 prodcut-add-cart">
                                                <?php if ($product['nalichnost'] > 0 && $_price > 0): ?>
                                                    <div class="input-group mb-1 prodcut-add-cart">
                                                        <div class="css-add-to-cart-quantity col height-40 py-2 px-3">
                                                            <div class="js-quantity d-flex align-items-center">
                                                                <div class="">
                                                                    <input class="js-result text-dark  css-add-to-cart-quantity-input form-control h-auto border-0 rounded p-0 shadow-none text-center" type="text" value="1" autocomplete="off">
                                                                </div>

                                                                <div class="col-auto px-1">
                                                                    <a class="js-minus text-dark  btn btn-icon btn-xs btn-outline-secondary rounded-circle border-0" href="javascript:;">
                                                                        <small class="fas fa-minus btn-icon__inner"></small>
                                                                    </a>

                                                                    <a class="js-plus text-dark  btn btn-icon btn-xs btn-outline-secondary rounded-circle border-0" href="javascript:;">
                                                                        <small class="fas fa-plus btn-icon__inner"></small>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <a class="css-btn-add-cart btn-add-cart text-white btn-primary height-40 transition-3d-hover1" data-product-id="<?= $product['product_id'] ?>" data-route="<?= route_to('Cart-addToCart') ?>" href="javascript:;">
                                                            <i class="ec ec-add-to-cart mr-2"></i> Добави
                                                        </a>
                                                    </div>

                                                <?php else: ?>
                                                    <a class="m-auto btn-add-cart transition-3d-hover" style="background: #fff;color: #ee1a19; width: 100%; border: 1px solid #ee1a19; border-radius: 3px;" title="За запитване <?= $_ENV['app.phoneRequest'] ?>">
                                                        Няма наличност
                                                    </a>
                                                <?php endif ?>
                                            </div>
                                        <?php endif ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        <?php endforeach ?>
    </ul>
</div>

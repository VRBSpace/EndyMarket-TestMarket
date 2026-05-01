<?= view("{$_ENV['app.theme']}/layouts/header/VIEW__head") ?>
<?= view("{$_ENV['app.theme']}/layouts/header/VIEW__header") ?>
<?php

helper('price');
// Рендер на продуктова карта за всички слайдери на страницата (един източник на markup)
$renderProductCard = function ($product) use ($productPriceLevel, $customConfig, $_sizeTitleProduct) {
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
    $_displayBasePrice = $_basePrice;
    $_displayPrice     = $_price;

    ob_start();
    ?>
    <div class="js-product-item css-product-item product-item mb-4 h-100" data-id="<?= $product['product_id'] ?>">
        <div class="product-item__outer w-100">
            <div class="css-product-item__inner px-xl-4 p-3">
                <div class="product-item__body pb-xl-2">

                    <figure class="mb-2 position-relative overflow-hidden">
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

                        <a class="d-block text-center img-fancybox1" href="<?= site_url('product/' . $product['product_id']) ?>">
                            <img class="lazy img-fluid product-image-height1 h-100 w-100" src="<?= route_to('ResizeImage-thumb') ?>?img=<?= $product['image'] ?>" loading="lazy" decoding="async" onerror="this.onerror=null;this.src='<?= $_ENV['app.noImage'] ?>';" alt="...">
                        </a>
                        <button
                            type="button"
                            class="btn btn-sm rounded-3 h5 quickview-btn <?= ISMOBILE ? 'px-0 py-1 font-size-1' : '' ?>"
                            data-quick-url="<?= site_url('product/quick-view/' . $product['product_id']) ?>"
                            aria-label="Бърз преглед">
                            <i class="fas fa-search mr-1"></i> Бърз преглед
                        </button>
                    </figure>

                    <div class="border-top pt-2 flex-center-between flex-wrap"></div>

                    <h5 class="mb-1 product-item__title">
                        <a href="<?= site_url('product/' . $product['product_id']) ?>" class="text-dark font-weight-bold-600 text-start lh-2 h4" style="<?= $_sizeTitleProduct ?>"><?= $product['product_name'] ?></a>
                    </h5>

                    <div class="mb-2">
                        <div class="prodcut-price d-flex">
                                <?php if ($_percent !== null): ?>
                                <div class="css-old-price text-gray font-size-15 mr-2">
        <?= preg_replace('/\\.([0-9]*)/', '<sup class="font-size-12">.$1</sup>', number_format($_displayBasePrice, 2)) ?>
                                    <small><?= get_valuta() ?></small>
                                    <!-- <small class="discount">- <?= $_percent ?? '' ?> %</small> -->
                                </div>
                                <?php endif ?>

                            <div class="text-center font-size-16 text-red fw-bold <?= ($_percent !== null) ? 'text-danger' : '' ?>">
    <?= preg_replace('/\\.([0-9]*)/', '<sup class="font-size-14">.$1</sup>', number_format($_displayPrice, 2)) ?>
                                <small class="fw-bold"><?= get_valuta() ?></small>
                                <span class="font-size-13 text-gray-100 fw-500"> <?= '/ ' . priceToEur2($_displayPrice) ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-1 d-flex">
                            <?php if ($customConfig -> btn['showCart']): ?>
                            <div class="<?= ISMOBILE ? '' : 'd-none' ?> w-100 d-xl-block prodcut-add-cart">
        <?php if ($product['nalichnost'] > 0 && $_price > 0): ?>
                                    <div class=" input-group mb-1 prodcut-add-cart">
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
                                        <!-- <i class="ec ec-phone"></i> -->
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
    <?php

    return ob_get_clean();
};
?>

<!-- ========== MAIN CONTENT ========== -->
<main class="container-fluid" role="main">

    <!-- HERO + BANNERS -->
    <div class="container py-3">
        <div class="<?= ISMOBILE ? 'd-flex flex-column' : 'd-flex flex-row flex-nowrap align-items-stretch' ?> home-hero-wrapper">
            <div class="<?= ISMOBILE ? 'w-100 mb-3' : 'flex-grow-1' ?>">
                <div class="home-ratio home-ratio-16x9">
                    <div class="home-ratio__inner h-100">
                        <!-- Slideshow -->
<?= view($views['slideshow']) ?>
                        <!-- End Slideshow -->
                    </div>
                </div>
            </div>
            <div class="<?= ISMOBILE ? 'w-100' : 'home-hero-banners' ?>">
                <div class="home-banners-stack d-flex flex-column">
                    <!-- BANNERS FROM OFERTABG -->
<?= view($views['banersBlock1']) ?>
                    <!-- END BANNERS FROM OFERTABG -->
                </div>
            </div>
        </div>
    </div>
    <!-- END HERO + BANNERS -->

    <!-- Category Filter Container -->
    <div class="row py-4" style="background-image: url('https://fullkit.moxcreative.com/otoplaza/wp-content/uploads/sites/28/2023/08/white-painted-wall-texture-background-1.jpg');">
        <?php

        // if (ISMOBILE) {
        //     return;
        // }
        ?>

        <div class="container d-flex flex-column gap-3">
            <div class="d-flex align-items-center justify-content-between">
<?php if (!empty($categoryBannerItems ?? [])): ?>
                    <div class="category-banners-scroller <?= ISMOBILE ? 'mt-3' : '' ?>">
    <?php foreach (($categoryBannerItems ?? []) as $_b): ?>
                            <div class="category-banner-item">
                                <div class="category-banner-thumb position-relative">
                                    <img class="img-fluid rounded-8 w-100 h-100" src="<?= $_b['src'] ?>" loading="lazy" decoding="async" alt="" style="object-fit: cover;">
                                    <a href="<?= $_b['link'] ?>" class="position-absolute w-100 h-100" style="top:0;left:0;"></a>
                                </div>
                            </div>
    <?php endforeach; ?>
                    </div>
<?php endif; ?>
            </div>
        </div>
    </div>
    <!-- END Category Filter Container -->

    <!-- PROMO PRODUCTS (Намалени продукти) -->
    <section class="py-5 bg-light position-relative overflow-hidden">
        <div class="container d-flex flex-column <?= ISMOBILE ? 'p-0' : '' ?>">
            <div class="heading-container-slider d-flex align-items-center justify-content-between mb-3">
                <h2 class="fw-bold h4 mb-0 text-dark heading-slider <?= ISMOBILE ? 'font-size-16' : '' ?>">Намалени продукти</h2>
            </div>
            <div id="js-promo-products-grid" class="tab-pane fade pt-2 show active" role="tabpanel" aria-labelledby="pills-promo-example1-tab" data-target-group="groups">
                <div
                    class="js-slick-carousel u-slick new-products-slider"
                    data-arrows-classes="d-none d-xl-block u-slick__arrow-normal u-slick__arrow-centered--y rounded-circle text-black font-size-30 z-index-2"
                    data-arrow-left-classes="fa fa-angle-left u-slick__arrow-inner--left left-n16"
                    data-arrow-right-classes="fa fa-angle-right u-slick__arrow-inner--right right-n20"
                    data-pagi-classes="text-center u-slick__pagination u-slick__pagination--long mt-2"
                    data-slides-show="5"
                    data-slides-scroll="5"
                    data-responsive='[{"breakpoint": 1400, "settings": { "slidesToShow": 4, "slidesToScroll": 4 }}, {"breakpoint": 1200, "settings": { "slidesToShow": 4, "slidesToScroll": 2 }}, {"breakpoint": 992, "settings": { "slidesToShow": 3, "slidesToScroll": 2 }}, {"breakpoint": 768, "settings": { "slidesToShow": 2, "slidesToScroll": 2 }}, {"breakpoint": 554, "settings": { "slidesToShow": 2, "slidesToScroll": 1 }}, {"breakpoint": 480, "settings": { "slidesToShow": 2, "slidesToScroll": 1 }}]'>
                    <?php foreach ($discountProducts as $k => $product): ?>

                        <div class="js-slide h-100">
    <?= $renderProductCard($product) ?>
                        </div>
<?php endforeach ?>
                </div>
            </div>
        </div>
    </section>
    <!-- END PROMO PRODUCTS -->

    <!-- QUICK VIEW PRODUCT -->
    <div class="modal fade rounded-8" id="quickViewModal" tabindex="-1" role="dialog" aria-labelledby="quickViewLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h5 class="modal-title" id="quickViewLabel">Бърз преглед</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Затвори">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="quickViewBody" class="modal-body">
                    <div class="p-5 text-center" id="quickViewSpinner">
                        <div class="spinner-border" role="status"></div>
                        <div class="mt-3">Зареждане…</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END QUICK VIEW PRODUCT -->

    <!-- SERVICE HIGHLIGHTS -->
    <?php
    $_settingsOrder           = $settings_portal['order']['order'] ?? [];
    $_freeShippingLimit       = (float) ($_settingsOrder['freeDostavkaPrice'] ?? 0);
    $_freeShippingLimitLabel  = $_freeShippingLimit > 0 ? rtrim(rtrim(number_format($_freeShippingLimit, 2, '.', ''), '0'), '.') . ' ' . get_valuta() : null;
    $_freeShippingSecondLabel = $_freeShippingLimit > 0 ? priceToEur2($_freeShippingLimit) : null;

    $_serviceCards = [
        [
            'icon'  => 'fa-shipping-fast',
            'title' => 'Безплатна доставка',
            'text'  => $_freeShippingLimitLabel
                ? 'за поръчки над ' . $_freeShippingLimitLabel . ($_freeShippingSecondLabel ? ' (' . $_freeShippingSecondLabel . ')' : '')
                : 'за поръчки над зададения лимит',
        ],
        [
            'icon'  => 'fa-shield-alt',
            'title' => '1 година гаранция',
            'text'  => 'от ЕндиМаркет',
        ],
        [
            'icon'  => 'fa-exchange-alt',
            'title' => 'Връщане или замяна',
            'text'  => 'на продукт до 14 дни',
        ],
        [
            'icon'  => 'fa-box-open',
            'title' => 'Преглед и тест',
            'text'  => 'при доставка',
        ],
    ];
    ?>
    <section class="css-counter-section py-3 py-md-5 bg-light">
        <div class="container">
            <div class="row">
                <?php foreach ($_serviceCards as $_card): ?>
                    <div class="col-12 col-sm-6 col-xl-3 mb-3 mb-xl-0">
                        <div class="h-100 bg-white rounded-lg shadow-sm border-0 d-flex align-items-center p-3 p-lg-4 text-left position-relative overflow-hidden" style="min-height: 138px;">
                            <span class="position-absolute" style="top: 0; left: 0; width: 100%; height: 4px; background: linear-gradient(90deg, #ee1a19 0%, #ff8a5b 100%);"></span>
                            <div class="flex-shrink-0 mr-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 58px; height: 58px; background: rgba(238, 26, 25, 0.08); color: #ee1a19;">
                                <i class="fas <?= $_card['icon'] ?>" style="font-size: 24px;"></i>
                            </div>
                            <div class="min-width-0">
                                <div class="text-uppercase font-weight-bold text-dark h5 mb-1" style="line-height: 1.15; letter-spacing: 0.02em;">
                                    <?= $_card['title'] ?>
                                </div>
                               <div style="line-height: 1.35; font-size: 16px; color: #3f4654;">
                                    <?= $_card['text'] ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <!-- END SERVICE HIGHLIGHTS -->

    <!-- NEW PRODUCTS -->
    <section class="py-5 bg-light position-relative overflow-hidden">
        <div class="container d-flex flex-column <?= ISMOBILE ? 'p-0' : '' ?>">
            <div class="heading-container-slider d-flex align-items-center justify-content-between mb-3">
                <h2 class="fw-bold h4 mb-0 text-dark heading-slider <?= ISMOBILE ? 'font-size-16' : '' ?>">Нови продукти</h2>
            </div>
            <div id="js-products-grid" class="tab-pane fade pt-2 show active" role="tabpanel" aria-labelledby="pills-one-example1-tab" data-target-group="groups">
                <div
                    class="js-slick-carousel u-slick new-products-slider"
                    data-arrows-classes="d-none d-xl-block u-slick__arrow-normal u-slick__arrow-centered--y rounded-circle text-black font-size-30 z-index-2"
                    data-arrow-left-classes="fa fa-angle-left u-slick__arrow-inner--left left-n16"
                    data-arrow-right-classes="fa fa-angle-right u-slick__arrow-inner--right right-n20"
                    data-pagi-classes="text-center u-slick__pagination u-slick__pagination--long mt-2"
                    data-slides-show="5"
                    data-slides-scroll="5"
                    data-responsive='[{"breakpoint": 1400, "settings": { "slidesToShow": 4, "slidesToScroll": 4 }}, {"breakpoint": 1200, "settings": { "slidesToShow": 4, "slidesToScroll": 2 }}, {"breakpoint": 992, "settings": { "slidesToShow": 3, "slidesToScroll": 2 }}, {"breakpoint": 768, "settings": { "slidesToShow": 2, "slidesToScroll": 2 }}, {"breakpoint": 554, "settings": { "slidesToShow": 2, "slidesToScroll": 1 }}, {"breakpoint": 480, "settings": { "slidesToShow": 2, "slidesToScroll": 1 }}]'>
                    <?php foreach ($latestProducts as $k => $product): ?>

                        <div class="js-slide h-100">
    <?= $renderProductCard($product) ?>
                        </div>
<?php endforeach ?>
                </div>
            </div>
        </div>
    </section>
    <!-- END NEW PRODUCTS -->

                <?php foreach (($categorySections ?? []) as $_section): ?>
        <section class="<?= ISMOBILE ? 'py-1' : 'py-5' ?> bg-light position-relative overflow-hidden">
            <div class="container d-flex flex-column <?= ISMOBILE ? 'p-0' : '' ?>">
                <div class="heading-container-slider d-flex align-items-center justify-content-between mb-3">
                    <h2 class="fw-bold h4 mb-0 text-dark heading-slider <?= ISMOBILE ? 'font-size-16' : '' ?>"><?= $_section['title'] ?></h2>
                    <?php if (!empty($_section['categoryId'])): ?>
                        <a class="d-inline-flex align-items-center text-dark" href="/shop/?categoryId=<?= $_section['categoryId'] ?>">
                            <svg class="mb-0" width="10" height="auto" viewBox="0 0 11 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0.429687 1.01563C0.143229 1.30208 0 1.6276 0 1.99219C0 2.35677 0.143229 2.68229 0.429687 2.96874L7.46094 10L0.429687 17.0313C0.143229 17.3177 0 17.6433 0 18.0079C0 18.3724 0.143229 18.6914 0.429687 18.9649C0.716146 19.2383 1.04167 19.375 1.40625 19.375C1.77083 19.375 2.08333 19.2317 2.34374 18.9453L10.3516 10.9766C10.4818 10.8464 10.5859 10.6901 10.6641 10.5078C10.7422 10.3255 10.7813 10.1563 10.7813 10C10.7813 9.84374 10.7422 9.67449 10.6641 9.49219C10.5859 9.3099 10.4818 9.15364 10.3516 9.02344L2.34374 1.01563C2.08333 0.755209 1.77083 0.625 1.40625 0.625C1.04167 0.625 0.716146 0.755209 0.429687 1.01563Z" fill="black"></path>
                            </svg>
                        </a>
    <?php endif; ?>
                </div>
                <div class="tab-pane fade pt-2 show active" role="tabpanel" data-target-group="groups">
    <?php $_items = $categorySectionItems[$_section['key']] ?? []; ?>

                    <div
                        class="js-slick-carousel u-slick new-products-slider"
                        data-arrows-classes="d-none d-xl-block u-slick__arrow-normal u-slick__arrow-centered--y rounded-circle text-black font-size-30 z-index-2"
                        data-arrow-left-classes="fa fa-angle-left u-slick__arrow-inner--left left-n16"
                        data-arrow-right-classes="fa fa-angle-right u-slick__arrow-inner--right right-n20"
                        data-pagi-classes="text-center u-slick__pagination u-slick__pagination--long mt-2"
                        data-slides-show="5"
                        data-slides-scroll="5"
                        data-responsive='[{"breakpoint": 1400, "settings": { "slidesToShow": 4, "slidesToScroll": 4 }}, {"breakpoint": 1200, "settings": { "slidesToShow": 4, "slidesToScroll": 2 }}, {"breakpoint": 992, "settings": { "slidesToShow": 3, "slidesToScroll": 2 }}, {"breakpoint": 768, "settings": { "slidesToShow": 2, "slidesToScroll": 2 }}, {"breakpoint": 554, "settings": { "slidesToShow": 2, "slidesToScroll": 1 }}, {"breakpoint": 480, "settings": { "slidesToShow": 2, "slidesToScroll": 1 }}]'>
                        <?php foreach ($_items as $k => $product): ?>

                            <div class="js-slide h-100">
        <?= $renderProductCard($product) ?>
                            </div>
        <?php endforeach ?>
                    </div>
                </div>
            </div>
        </section>
<?php endforeach ?>

    <!-- ABOUT / LOCATION SECTION -->
    <section class="py-5 bg-white about-section">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
                <div>
                    <p class="h5 text-red-theme fw-bold mb-1">За нас</p>
                    <h2 class="h3 fw-bold text-dark mb-0">Енди Маркет</h2>
                </div>
            </div>

            <div class="row g-4 align-items-stretch">
                <div class="col-lg-6 d-flex flex-column gap-3">
                    <div class="about-card p-4">
                        <p class="mb-4 text-dark about-text fw-300">
                            Вашият надежден партньор за дома и строителството – създадени с мисъл за удобството на клиентите, за да намерите всичко на едно място и да улесним ежедневието и проектите ви.
                        </p>
                    </div>

                    <div class="about-map rounded overflow-hidden shadow-sm">
                        <div class="about-map__frame">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2962.238503517078!2d25.587828400000003!3d42.059506899999995!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40a801f03383bc2b%3A0x8a84c695c4a83274!2z0JXQvdC00Lgg0LzQsNGA0LrQtdGC!5e0!3m2!1sbg!2sbg!4v1724929419713!5m2!1sbg!2sbg"
                                width="100%" 
                                height="450" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade"
                                title="Карта на Енди Маркет">
                            </iframe>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 d-flex flex-column gap-3">
                    <div class="about-image position-relative rounded overflow-hidden shadow-sm h-100">
                        <img src="<?= rtrim($_ENV['app.imagePortalDir'], '/') ?>/baners/endy-market-team.webp" class=" w-100 h-100 about-image-main" alt="Енди Маркет екип">
                    </div>
                    <div class="about-note bg-light rounded shadow-sm p-4">
                        <p class="mb-1 h5 text-blue-theme fw-bold">Винаги до вас</p>
                        <p class="mb-0 text-dark about-text fw-300">В "Енди Маркет" се стремим да предложим висок стандарт на обслужване и продукти, които отговарят на всички нужди и очаквания.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END ABOUT / LOCATION SECTION -->




</main>

<?php

echo view("{$_ENV['app.theme']}/layouts/sidebar/VIEW__sidebar-account"); // Account Sidebar Navigation
echo view("{$_ENV['app.theme']}/layouts/footer/VIEW__footer");
?>

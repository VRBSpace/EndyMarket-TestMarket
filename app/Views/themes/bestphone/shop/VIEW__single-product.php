<?= view("{$_ENV['app.theme']}/layouts/header/VIEW__head", ['seo' => $seo]) ?>
<?= view("{$_ENV['app.theme']}/layouts/header/VIEW__header") ?>

<?php
helper('price');
// Detect product page
$isProductPage = str_contains(current_url(), '/product/');

// Generate schema if product page
if ($isProductPage) {

    // --- FALLBACK DATA FROM PRODUCT ---
    $schemaName = $product -> product_name ?? 'Продукт';
    $schemaDesc = strip_tags($product -> short_description ?? '');
    if (strlen($schemaDesc) < 10) {
        $schemaDesc = 'Купете ' . $schemaName . ' на топ цена. Бърза доставка и гарантирано качество.';
    }

    $schemaImage = !empty($product -> image) ? $_ENV['app.imageDir'] . $product -> image : base_url('uploads/no-image.jpg');

    $schemaPrice     = $product -> cenaKKC ?? '0.00';
    $schemaCanonical = current_url();

    // Optional recommended extras
    $shippingDetails = [
        "@type"               => "OfferShippingDetails",
        "shippingRate"        => [
            "@type"    => "MonetaryAmount",
            "value"    => "5.00",
            "currency" => "BGN"
        ],
        "shippingDestination" => [
            "@type"          => "DefinedRegion",
            "addressCountry" => [
                "@type" => "Country",
                "name"  => "BG"
            ]
        ],
        "deliveryTime"        => [
            "@type"        => "ShippingDeliveryTime",
            "transitTime"  => [
                "@type"    => "QuantitativeValue",
                "minValue" => 1,
                "maxValue" => 3,
                "unitCode" => "DAY"
            ],
            "businessDays" => [
                "@type"     => "OpeningHoursSpecification",
                "dayOfWeek" => [
                    "https://schema.org/Monday",
                    "https://schema.org/Tuesday",
                    "https://schema.org/Wednesday",
                    "https://schema.org/Thursday",
                    "https://schema.org/Friday"
                ]
            ],
            "handlingTime" => [
                "@type"    => "QuantitativeValue",
                "minValue" => 1,
                "maxValue" => 2,
                "unitCode" => "DAY"
            ]
        ]
    ];

    $returnPolicy = [
        "@type"                => "MerchantReturnPolicy",
        "returnPolicyCategory" => "https://schema.org/MerchantReturnFiniteReturnWindow",
        "merchantReturnDays"   => 14,
        "returnMethod"         => "https://schema.org/ReturnByMail",
        "returnFees"           => "https://schema.org/FreeReturn",
        "applicableCountry"    => "BG"
    ];

    // The schema itself
    $schemaArray = [
        "@context"    => "https://schema.org",
        "@type"       => "Product",
        "name"        => $schemaName,
        "description" => $schemaDesc,
        "image"       => $schemaImage,
        "sku"         => $product -> kod ?? '',
        "mpn"         => $product -> oem ?? '',
        "brand"       => [
            "@type" => "Brand",
            "name"  => $product -> brandTxt ?? "Brand"
        ],
        // === OPTIONAL BUT IMPORTANT FOR SEO ===
        "aggregateRating" => [
            "@type"       => "AggregateRating",
            "ratingValue" => "5",
            "reviewCount" => "1"
        ],
        "review" => [[
        "@type"        => "Review",
        "reviewRating" => [
            "@type"       => "Rating",
            "ratingValue" => 5,
            "bestRating"  => 5
        ],
        "author"       => [
            "@type" => "Person",
            "name"  => "Клиент"
        ]
            ]],
        "offers" => [
            "@type"         => "Offer",
            "priceCurrency" => "BGN",
            "price"         => $schemaPrice,
            "availability"  => "https://schema.org/InStock",
            "url"           => $schemaCanonical,
            // OPTIONAL BUT RECOMMENDED
            "priceValidUntil" => date("Y-m-d", strtotime("+1 year")),
            "shippingDetails"         => $shippingDetails,
            "hasMerchantReturnPolicy" => $returnPolicy
        ]
    ];
    ?>
    <script type="application/ld+json">
    <?= json_encode($schemaArray, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?>
    </script>
<?php } ?>

<?php
$_w         = ISMOBILE ? 'w-50' : '';
$_brojBlock = <<<HTML
         <div class="border py-1 px-3 $_w">
           <div class="js-quantity d-flex align-items-center">
                <div>
                    <!-- <input class="js-result form-control h-auto border-0 rounded p-0 shadow-none text-center" type="text" value="1"> -->
                             <input
          class="js-result form-control h-auto border-0 rounded p-0 shadow-none text-center"
          type="number" min="1" step="1" value="1"
          name="qty" form="form_fastOrder" id="qty_ui">
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

// Рендер на продуктова карта – същият дизайн като на слайдерите от началната страница
$_sizeTitleProduct = $_sizeTitleProduct ?? '';
$renderProductCard = function ($product) use ($productPriceLevel, $customConfig, $_sizeTitleProduct) {
    $_badges     = [1 => 'НОВО', 2 => 'ОЧАКВАН', 4 => 'ФИКСИРАНА ЦЕНА'];
    $_badgeClass = match ($product['badge_index'] ?? '') {
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
    $_displayBasePrice = get_valuta() === '€' ? ($_basePrice / 1.95583) : $_basePrice;
    $_displayPrice     = get_valuta() === '€' ? ($_price / 1.95583) : $_price;

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
                                    <small class="fw-600">- <?= $_percent ?> %</small>
                                </span>
    <?php endif; ?>

                            <?php if (!empty($_badges[$product['badge_index']] ?? '')): ?>
                                <span class="badge badge-<?= $_badgeClass ?> rounded-top-pill rounded-bottom-left-pill font-size-15" style="<?= ($product['badge_index'] ?? '') == 4 ? 'background: #e697fd;' : '' ?>">
        <?= $_badges[$product['badge_index']] ?? '' ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <a class="d-block text-center img-fancybox1" href="<?= site_url('product/' . $product['product_id']) ?>">
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

                    <div class="border-top pt-2 flex-center-between flex-wrap"></div>

                    <h5 class="mb-1 product-item__title">
                        <a href="<?= site_url('product/' . $product['product_id']) ?>" class="text-dark font-weight-bold-600 text-center lh-2 h4" style="font-size: 14px; <?= $_sizeTitleProduct ?>"><?= $product['product_name'] ?></a>
                    </h5>

                    <div class="mb-2">
                        <div class="prodcut-price d-flex">
                            <?php if ($_percent !== null): ?>
                                <div class="css-old-price text-gray font-size-15 mr-2">
        <?= preg_replace('/\\.([0-9]*)/', '<sup class="font-size-12">.$1</sup>', number_format($_displayBasePrice, 2)) ?>
                                    <small><?= get_valuta() ?></small>
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
        <?php if (($product['nalichnost'] ?? 0) > 0 && $_price > 0): ?>
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
<main id="content" role="main">

    <div class="container">
        <div class="col-xl-12 p-0">
            <div id="msg-place"></div>

            <!-- Single Product Body -->
            <div class="<?= ISMOBILE ? '' : 'mb-xl-1 mb-6' ?> ">

                <!-- PRODUCT BREADCRUMB зареждане от LIB__productBreadcrumbs -->
<?= $breadcrumbs ?>
                <!-- END NEW PRODUCT BREADCRUMB -->

                <div class="js-product-item row">
                    <div class="col-md-6 mb-4 mb-md-0 p-0">
                <?php
                // Определяне на цената на продукта
                $_basePrice = $product -> cenaKKC ?? 0;
                $_percent   = null;
                $_price     = $_basePrice;

                $_basePrice = $product -> $productPriceLevel ?? $_basePrice;

                $_promoPercent = getPromoPercentFromGensoftJson($product -> gensoft_json ?? null);
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
                        <div id="sliderSyncingNav" class="js-slick-carousel css-product-images u-slick mb-2 p-0"
                             data-infinite="false"
                             data-arrows-classes="d-none d-lg-inline-block u-slick__arrow-classic u-slick__arrow-centered--y rounded-circle"
                             data-arrow-left-classes="fas fa-arrow-left u-slick__arrow-classic-inner u-slick__arrow-classic-inner--left ml-lg-2 ml-xl-4"
                             data-arrow-right-classes="fas fa-arrow-right u-slick__arrow-classic-inner u-slick__arrow-classic-inner--right mr-lg-2 mr-xl-4"
                             data-nav-for="#sliderSyncingThumb">

                            <figure class="js-slide" style="cursor: pointer;">
                                <a class="img-fancybox" data-fancybox="product-gallery" data-options='{"loop": false}' href="<?= !is_null($product -> image) ? $_ENV['app.imageDir'] . $product -> image : '' ?>">
                                    <img class="lazy img-fluid bg-white" src="<?= !is_null($product -> image) ? $_ENV['app.imageDir'] . $product -> image : '' ?>" alt="...">
                                </a>
                            </figure>

<?php if (!empty($additional_mages)): ?>
    <?php foreach ($additional_mages as $image): ?>
                                    <div class="js-slide" style="cursor: pointer;">
                                        <a class="img-fancybox" data-fancybox="product-gallery" data-options='{"loop": false}' href="<?= $_ENV['app.imageDir'] . $image ?>">
                                            <img class="lazy img-fluid" src="<?= $_ENV['app.imageDir'] . $image ?>" alt="...">
                                        </a>
                                    </div>
                                <?php endforeach; ?>
<?php endif; ?>
                        </div>

<?php if (!empty($additional_mages)): ?>
                            <figure id="sliderSyncingThumb" class="js-slick-carousel u-slick u-slick--slider-syncing u-slick--gutters-1 u-slick--transform-off"
                                    data-infinite="false"
                                    data-slides-show="5"
                                    data-is-thumbs="true"
                                    data-nav-for="#sliderSyncingNav">

                                <picture class="js-slide" style="cursor: pointer;">
                                    <img class="lazy img-fluid" src="<?= $_ENV['app.imageDir'] . $product -> image ?>" alt="...">
                                </picture>

    <?php foreach ($additional_mages as $image): ?>
                                    <picture class="js-slide" style="cursor: pointer;">
                                        <img class="lazy img-fluid" src="<?= $_ENV['app.imageDir'] . $image ?>" alt="Image Description">
                                    </picture>
    <?php endforeach; ?>
                            </figure>
                            <?php endif; ?>
                    </div>

                    <!--                        <div class="col-md-1 d-none d-md-block">
                                                    <div class="flex-content-center h-100">
                                                        <div class="width-1 bg-1 h-100"></div>
                                                    </div>
                                                </div>-->

                    <div class="col-md-6 mb-md-6 mb-lg-0">
                        <div class="mb-2">
                            <div class=" <?= ISMOBILE ? '' : 'mb-3 pb-md-1 pb-3' ?>">
<?php
$_badges     = [1 => 'НОВО', 2 => 'ОЧАКВАН', 4 => 'ФИКСИРАНА ЦЕНА'];
$_badgeClass = match ($product -> badge_index) {
    '1' => 'danger',
    '2' => 'warning', //success
    default => ''
}
?>
                                <div class="mb-2">
                                    <span class="badge badge-<?= $_badgeClass ?> font-size-15" style="<?= $product -> badge_index == 4 ? 'background: #e697fd;' : '' ?>">
                                        <span><?= $_badges[$product -> badge_index] ?? '' ?></span>
                                </div>

                                <h1 class="text-dark text-lh-1dot2 fw-bold <?= ISMOBILE ? 'font-size-20' : 'font-size-23' ?>"><?= $product -> product_name ?>
                                </h1>

                                <div class="md-3 text-gray-9 font-size-14">Наличност:
<?php
$nalichClass = 'red';
$nalichTxt   = 'не';

if ($product -> nalichnost > 0) {
    $nalichClass = 'green';
    $nalichTxt   = 'да';
}
?>
                                    <span class="text-<?= $nalichClass ?> font-weight-bold"><?= $nalichTxt ?></span>
                                </div>

                                <div class="border-bottom mb-3 pb-md-1 pb-3">
                                </div>

<?php
$variationItems = $variation_products['results'] ?? [];
if (!empty($variationItems)):
    $buildVariationLabel = static function (array $vp): string {
        $vpAttrs = $vp['variation_attributes'] ?? [];
        $vpTextParts = [];
        foreach ($vpAttrs as $va) {
            $vpTextParts[] = trim((string) ($va['value_text'] ?? ''));
        }

        $label = implode(' / ', array_filter($vpTextParts));
        if ($label === '') {
            $label = (string) ($vp['product_name'] ?? ('Продукт #' . (int) ($vp['product_id'] ?? 0)));
        }

        return $label;
    };

    // Стабилна подредба A-Z, за да не се местят опциите при избор на различна вариация.
    $variationItemsSorted = $variationItems;
    usort($variationItemsSorted, static function ($a, $b) use ($buildVariationLabel) {
        $aLabel = mb_strtolower($buildVariationLabel((array) $a));
        $bLabel = mb_strtolower($buildVariationLabel((array) $b));

        return strcmp($aLabel, $bLabel);
    });

    $currentProductId = (int) ($product -> product_id ?? 0);
    $productsById = [];
    foreach ($variationItemsSorted as $vp) {
        $productsById[(int) ($vp['product_id'] ?? 0)] = $vp;
    }

    $currentVariation = $productsById[$currentProductId] ?? ($variationItemsSorted[0] ?? null);
    $selectedByAttr = $currentVariation['variation_attributes'] ?? [];

    $attributeGroups = [];
    foreach ($variationItemsSorted as $vp) {
        foreach (($vp['variation_attributes'] ?? []) as $attrKey => $attr) {
            if (!isset($attributeGroups[$attrKey])) {
                $attributeGroups[$attrKey] = [
                    'name' => $attr['name'] ?? $attrKey,
                    'options' => [],
                ];
            }

            $optionKey = (string) ($attr['value_id'] ?? 0);
            if (!isset($attributeGroups[$attrKey]['options'][$optionKey])) {
                $attributeGroups[$attrKey]['options'][$optionKey] = [
                    'value_id' => (int) ($attr['value_id'] ?? 0),
                    'value_text' => $attr['value_text'] ?? '',
                    'products' => [],
                ];
            }

            $attributeGroups[$attrKey]['options'][$optionKey]['products'][] = (int) ($vp['product_id'] ?? 0);
        }
    }

    uasort($attributeGroups, static function ($a, $b) {
        $aName = mb_strtolower((string) ($a['name'] ?? ''));
        $bName = mb_strtolower((string) ($b['name'] ?? ''));

        return strcmp($aName, $bName);
    });

    foreach ($attributeGroups as &$group) {
        uasort($group['options'], static function ($a, $b) {
            $aText = mb_strtolower((string) ($a['value_text'] ?? ''));
            $bText = mb_strtolower((string) ($b['value_text'] ?? ''));

            return strcmp($aText, $bText);
        });
    }
    unset($group);

    $findCandidateForOption = function ($targetAttrKey, $targetValueId) use ($variationItemsSorted, $selectedByAttr) {
        $firstMatch = null;
        $inStockMatch = null;

        foreach ($variationItemsSorted as $candidate) {
            $candidateAttrs = $candidate['variation_attributes'] ?? [];
            if (!isset($candidateAttrs[$targetAttrKey])) {
                continue;
            }

            if ((int) ($candidateAttrs[$targetAttrKey]['value_id'] ?? 0) !== (int) $targetValueId) {
                continue;
            }

            $isMatch = true;
            foreach ($selectedByAttr as $key => $selectedAttr) {
                if ($key === $targetAttrKey) {
                    continue;
                }

                if (!isset($candidateAttrs[$key])) {
                    $isMatch = false;
                    break;
                }

                if ((int) ($candidateAttrs[$key]['value_id'] ?? 0) !== (int) ($selectedAttr['value_id'] ?? 0)) {
                    $isMatch = false;
                    break;
                }
            }

            if (!$isMatch) {
                continue;
            }

            if ($firstMatch === null) {
                $firstMatch = $candidate;
            }

            if ((int) ($candidate['nalichnost'] ?? 0) > 0) {
                $inStockMatch = $candidate;
                break;
            }
        }

        return $inStockMatch ?? $firstMatch;
    };
?>
                                <section class="css-variation-switcher mb-3">
                                    <?php foreach ($attributeGroups as $attrKey => $group): ?>
                                        <?php
                                        $selectedAttr = $selectedByAttr[$attrKey] ?? null;
                                        $selectedText = $selectedAttr['value_text'] ?? '';
                                        ?>
                                        <div class="css-variation-group mb-2">
                                            <div class="css-variation-group-title">
                                                Избери <?= mb_strtolower($group['name'] ?? $attrKey) ?>:
                                            </div>

                                            <div class="css-variation-options">
                                                <?php foreach (($group['options'] ?? []) as $option): ?>
                                                    <?php
                                                    $targetProduct = $findCandidateForOption($attrKey, (int) ($option['value_id'] ?? 0));
                                                    $targetProductId = (int) ($targetProduct['product_id'] ?? 0);
                                                    $isCurrent = $targetProductId === $currentProductId;
                                                    $hasCandidate = $targetProductId > 0;
                                                    $isOutOfStock = $hasCandidate && (int) ($targetProduct['nalichnost'] ?? 0) <= 0;

                                                    $optionClasses = 'css-variation-option';
                                                    if ($isCurrent) {
                                                        $optionClasses .= ' is-selected';
                                                    }
                                                    if (!$hasCandidate) {
                                                        $optionClasses .= ' is-disabled';
                                                    } elseif ($isOutOfStock) {
                                                        $optionClasses .= ' is-out';
                                                    }
                                                    ?>

                                                    <?php if ($hasCandidate): ?>
                                                        <a
                                                            href="<?= site_url('product/' . $targetProductId) ?>"
                                                            class="<?= $optionClasses ?>">
                                                            <?= esc($option['value_text'] ?? '') ?>
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="<?= $optionClasses ?>">
                                                            <?= esc($option['value_text'] ?? '') ?>
                                                        </span>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>

                                    <div class="css-variation-image-options">
                                        <?php foreach ($variationItemsSorted as $vp): ?>
                                            <?php
                                            $vpId = (int) ($vp['product_id'] ?? 0);
                                            $vpAttrs = $vp['variation_attributes'] ?? [];
                                            uasort($vpAttrs, static function ($a, $b) {
                                                $aName = mb_strtolower((string) ($a['name'] ?? ''));
                                                $bName = mb_strtolower((string) ($b['name'] ?? ''));

                                                return strcmp($aName, $bName);
                                            });

                                            $tooltipLines = [];
                                            foreach ($vpAttrs as $attr) {
                                                $attrName = trim((string) ($attr['name'] ?? ''));
                                                $attrVal = trim((string) ($attr['value_text'] ?? ''));
                                                if ($attrName === '' || $attrVal === '') {
                                                    continue;
                                                }

                                                $tooltipLines[] = mb_strtolower($attrName) . ': ' . $attrVal;
                                            }
                                            $tooltipText = implode("\n", $tooltipLines);

                                            $thumbClass = 'css-variation-image-option';
                                            if ($vpId === $currentProductId) {
                                                $thumbClass .= ' is-current';
                                            } elseif ((int) ($vp['nalichnost'] ?? 0) <= 0) {
                                                $thumbClass .= ' is-out';
                                            }
                                            $thumbSrc = !empty($vp['image'])
                                                ? route_to('ResizeImage-thumb') . '?img=' . $vp['image']
                                                : ($_ENV['app.noImage'] ?? '');
                                            ?>
                                            <a
                                                href="<?= site_url('product/' . $vpId) ?>"
                                                class="<?= $thumbClass ?>"
                                                data-variation-tooltip="<?= esc($tooltipText, 'attr') ?>">
                                                <span class="css-variation-image-frame">
                                                    <img src="<?= $thumbSrc ?>" alt="<?= esc($vp['product_name'] ?? 'Вариация') ?>">
                                                </span>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </section>
<?php endif; ?>

<?php if (!empty($product -> short_description)): ?>
                                    <div class="my-2">
                                        <span>Кратко описание:</span>
                                        <br>
    <?= $product -> short_description ?>
                                    </div>
                                <?php endif ?>

                                <!-- <div class="mb-2">
                                        <a class="d-inline-flex align-items-center small font-size-15 text-lh-1" href="#">
                                        </a>
                                    </div> -->

                                <div class="d-flex flex-row justify-content-start <?= ISMOBILE ? 'align-items-start flex-column gap-px' : 'align-items-center' ?>">
                                    <div class="<?= ISMOBILE ? '' : 'mb-4 mr-6' ?>">
                                        <div class="d-flex align-items-start flex-column <?= ISMOBILE ? 'justify-content-around' : '' ?>">
                                            <!-- Показване на новата цена -->
<ins class="font-size-36 text-decoration-none fw-bold mr-4 <?= $_percent !== null ? 'text-danger' : '' ?>">

<?= preg_replace('/\.([0-9]*)/', '<sup>.$1</sup>', number_format($_price, 2)) ?>
                                                <small class="font-size-20 fw-bold"><?= get_valuta() ?></small>
                                                <?= '/ ' . priceToEur2($_price) ?>
                                            </ins>

                                            <!-- Показване на старата цена -->
                                                <?php if ($_percent !== null): ?>
                                                <div class="css-old-price text-gray-100 font-size-18">
    <?= preg_replace('/\.([0-9]*)/', '<sup class="font-size-12">.$1</sup>', number_format($_basePrice, 2)) ?>
                                                    <small><?= get_valuta() ?></small>
                                                    <small class="ml-1">(- <?= $_percent ?? '' ?> %)</small>
                                                </div>
                                            <?php endif ?>
                                            <div class="vat-status">Цените са <?= $productPriceLevel == 'cenaKKC' ? 'с' : 'без' ?> ДДС</div>
                                        </div>
                                    </div>

                                            <?php if ($customConfig -> btn['showCart']): ?>
                                        <div class="d-md-flex align-items-end mb-3 <?= ISMOBILE ? 'w-100 mt-3' : 'w-40' ?>">
    <?php $_isPrice = $_price ?>

    <?php if (!empty($_isPrice) && $product -> nalichnost > 0): ?>
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
                                                            data-product-id="<?= $product -> product_id ?>"
                                                            href="javascript:;">
                                                            <i class="ec ec-add-to-cart mr-2"></i> Купи
                                                        </a>
                                                    </div>
                                                </div>
    <?php endif ?>
                                        </div>
<?php endif ?>
                                </div>

                                <!-- Бърза поръчка ///////////////////////////////////// -->
                                <!-- --------------------------------------------------- -->
                                    <?php if ($product -> nalichnost > 0 && $customConfig -> isVisible['fastOrder']): ?>
                                    <section class="<?= ISMOBILE ? 'text-center mb-4' : 'd-flex justify-content-start mb-4' ?>">
                                        <button id="btn_fast_order" type="button" class="<?= ISMOBILE ? 'w-100 mb-4' : 'w-50' ?> btn btn-mega p-1 css-btn-secondary-v2 fast-order-trigger">БЪРЗА ПОРЪЧКА</button>

                                        <div id="fastOrder_block" style="display:none;" class="w-100">
                                            <div>
                                                <img class="w-40" src="<?= $_ENV['app.imageDataDir'] . 'eshop/logo/econtDostavka.png' ?>">

                                                <p class="m-0">Телефон за поръчки</p>
                                                <p class="m-0"><?= $settings_portal['order']['order']['fastOrderTel'] ?? ''; ?></p>
                                            </div>

                                            <form id="form_fastOrder" class="js-validate" action="<?= route_to('Checkout-fastOrder') ?>" method="post">

                                                <input name="product_id" type="hidden" value="<?= $product -> product_id ?>">

                                                <div>
                                                    <h2 class="text-center h4">Бърза поръчка</h2>
                                                    <div>
                                                        <div class="row mb-2">
                                                            <div class="js-form-message col-md-7 mb-1 px-0">
                                                                <input class="form-control rounded-3 fast-order-input" name="lice_zaKont" type="text" placeholder="Име и фамилия"
                                                                       data-msg="Задълтелно поле за име"
                                                                       data-error-class="u-has-error"
                                                                       data-success-class="u-has-success"
                                                                       required>
                                                            </div>

                                                            <div class="js-form-message col-md-5 px-0">
                                                                <input class="form-control fast-order-input" name="tel" type="tel" placeholder="Телефон"
                                                                       data-error-class="u-has-error"
                                                                       data-success-class="u-has-success"
                                                                       required>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-1">
                                                            <!-- <div class="js-form-message col-md-9 mb-1">
                                                                <input class="form-control" type="text" name="adres" placeholder="Адрес/Офис на Еконт"
                                                                    data-msg="Задълтелно поле за aдрес"
                                                                    data-error-class="u-has-error"
                                                                    data-success-class="u-has-success"
                                                                    required>
                                                            </div> -->

                                                            <!-- <div class="js-form-message col-md-3">
                                                                <input class="form-control" type="text" name="qty" placeholder="Колич."
                                                                    data-msg="Задълтелно поле за количество"
                                                                    data-error-class="u-has-error"
                                                                    data-success-class="u-has-success"
                                                                    required>
                                                            </div> -->
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="">
                                                    <h6>Коментар към поръчката (не е задължително)</h6>
                                                   <input class="form-control fast-order-input" type="text" name="belezka" autocomplete="off">
                                                </div>

                                                <div class="mt-3">
                                                    <input id="is_agree" name="is_agree" type="checkbox" value="1">
                                                    <a href="<?= route_to('Pages-uslovia') ?>" target="_blank" class="text-red">Съгласяване с общите правила и условия</a>
                                                </div>

                                                <div class="mt-2">
                                                    <input id="is_privacy_agree" name="is_privacy_agree" type="checkbox" value="1">
                                                    <a href="https://portal.testmarketbg.com/privacy-data" target="_blank" rel="noopener noreferrer" class="text-red">Съгласяване с политика за поверителност</a>
                                                </div>

                                             <div class="mt-4">
                                                    <button class="fast-order-submit w-100" type="submit">Поръчай сега</button>
                                                </div>

                                            </form>
                                        </div>
                                    </section>
<?php endif ?>
                                <!-- край Бърза поръчка ----- -->

<?php if (!empty($product -> brandTxt)): ?>
                                    <div class="mb-1">
                                        <span>Производител:</span>
                                        <b><?= $product -> brandTxt ?></b>
                                    </div>
                                <?php endif ?>

<?php if (!empty($product -> model)): ?>
                                    <div class="mb-1">
                                        <span>Модел:</span>
                                        <b><?= $product -> model ?></b>
                                    </div>
                                <?php endif ?>

<?php if (!empty($product -> additional_models)): ?>
                                    <div class="mb-1">
                                        <span>Съвместимост с модели:</span>
                                        <b><?= $product -> additional_models ?></b>
                                    </div>
                                <?php endif ?>

<?php if (!empty($product -> kod)): ?>
                                    <div class="mb-1">
                                        <span>Код:</span>
                                        <b><?= $product -> kod ?></b>
                                    </div>
                                <?php endif ?>

<?php if (!empty($product -> oem)): ?>
                                    <div class="mb-1">
                                        <span>OEM:</span>
                                        <b><?= $product -> oem ?></b>
                                    </div>
                                <?php endif ?>

<?php if (!empty($product -> upc)): ?>
                                    <div class="mb-1">
                                        <span>UPC:</span>
                                        <b><?= $product -> upc ?></b>
                                    </div>
                                <?php endif ?>

<?php if (!empty($product -> ean)): ?>
                                    <div class="mb-1">
                                        <span>EAN:</span>
                                        <b><?= $product -> ean ?></b>
                                    </div>
                                <?php endif ?>

<?php if (!empty($product -> teglo)): ?>
                                    <div class="mb-1">
                                        <span>Тегло:</span>
                                        <b><?= $product -> teglo ?> кг.</b>
                                    </div>
                                <?php endif ?>

                                <div class="d-md-flex align-items-center">
<?php if (isset($product -> img_path)) : ?>
                                        <figure>
                                            <a href="#" class="max-width-150 ml-n2 mb-2 mb-md-0 d-block">
                                                <img class="lazy img-fluid" src="<?= base_url($product -> img_path) ?>" alt="...">
                                            </a>
                                        </figure>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="border-bottom mb-3 pb-md-1 pb-3">
                            </div>

<?php
// Текущият URL
$currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
$currentUrl .= "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

// Текст за споделяне (по желание)
$shareText = "Виж този продукт!";
?>

                            <!-- Контейнер с заглавие и бутони -->
                            <div class="product-share d-flex justify-content-start align-items-center flex-wrap mb-4">
                                <p class="h6 mb-2 mb-md-0 mr-3">Сподели продукта:</p>

                                <div class="social-media-icons d-flex align-items-center position-relative" style="z-index: 2;">
                                    <a
                                        href="fb://facewebmodal/f?href=<?= urlencode($currentUrl) ?>"
                                        onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($currentUrl) ?>', '_blank'); return false;"
                                        class="orange-theme mx-2"
                                        aria-label="Сподели във Facebook"
                                        title="Сподели във Facebook">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                </div>
                            </div>



                        </div>


                    </div>
                </div>
            </div>
            <!-- End Single Product Body -->

            <!--  Single Product Description -->
            <div class="mb-3">
                <div class="container p-0">
                    <!-- при проблем с скролването при кликване на върху таб  <div class="js-scroll-nav"> -->
                    <div class="bg-white pt-4 pb-6 <?= ISMOBILE ? 'mb-0' : 'mb-6' ?> overflow-hidden">
                        <div id="Description1" class="mx-md-2">
                            <div class="position-relative <?= ISMOBILE ? '' : 'mb-4' ?>">

                                <ul class="nav nav-classic nav-tab nav-tab-lg product-tabs justify-content-xl-start mb-6 flex-nowrap flex-xl-wrap overflow-auto overflow-xl-visble pb-1 pb-xl-0 mb-n1 mb-xl-0" role="tablist">
                                    <li class="nav-item flex-shrink-0 flex-xl-shrink-1 z-index-2 m-0">
                                        <a class="nav-link active" href="#Description" data-toggle="tab" role="tab">
                                            <div class="d-md-flex justify-content-md-center align-items-md-center h6 fw-500">
                                                Описание
                                            </div>
                                        </a>
                                    </li>

<?php if (!empty($categoryAttributes)): ?>
                                        <li class="nav-item flex-shrink-0 flex-xl-shrink-1 z-index-2 m-0">
                                            <a class="nav-link" href="#Specification" data-toggle="tab" role="tab">
                                                <div class="d-md-flex justify-content-md-center align-items-md-center h6 fw-bold">
                                                    Характеристики
                                                </div>
                                            </a>
                                        </li>
<?php endif ?>
                                </ul>
                            </div>

                            <div class="tab-content">
                                <div id="Description" class="tab-pane fade active show pt-1" role="tabpanel">
                                    <?= $product -> description ?>
                                </div>

                                <div id="Specification" class="tab-pane fade pt-1" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <tbody>
<?php
$_prodChar = !empty($productCharacteristics) ? array_column($productCharacteristics, null, 'category_characteristic_id') : [];

$_sizeArr = sizeof($categoryAttributes); // размер на масива
foreach ($categoryAttributes as $k => $ca):
    $_cssClass      = ($k === 0 || $k === $_sizeArr - 1) ? 'hrv' : '';
    $_categoryValue = $ca['category_characteristic_value'] ?? '';
    $_catCharId     = $ca['category_characteristic_id'];
    $_prodCharValue = $_prodChar[$_catCharId]['product_characteristic_text'] ?? '';
    if (empty($_prodCharValue)) {
        continue;
    }
    ?>
                                                    <tr>
                                                        <td class="col-5 px-4 px-xl-5 border-top-0 border-bottom2 border-end <?= $_cssClass ?>"><?= $_categoryValue ?></td>
                                                        <td class="border-top-0 border-bottom2"><?= $_prodCharValue ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- Specification -->
                        <!--  <div class="bg-white py-4 px-xl-11 px-md-5 px-4 mb-6">
                                 <div id="Specification1" class="mx-md-2">
                                     <div class="position-relative mb-6">
                                         <ul class="nav nav-classic nav-tab nav-tab-lg product-tabs justify-content-xl-center mb-6 flex-nowrap flex-xl-wrap overflow-auto overflow-xl-visble border-lg-down-bottom-0 pb-1 pb-xl-0 mb-n1 mb-xl-0">
                                             <li class="nav-item flex-shrink-0 flex-xl-shrink-1 z-index-2">
                                                 <a class="nav-link" href="#Description">
                                                     <div class="d-md-flex justify-content-md-center align-items-md-center">
                                                         Описание
                                                     </div>
                                                 </a>
                                             </li>
                                             <li class="nav-item flex-shrink-0 flex-xl-shrink-1 z-index-2">
                                                 <a class="nav-link active" href="#Specification">
                                                     <div class="d-md-flex justify-content-md-center align-items-md-center">
                                                         Характеристики
                                                     </div>
                                                 </a>
                                             </li>
                                         </ul>
                                     </div>
                                     <div class="mx-md-12 pt-1">
                                         <div class="table-responsive">
                                             <table class="table table-hover">
                                                 <tbody>
                                                          
                                                 </tbody>
                                             </table>
                                         </div>
                                     </div>
                                 </div>
                             </div> -->
                    </div>
                </div>
            </div>
            <!-- End Single Product Description -->
        </div>

        <!-- RELATED PRODUCTS -->
<?php if (!empty($related_products)): ?>
            <section class="<?= ISMOBILE ? 'py-0' : 'py-5' ?> bg-white position-relative overflow-hidden">
                <div class="container d-flex flex-column <?= ISMOBILE ? 'p-0' : '' ?>">
                    <h2 class="fw-bold h4 mb-3 text-dark heading-slider">Свързани продукти</h2>

                    <div
                        class="js-slick-carousel u-slick new-products-slider"
                        data-arrows-classes="d-none d-xl-block u-slick__arrow-normal u-slick__arrow-centered--y rounded-circle text-black font-size-30 z-index-2"
                        data-arrow-left-classes="fa fa-angle-left u-slick__arrow-inner--left left-n16"
                        data-arrow-right-classes="fa fa-angle-right u-slick__arrow-inner--right right-n20"
                        data-pagi-classes="text-center u-slick__pagination u-slick__pagination--long mt-2"
                        data-slides-show="5"
                        data-slides-scroll="5"
                        data-responsive='[{"breakpoint": 1400, "settings": { "slidesToShow": 4, "slidesToScroll": 4 }}, {"breakpoint": 1200, "settings": { "slidesToShow": 4, "slidesToScroll": 2 }}, {"breakpoint": 992, "settings": { "slidesToShow": 3, "slidesToScroll": 2 }}, {"breakpoint": 768, "settings": { "slidesToShow": 2, "slidesToScroll": 2 }}, {"breakpoint": 554, "settings": { "slidesToShow": 1, "slidesToScroll": 1 }}]'
                        >
    <?php foreach (array_slice($related_products, 0, 10) as $rp): ?>
        <?php if ((int) ($rp['product_id'] ?? 0) === (int) $product -> product_id) continue; ?>
                            <div class="js-slide h-100">
        <?= $renderProductCard($rp) ?>
                            </div>
                            <?php endforeach; ?>
                    </div>
                </div>
            </section>
                        <?php endif; ?>

        <!-- END RELATED PRODUCTS -->
    </div>



    <!-- <div class="container-fluid"> -->
    <!-- <div class="row mb-12 justify-content-between  no-gutters"> -->


    <!-- </div> -->
    <!-- </div> -->
</main><!-- ========== END MAIN CONTENT ========== -->

<?php
echo view("{$_ENV['app.theme']}/layouts/sidebar/VIEW__sidebar-account");  // Account Sidebar Navigation
echo view("{$_ENV['app.theme']}/layouts/footer/VIEW__footer");
?>

<!-- JS Plugins Init. -->
<script>
    var PRODUCT_ID = '<?= $product -> product_id ?>';
</script>

<?php
$_unfoldTtarget             = '#basicDropdownHover';
$_unfoldTtargetId           = 'basicDropdownHover';
$_sort                      = $settings_portal['func']['sort'] ?? '';
$categories                 = $categories ?? [];
$countProductsBySubCategory = $countProductsBySubCategory ?? [];

$slugify = function ($text) {
    $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
    $text = strtolower($text);
    $text = preg_replace('~[^a-z0-9]+~', '-', $text);
    return trim($text, '-');
};

if (!empty($isCloned)) {
    $_unfoldTtarget   = '#basicDropdownHover1';
    $_unfoldTtargetId = 'basicDropdownHover1';
}
?>

<?php

function renderCategoryTree(array $categories, array $countByCat = [], string $parentSlug = '', int $level = 0): string {
    // Ограничение: максимум 3 нива (0,1,2). Ако станем level 3 → стоп.
    if ($level >= 3) {
        return '';
    }

    $html = '<ul class="level-' . $level . ' list-unstyled mb-0">';

    foreach ($categories as $c) {

        $catId       = $c['category_id'];
        $catName     = $c['category_name'];
        $slug        = $parentSlug . '/' . slugify($catName);
        $hasChildren = !empty($c['children']) && $level < 2;

        // Pre-calc image path
        $img = '';
        if (!empty($c['image_cat'])) {
            $img = '<a href="/shop/?categoryId=' . $catId . '" class="categories-flyout__img-link">'
                    . '<img src="' . $_ENV['app.imagePortalDir'] . $c['image_cat'] . '" alt="">'
                    . '</a>';
        }

        // Count only on parent levels
        $count = $countByCat[$catId] ?? '';

        // Level class
        $cls = ($level === 0) ? 'categories-flyout__item' : 'categories-flyout__subitem';

        // Start LI
        $html .= '<li class="' . $cls . '" data-level="' . $level . '" data-id="' . $catId . '">';

        // Icon
        $html .= $img;

        // Link + name
        $html .= '<a href="/shop/?categoryId=' . $catId . '" class="flex-grow-1 text-dark">'
                . htmlspecialchars($catName);

        if ($count !== '') {
            $html .= '<small class="ml-1">(' . $count . ')</small>';
        }

        $html .= '</a>';

        // Has children → render nested panel
        if ($hasChildren) {
            $html .= '<i class="fa fa-chevron-right ml-auto text-muted small"></i>';
            $html .= '<div class="categories-flyout__panel" data-level="' . ($level + 1) . '">'
                    . renderCategoryTree($c['children'], $countByCat, $slug, $level + 1)
                    . '</div>';
        }

        $html .= '</li>';
    }

    return $html . '</ul>';
}

function slugify(string $str): string {
    return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $str)));
}
?>


<section class="section2 sticky py-1 py-xl-1 bg-primary-down-lg">
    <!-- ISMOBILE съобщение за логнатия потребител -->
    <?php if (ISMOBILE): ?>
        <?php
        $_isLoggedIn = session() -> has('user_id');
        $_settingsOrder = $settings_portal['order']['order'] ?? [];
        $_freeDostRange = (float) ($_settingsOrder['freeDostavkaLeftPrice'] ?? 0);
        $_isFree = $_settingsOrder['freeDostavkaPrice'] ?? '';
        ?>

        <div class="d-none">
            <span id="js-freeDostavkaPrice"><?= $_isFree ?></span>
            <input id="js-freeDostRange" type="hidden" value="<?= $_freeDostRange ?>">
        </div>

    <?php endif ?>
    <!-- end ISMOBILE -->
    <div class="container">
        <div class="my-0dot5 my-xl-0">
            <?php if (ISMOBILE): ?>
                <!-- MOBILE Layout: Toggle - Logo - Icons -->
                <div class="d-flex align-items-center w-100 d-xl-none position-relative">
                    <!-- Toggle Button (Left) -->
                    <div class="d-flex align-items-center">
                        <button id="sidebarHeaderInvokerMenu" type="button" class="navbar-toggler btn u-hamburger p-1"
                                aria-controls="sidebarHeader"
                                aria-haspopup="true"
                                aria-expanded="false"
                                data-unfold-event="click"
                                data-unfold-hide-on-scroll="false"
                                data-unfold-target="#navigationSidebarLeft"
                                data-unfold-type="css-animation"
                                data-unfold-animation-in="fadeInLeft"
                                data-unfold-animation-out="fadeOutLeft"
                                data-unfold-duration="500">
                            <span id="hamburgerTriggerMenu" class="u-hamburger__box text-red">
                                <span class="u-hamburger__inner text-red"></span>
                            </span>
                        </button>
                    </div>

                    <!-- NAVIGATION SIDEBAR -->
                    <?= view($views['navigation-sidebar']) ?>

                    <!-- Logo (Absolute Center) -->
                    <div class="position-absolute w-100 d-flex justify-content-center" style="left: 0; top: 50%; transform: translateY(-50%); pointer-events: none;">
                        <div style="pointer-events: auto;">
                            <?php
                            $logo        = get_logo();
                            $logoImgPath = !empty($logo) ? $_ENV['app.imagePortalDir'] . $logo : '';
                            if (ISMOBILE) {
                                $logoImgPath = $_ENV['app.imagePortalDir'] . 'logo/ND_market_logotype_2025.webp';
                            }
                            ?>
                            <a href="<?= site_url('/') ?>"><img src="<?= $logoImgPath ?>" alt="" style="max-height: 40px; width: auto; max-width:140px;"></a>
                        </div>
                    </div>

                    <!-- Mobile Icons (Right) -->
                    <div id="mobileIcons" class="d-flex align-items-center ml-auto">
                        <ul class="d-flex list-unstyled mb-0 align-items-center">
                            <!-- Search -->
                            <li class="px-1 position-static">
                                <a id="searchClassicInvoker" class="font-size-22 text-red text-lh-1 btn-text-secondary" href="javascript:;" role="button"
                                   data-toggle="tooltip"
                                   data-placement="top"
                                   title="Search"
                                   aria-controls="searchClassic"
                                   aria-haspopup="true"
                                   aria-expanded="false"
                                   data-unfold-target="#searchClassic"
                                   data-unfold-type="css-animation"
                                   data-unfold-duration="300"
                                   data-unfold-delay="300"
                                   data-unfold-hide-on-scroll="true"
                                   data-unfold-animation-in="slideInUp"
                                   data-unfold-animation-out="fadeOut">
                                    <span class="ec ec-search"></span>
                                </a>

                                <!-- Input -->
                                <div id="searchClassic" class="dropdown-menu dropdown-unfold dropdown-menu-right left-0 mx-2" aria-labelledby="searchClassicInvoker">
                                    <form id="product-search-form" class="js-focus-state input-group px-3" method="GET" action="<?= site_url('/shop') ?>">
                                        <input class="form-control" name="searchName" type="search" placeholder="Search Product">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary px-3" type="button"><i class="font-size-18 ec ec-search"></i></button>
                                        </div>
                                    </form>
                                </div>
                                <!-- End Input -->
                            </li>
                            <!-- End Search -->

                            <!-- User Login -->
                            <?php if (!session() -> has('user_id')): ?>
                                <li class="px-1">
                                    <a id="sidebarNavToggler" href="javascript:;" role="button" class="u-header-topbar__nav-link text-red"
                                       data-unfold-event="click"
                                       data-unfold-target="#sidebarContent"
                                       data-unfold-type="css-animation"
                                       data-unfold-animation-in="fadeInRight"
                                       data-unfold-animation-out="fadeOutRight"
                                       data-unfold-duration="300">
                                        <i class="ec ec-user mr-1" style="font-size: 22px;"></i>
                                    </a>
                                </li>
                            <?php endif ?>

                            <!-- Cart -->
                            <li class="px-1">
                                <a href="<?= route_to('Cart-index') ?>" class="text-red position-relative d-flex " data-toggle="tooltip" data-placement="top" title="Cart">
                                    <i class="font-size-22 ec ec-shopping-bag"></i>
                                    <span class="js-cart-quantity <?= count($cartSessionProductsList) !== 0 ? 'cart-effect' : '' ?> width-22 height-22 bg-primary position-absolute d-flex align-items-center justify-content-center rounded-circle left-12 top-8 font-weight-bold font-size-12 text-white"><?= count($cartSessionProductsList) ?></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <?php if ($_isLoggedIn): ?>
                    <div class="d-flex align-items-center justify-content-between flex-wrap px-2 py-1 mt-1 rounded bg-white">
                        <span class="font-size-12 text-dark">Здравей, <strong class="text-red"><?= esc(session('username')) ?></strong></span>

                        <div class="d-flex align-items-center">
                            <a href="<?= route_to('Account-index') ?>" class="btn btn-outline-danger rounded-pill py-0 px-2 mr-1 font-size-11">
                                <i class="fa fa-user mr-1"></i>Акаунт
                            </a>
                            <a href="<?= route_to('Order-index') ?>" class="btn btn-outline-danger rounded-pill py-0 px-2 mr-1 font-size-11">
                                <i class="fa fa-clipboard-list mr-1"></i>Поръчки
                            </a>
                            <a href="<?= base_url('logout') ?>" class="btn btn-outline-danger rounded-pill py-0 px-2 font-size-11">
                                <i class="fa fa-sign-out-alt mr-1"></i>Изход
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (session() -> has('error')): ?>
                    <p class="mb-1 text-right px-2" style="color: red"><?php echo session('error'); ?></p>
                <?php endif; ?>
            <?php else: ?>
                <!-- DESKTOP Layout -->
                <div class="d-flex flex-row justify-content-between align-items-center w-100">
                    <div class="col-3 p-0">
                        <!-- Nav -->
                        <nav class="navbar navbar-top navbar-expand u-header__navbar py-0 justify-content-xl-between">
                            <!-- Logo -->
                            <?php
                            $logo        = get_logo();
                            $logoImgPath = !empty($logo) ? $_ENV['app.imagePortalDir'] . $logo : '';
                            ?>
                            <a href="<?= site_url('/') ?>"><img class="max-width-280" src="<?= $logoImgPath ?>" alt=""></a>
                            <!-- End Logo -->
                            <nav class="js-mega-menu navbar navbar-expand-md u-header__navbar u-header__navbar--no-space" style="position: static;">
                                <ul class="navbar-nav u-header__navbar-nav">
                                    <li class="nav-item hs-has-mega-menu u-header__nav-item"
                                        data-event="click"
                                        data-position="left">
                                        <a id="headerCategoriesMenuTop"
                                           class="btn text-dark font-weight-bold p-2 d-flex align-items-center u-header__nav-link u-header__nav-link-toggle"
                                           href="javascript:;"
                                           aria-haspopup="true"
                                           aria-expanded="false">
                                            <i class="fa fa-bars mr-2 text-red header-bars-icon"></i>
                                        </a>

                                        <div class="hs-mega-menu u-header__sub-menu p-0 animated hs-position-left" aria-labelledby="headerCategoriesMenuTop" style="bottom: auto; width: auto;">
                                            <div class="d-flex categories-flyout">
                                                <div class="categories-flyout__list border-right bg-light">
                                                    <?php if (!empty($categories)): ?>
                                                        <?= renderCategoryTree($categories, $countProductsBySubCategory) ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </nav>
                        </nav>
                        <!-- End Nav -->
 
                        <!-- ========== HEADER SIDEBAR LEFT ========== -->
                        <?php /*
                          <?= view($views['navigation-sidebar']) ?>
                         */ ?>
                        <!-- ========== END HEADER SIDEBAR LEFT ========== -->
                    </div>

                    <!-- Search bar -->
                    <div class="col-6 d-flex justify-content-center px-3" style="max-width: 50%; width: 100%; flex-shrink: 1;">
                        <div style="width: 100%; padding: 0 15px;">
                            <?= view($views['search']) ?>
                        </div>
                    </div>
                    <!-- End Search bar -->

                    <!-- Flex container for Shopping card and Nav links -->
                    <div class="col-3 d-flex flex-row flex-nowrap align-items-center justify-content-end">
                        <!-- Nav links -->
                        <?php echo view($views['navigation-headerLinks']); ?>
                        <!-- End Nav links -->
                        <!-- Shoping card -->
                        <?php if (!url_is('*Cart*')): ?>
                            <div class="d-none d-xl-block">
                                <?= view($views['miniCart']) ?>
                            </div>
                        <?php endif ?>
                        <!-- End Shoping card -->

                        <!-- LOGOUT -->
                        <?php if (session() -> has('user_id')): ?>
                            <a class="px-1 nav-link u-header__nav-link"
                               href="<?= base_url('logout') ?>"
                               role="button"
                               title="Изход">
                                <i class="fa fa-sign-out-alt font-size-28 text-red px-1" title="Изход"
                                   data-toggle="tooltip"
                                   data-placement="top"
                                   aria-controls="basicDropdownHover"
                                   aria-haspopup="true"
                                   aria-expanded="false"
                                   data-unfold-event="click"

                                   data-unfold-type="jquery-slide"
                                   data-unfold-duration="300"
                                   data-unfold-delay="300"
                                   data-unfold-hide-on-scroll="true"
                                   data-unfold-animation-in="slideInUp"></i>
                            </a>
                        <?php endif; ?>
                        <!-- END LOGOUT -->

                    </div>
                    <!-- End Flex container -->
                </div>
            <?php endif ?>
        </div>
    </div>
</section>
<!-- End Logo and Menu -->

<!-- Vertical-and-Search-Bar -->
<section class="section3 container-fluid d-none d-xl-block py-2">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between header-categories-row3">
            <nav class="js-mega-menu navbar navbar-expand-md u-header__navbar u-header__navbar--no-space">
                <ul class="navbar-nav u-header__navbar-nav">
                    <li class="nav-item hs-has-mega-menu u-header__nav-item"
                        data-event="click"
                        data-position="left">
                        <a id="headerCategoriesMenuMain"
                           class="btn btn-light border text-dark fw-500 px-3 py-2 d-flex align-items-center u-header__nav-link u-header__nav-link-toggle css-header-btn"
                           href="javascript:;"
                           aria-haspopup="true"
                           aria-expanded="false">
                            <i class="fa fa-bars mr-2 text-red header-bars-icon"></i>
                            Категории
                        </a>

                        <div class="hs-mega-menu u-header__sub-menu p-0 animated hs-position-left" aria-labelledby="headerCategoriesMenuMain" style="bottom: auto; width: auto;">
                            <div class="d-flex categories-flyout">
                                <div class="categories-flyout__list border-right bg-light">
                                    <?php if (!empty($categories)): ?>
                                        <?= renderCategoryTree($categories, $countProductsBySubCategory) ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </nav>

            <a class="btn btn-primary px-4 py-2 font-weight-bold d-flex align-items-center css-header-btn"
               href="<?= route_to('Shop-promo', 'promo=1') ?>">
                <i class="fa fa-fire mr-2"></i>
                Промо продукти
            </a>
        </div>
    </div>
</section>
<!-- End Vertical-and-secondary-menu -->
 
<script>
    // strip legacy inline widths so both mega menus can resize dynamically
    $(function () {
        ['headerCategoriesMenuMain', 'headerCategoriesMenuTop'].forEach(function (id) {
            const $mega = $('.hs-mega-menu[aria-labelledby="' + id + '"]');
            if ($mega.length) {
                $mega.each(function () {
                    this.style.removeProperty('width');
                });
                $mega.find('.categories-flyout').each(function () {
                    this.style.removeProperty('width');
                });
            }
        });
    });
</script>

<script>
    $(function () {
        $('.categories-flyout').each(function () {
            const $flyout = $(this);
            const $mega   = $flyout.closest('.hs-mega-menu');
            let pointer = { x: null, y: null };

            function measure ($el) {
                if (!$el.length) return { w: 0, h: 0 };
                const hidden = !$el.is(':visible');
                if (hidden) $el.css({ display: 'block', visibility: 'hidden' });
                const w = $el.outerWidth() || 0;
                const h = $el.outerHeight() || 0;
                if (hidden) $el.css({ display: '', visibility: '' });
                return { w, h };
            }

            function totalWidth () {
                let base = measure($flyout.find('.categories-flyout__list').first()).w || 0;
                if (base < 320) base = 320; // минимум според списъка
                let sum = base;
                $flyout.find('.categories-flyout__panel.is-visible').each(function () {
                    sum += measure($(this)).w;
                });
                return sum;
            }

            function resizeContainer () {
                // +2px за да обхванем бордера и да няма клипване
                const w = totalWidth() + 2;
                $flyout.width(w);
                if ($mega.length) {
                    $mega[0].style.setProperty('width', w + 'px', 'important');
                }
            }

            function getItemUnderPointer ($scope) {
                if (pointer.x === null || pointer.y === null || !$scope.length) return $();
                const scopeEl = $scope[0];
                if (!scopeEl) return $();

                const el = document.elementFromPoint(pointer.x, pointer.y);
                if (!el || !scopeEl.contains(el)) return $();

                const $item = $(el).closest('[data-level]');
                if (!$item.length || !scopeEl.contains($item[0])) return $();
                return $item;
            }

            function autoOpenFromPointer ($scope, attemptsLeft) {
                if (!$scope.length || attemptsLeft <= 0) return;
                requestAnimationFrame(function () {
                    const $hovered = getItemUnderPointer($scope);
                    if ($hovered.length) {
                        openPanel($hovered);
                        return;
                    }
                    autoOpenFromPointer($scope, attemptsLeft - 1);
                });
            }

            function closeFromLevel (level) {
                $flyout.find('.categories-flyout__panel').each(function () {
                    if ($(this).data('level') >= level) {
                        $(this).removeClass('is-visible');
                    }
                });

                $flyout.find('[data-level]').each(function () {
                    if ($(this).data('level') >= level) {
                        $(this).removeClass('is-active');
                    }
                });

                resizeContainer();
            }

            function openPanel ($item) {
                const lvl = $item.data('level');

                closeFromLevel(lvl + 1);

                // Само един активен елемент на текущото ниво
                $flyout.find('[data-level]').each(function () {
                    if ($(this).data('level') === lvl) {
                        $(this).removeClass('is-active');
                    }
                });

                $item.addClass('is-active');
                const $childPanel = $item.children('.categories-flyout__panel').first();
                $childPanel.addClass('is-visible');

                resizeContainer();

                // Ако курсорът вече е в зоната на новоотворен панел, отваряме автоматично и следващото ниво
                if ($childPanel.length) autoOpenFromPointer($childPanel, 8);
            }

            $flyout.on('mouseenter', '[data-level]', function (e) {
                pointer.x = e.clientX;
                pointer.y = e.clientY;
                openPanel($(this));
            });

            $flyout.on('mousemove', function (e) {
                pointer.x = e.clientX;
                pointer.y = e.clientY;
            });

            $flyout.on('mouseleave', function () {
                pointer = { x: null, y: null };
                closeFromLevel(0);
            });

            resizeContainer();

            // пазим референция за глобално преизчисляване при нужда
            this._resizeCategoriesFlyout = resizeContainer;
        });
    });

</script>

<script>
    // При всяко отваряне на менюто нулираме фиксираните width атрибути
    $(function () {
        function resetWidths(menuId) {
            const $mega = $('.hs-mega-menu[aria-labelledby="' + menuId + '"]');
            if ($mega.length) {
                $mega.each(function () { this.style.removeProperty('width'); });
                $mega.find('.categories-flyout').each(function () {
                    this.style.removeProperty('width');
                    const $flyout = $(this);
                    $flyout.find('.categories-flyout__panel').removeClass('is-visible');
                    $flyout.find('[data-level]').removeClass('is-active');
                });
            }
        }

        $('#headerCategoriesMenuMain, #headerCategoriesMenuTop').on('click', function () {
            resetWidths(this.id);
            // след нулиране преизчисляваме ширините
            $('.categories-flyout').each(function () {
                if (typeof this._resizeCategoriesFlyout === 'function') {
                    this._resizeCategoriesFlyout();
                }
            });
        });
    });
</script>

<script>
    // Отваряне на категориите при hover (desktop)
    $(function () {
        const mq = window.matchMedia('(min-width: 1200px)');
        let closeTimer = null;

        function isMenuOpen($trigger, $item, $mega) {
            const ariaExpanded = String($trigger.attr('aria-expanded')) === 'true';
            const itemOpened = $item.hasClass('hs-mega-menu-opened');
            const megaVisible = $mega.is(':visible');
            return ariaExpanded || itemOpened || megaVisible;
        }

        function openMenu($trigger, $item, $mega) {
            if (!isMenuOpen($trigger, $item, $mega)) {
                $trigger.trigger('click');
            }
        }

        function closeMenu($trigger, $item, $mega) {
            if (isMenuOpen($trigger, $item, $mega)) {
                $trigger.trigger('click');
            }
        }

        function bindHover(triggerId) {
            const $trigger = $('#' + triggerId);
            if (!$trigger.length) return;
            const $item = $trigger.closest('.hs-has-mega-menu');
            const $mega = $('.hs-mega-menu[aria-labelledby="' + triggerId + '"]');

            function clearCloseTimer() {
                if (closeTimer) {
                    clearTimeout(closeTimer);
                    closeTimer = null;
                }
            }

            $item.on('mouseenter', function () {
                if (!mq.matches) return;
                clearCloseTimer();
                openMenu($trigger, $item, $mega);
            });

            $item.on('mouseleave', function () {
                if (!mq.matches) return;
                clearCloseTimer();
                closeTimer = setTimeout(function () {
                    closeMenu($trigger, $item, $mega);
                }, 220);
            });

            $mega.on('mouseenter', function () {
                if (!mq.matches) return;
                clearCloseTimer();
                openMenu($trigger, $item, $mega);
            });

            $mega.on('mouseleave', function () {
                if (!mq.matches) return;
                clearCloseTimer();
                closeTimer = setTimeout(function () {
                    closeMenu($trigger, $item, $mega);
                }, 220);
            });
        }

        bindHover('headerCategoriesMenuMain');
        bindHover('headerCategoriesMenuTop');
    });
</script>

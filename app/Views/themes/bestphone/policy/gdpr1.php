<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Title -->
        <title>Shop | Electro - Responsive Website Template</title>

        <!-- Required Meta Tags Always Come First -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Favicon -->
        <link rel="shortcut icon" href="../../favicon.png">

        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&display=swap" rel="stylesheet">

        <!-- CSS Implementing Plugins -->
        <link rel="stylesheet" href="../../assets/vendor/font-awesome/css/fontawesome-all.min.css">
        <link rel="stylesheet" href="../../assets/css/font-electro.css">

        <link rel="stylesheet" href="../../assets/vendor/animate.css/animate.min.css">
        <link rel="stylesheet" href="../../assets/vendor/hs-megamenu/src/hs.megamenu.css">
        <link rel="stylesheet" href="../../assets/vendor/ion-rangeslider/css/ion.rangeSlider.css">
        <link rel="stylesheet" href="../../assets/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css">
        <link rel="stylesheet" href="../../assets/vendor/fancybox/jquery.fancybox.css">
        <link rel="stylesheet" href="../../assets/vendor/slick-carousel/slick/slick.css">
        <link rel="stylesheet" href="../../assets/vendor/bootstrap-select/dist/css/bootstrap-select.min.css">

        <!-- CSS Electro Template -->
        <link rel="stylesheet" href="../../assets/css/theme.css">
    </head>

    <body>

        <!-- ========== HEADER ========== -->
        <header id="header" class="u-header u-header-left-aligned-nav">
            <div class="u-header__section">
                <!-- Topbar -->
                <div class="u-header-topbar py-2 d-none d-xl-block">
                    <div class="container">
                        <div class="d-flex align-items-center">
                            <div class="topbar-left">
                                                                            <?php if (session()->has('user_id')): ?>
                                            <?php if ($cartSession['cart_all_products_info']['quantity'] > 0) :?>
                                            <a href="<?=site_url('cart/')?>" class="text-gray-90 position-relative d-flex " data-toggle="tooltip" data-placement="top" title="Cart">
                                                <i class="font-size-50 ec ec-shopping-bag"></i>
                                                <span class="cart-quantity width-22 height-22 bg-dark position-absolute flex-content-center text-white rounded-circle left-12 top-8 font-weight-bold font-size-12"><?php if (isset($cartSession['cart_all_products_info']['quantity'])) echo $cartSession['cart_all_products_info']['quantity'];?></span>
                                                <span class="cart-price d-none d-xl-block font-weight-bold font-size-16 text-white ml-3"><?=$cartSession['cart_all_products_info']['price']?> лв.</span>
                                            </a>
                                             <?php endif; ?>
                                            <?php endif; ?>
                            </div>
                            <div class="topbar-right ml-auto">
                                <ul class="list-inline mb-0">
                                <li class="list-inline-item mr-0 u-header-topbar__nav-item u-header-topbar__nav-item-border">
                                        <!-- Account Sidebar Toggle Button -->
                                        <?php if (session()->has('user_id')): ?>
                                            <!-- Покажете съдържанието, което трябва да види логнатият потребител -->
                                            <p>Здравей, <strong><?= session('username') ?> |</strong>  <a href="<?= base_url('logout') ?>">| Logout</a></p>
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
                                                <i class="ec ec-user mr-1"></i> Регистрирай се <span class="text-gray-50">или</span> Влез
                                            </a>
                                        <?php endif; ?>
                                        <?php if (session()->has('error')): ?>
                                            <br />
                                            <span style="color: red";><?php echo session('error'); ?></span>
                                        <?php endif; ?>
                                        <!-- End Account Sidebar Toggle Button -->
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Topbar -->

                <!-- Logo and Menu -->
                <div class="py-2 py-xl-4 bg-primary-down-lg">
                    <div class="container my-0dot5 my-xl-0">
                        <div class="row align-items-center">
                            <!-- Logo-offcanvas-menu -->
                            <div class="col-auto">
                                <!-- Nav -->
                                <nav class="navbar navbar-expand u-header__navbar py-0 justify-content-xl-between max-width-230 min-width-230">
                                    <!-- Logo -->
                                    <a href="<?=site_url('/')?>">                                                         
                                        <img src="<?= base_url('assets/img/logo-230-45.png') ?>" alt="">
                                    </a>
                                    <!-- End Logo -->

                                    <!-- Fullscreen Toggle Button -->
                                    <button id="sidebarHeaderInvokerMenu" type="button" class="navbar-toggler d-block btn u-hamburger mr-3 mr-xl-0"
                                        aria-controls="sidebarHeader"
                                        aria-haspopup="true"
                                        aria-expanded="false"
                                        data-unfold-event="click"
                                        data-unfold-hide-on-scroll="false"
                                        data-unfold-target="#sidebarHeader1"
                                        data-unfold-type="css-animation"
                                        data-unfold-animation-in="fadeInLeft"
                                        data-unfold-animation-out="fadeOutLeft"
                                        data-unfold-duration="500">
                                        <span id="hamburgerTriggerMenu" class="u-hamburger__box">
                                            <span class="u-hamburger__inner"></span>
                                        </span>
                                    </button>
                                    <!-- End Fullscreen Toggle Button -->
                                </nav>
                                <!-- End Nav -->

                                <!-- ========== HEADER SIDEBAR ========== -->
                                <aside id="sidebarHeader1" class="u-sidebar u-sidebar--left" aria-labelledby="sidebarHeaderInvokerMenu">
                                    <div class="u-sidebar__scroller">
                                        <div class="u-sidebar__container">
                                            <div class="u-header-sidebar__footer-offset pb-0">
                                                <!-- Toggle Button -->
                                                <div class="position-absolute top-0 right-0 z-index-2 pt-4 pr-7">
                                                    <button type="button" class="close ml-auto"
                                                        aria-controls="sidebarHeader"
                                                        aria-haspopup="true"
                                                        aria-expanded="false"
                                                        data-unfold-event="click"
                                                        data-unfold-hide-on-scroll="false"
                                                        data-unfold-target="#sidebarHeader1"
                                                        data-unfold-type="css-animation"
                                                        data-unfold-animation-in="fadeInLeft"
                                                        data-unfold-animation-out="fadeOutLeft"
                                                        data-unfold-duration="500">
                                                        <span aria-hidden="true"><i class="ec ec-close-remove text-gray-90 font-size-20"></i></span>
                                                    </button>
                                                </div>
                                                <!-- End Toggle Button -->

                                                <!-- Content -->
                                                <div class="js-scrollbar u-sidebar__body">
                                                    <div id="headerSidebarContent" class="u-sidebar__content u-header-sidebar__content">
                                                        <!-- Logo -->
                                                        <a class="d-flex ml-0 navbar-brand u-header__navbar-brand u-header__navbar-brand-vertical" href="<?=site_url('/')?>" aria-label="Electro">
                                                        <img src="<?= base_url('assets/img/logo-230-45.png') ?>" alt="">
                                                        </a>
                                                        <!-- End Logo -->

                                                        <!-- List -->
                                                        <ul id="headerSidebarList" class="u-header-collapse__nav">
                                                            <li class="u-has-submenu u-header-collapse__submenu">
                                                                <a class="u-header-collapse__nav-link " href="/" role="button" >
                                                                   Начало
                                                                </a>
                                                            </li>
                                                            <li class="u-has-submenu u-header-collapse__submenu">
                                                                <a class="u-header-collapse__nav-link " href="/shop" role="button" >
                                                                   Магазин
                                                                </a>
                                                            </li>
                                                   
                                                            <?php if (session()->has('user_id')): ?>
                                                            <li class="u-has-submenu u-header-collapse__submenu">
                                                                <a class="u-header-collapse__nav-link u-header-collapse__nav-pointer" href="javascript:;" data-target="#Collaps" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="Collaps">Ценова листа</a>
                                                                <div id="Collaps" class="collapse" data-parent="#headerSidebarList">
                                                                    <ul id="" class="u-header-collapse__nav-list">
                                                                         <li><a href="<?=site_url('/cenovalista')?>" class="u-header-collapse__submenu-nav-link" ><strong>Виж всички</strong></a></li>
                                                                        <?php foreach ($allCenovaLista as $cenovaLista): ?>
                                                                            <li><a class="u-header-collapse__submenu-nav-link" href="<?=site_url('/cenovalista/'.$cenovaLista['id'])?>"><?= $cenovaLista['offersName'] ?></a></li>
                                                                        <?php endforeach; ?>
                                                                    </ul>
                                                                </div>
                                                            </li>
                                                            <?php endif; ?>
                                                            <li class="u-has-submenu u-header-collapse__submenu">
                                                                <a class="u-header-collapse__nav-link " href="/shop" role="button" >
                                                                   Моят Профил
                                                                </a>
                                                            </li>
                                                            <?php if (session()->has('user_id')): ?>
                                                            <li class="u-has-submenu u-header-collapse__submenu">
                                                                <a class="u-header-collapse__nav-link " href="<?=site_url('/orders')?>" role="button" >
                                                                   Поръчки
                                                                </a>
                                                            </li>
                                                            <?php endif; ?>
                                                            <hr>
                                                            <p class="text-center font-weight-bold">Търсене по категории</p>                           
                                                            <?php foreach ($categories as $category): ?>
                                                                <?php if (isset($category['children'])): ?>   
                                                            <!-- Accessories -->
                                                            <li class="u-has-submenu u-header-collapse__submenu">
                                                                <a class="u-header-collapse__nav-link u-header-collapse__nav-pointer" href="javascript:;" data-target="#Collaps<?= $category['category_id']?>" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="Collaps<?= $category['category_id']?>">
                                                                <?= $category['category_name']?>
                                                                </a>

                                                                <div id="Collaps<?= $category['category_id']?>" class="collapse" data-parent="#headerSidebarList">
                                                                    <ul id="<?=$category['category_id']?>" class="u-header-collapse__nav-list">
                                                                        <?php foreach ($category['children'] as $childCategory): ?>
                                                                            <li><a class="u-header-collapse__submenu-nav-link" href="<?= '/shop?categoryId='.$childCategory['category_id'] ?>"><?= $childCategory['category_name']; ?></a></li>
                                                                        <?php endforeach; ?>
                                                                    </ul>
                                                                </div>
                                                            </li>
                                                            <!-- End Accessories -->
                                                            <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                        <!-- End List -->
                                                    </div>
                                                </div>
                                                <!-- End Content -->
                                            </div>
                                        </div>
                                    </div>
                                </aside>
                                <!-- ========== END HEADER SIDEBAR ========== -->
                            </div>
                            <!-- End Logo-offcanvas-menu -->
                            <!-- Primary Menu -->
                            <div class="col d-none d-xl-block">
                                <!-- Nav -->
                                <nav class="js-mega-menu navbar navbar-expand-md u-header__navbar u-header__navbar--no-space">
                                    <!-- Navigation -->
                                    <div id="navBar" class="collapse navbar-collapse u-header__navbar-collapse">
                                        <ul class="navbar-nav u-header__navbar-nav">
                                            <!-- About us -->
                                            <li class="nav-item u-header__nav-item">
                                                <a class="nav-link u-header__nav-link" href="/">Начало</a>
                                            </li>
                                            <!-- End About us -->

                                            <!-- FAQs -->
                                            <li class="nav-item u-header__nav-item">
                                                <a class="nav-link u-header__nav-link" href="/shop">Магазин</a>
                                            </li>
                                            <!-- End FAQs -->
                                              <?php if (session()->has('user_id')): ?>
                                            <li class="nav-item hs-has-mega-menu u-header__nav-item" data-event="click" data-animation-in="slideInUp" data-animation-out="fadeOut" data-position="left">
                                                <a id="homeMegaMenu" class="nav-link u-header__nav-link u-header__nav-link-toggle" href="javascript:;" aria-haspopup="true" aria-expanded="false">Ценова листа</a>
                                                <!-- Home - Mega Menu -->
                                                <div class="hs-mega-menu w-75 u-header__sub-menu animated hs-position-left slideInUp" aria-labelledby="homeMegaMenu" style="display: none;">
                                                    <div class="row u-header__mega-menu-wrapper">
                                                        <div class="col-md-9">
                                                            <ul class="u-header__sub-menu-nav-group">
                                                                 <li><a href="<?=site_url('/cenovalista')?>" class="nav-link u-header__sub-menu-nav-link"><strong>Виж всички</strong></a></li>
                                                                <?php foreach($allCenovaLista as $cenovaLista): ?>
                                                                <li><a href="<?=site_url('/cenovalista/'.$cenovaLista['id'])?>" class="nav-link u-header__sub-menu-nav-link"><?= $cenovaLista['offersName'] ?></a></li>
                                                                <?php endforeach; ?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End Home - Mega Menu -->
                                            </li>
                                            <?php endif; ?>
                                            <!-- Contact Us -->
                                            <li class="nav-item u-header__nav-item">
                                                <a class="nav-link u-header__nav-link" href="<?=site_url('/account')?>">Моят профил</a>
                                            </li>
                                            <?php if (session()->has('user_id')): ?>
                                            <li class="nav-item u-header__nav-item">
                                                <a class="nav-link u-header__nav-link" href="<?=site_url('/orders')?>">Поръчки</a>
                                            </li>
                                            <?php endif; ?>
                                            <!-- End Contact Us -->
                                        </ul>
                                    </div>
                                    <!-- End Navigation -->
                                </nav>
                                <!-- End Nav -->
                            </div>
                            <!-- End Primary Menu -->
                            <!-- Customer Care -->
                            <div class="d-none d-xl-block col-md-auto">
                                <div class="d-flex">
                                    <i class="ec ec-support font-size-50 text-primary"></i>
                                    <div class="ml-2">
                                        <div class="phone">
                                            <strong>Телефон</strong> <a href="tel:+359 896 830 406" class="text-gray-90">+359 896 830 406</a>
                                        </div>
                                        <div class="email">
                                            <strong> E-mail:</strong><a href="mailto:office@valpers.eu?subject=Help Need" class="text-gray-90">office@valpers.eu</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Customer Care -->
                            <!-- Header Icons -->
                            <div class="d-xl-none col col-xl-auto text-right text-xl-left pl-0 pl-xl-3 position-static">
                                <div class="d-inline-flex">
                                    <ul class="d-flex list-unstyled mb-0 align-items-center">
                                        <!-- Search -->
                                        <li class="col d-xl-none px-2 px-sm-3 position-static">
                                            <a id="searchClassicInvoker" class="font-size-22 text-gray-90 text-lh-1 btn-text-secondary" href="javascript:;" role="button"
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
                                                <form class="js-focus-state input-group px-3"  method='GET' action="<?=site_url('/shop')?>">
                                                    <input class="form-control" type="search" name="searchName" placeholder="Търси продукт">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-primary px-3" type="submit"><i class="font-size-18 ec ec-search"></i></button>
                                                    </div>
                                                </form>
                                            </div>
                                            <!-- End Input -->
                                        </li>
                                        <!-- End Search -->
                                        <!-- <li class="col d-none d-xl-block"><a href="../shop/compare.html" class="text-gray-90" data-toggle="tooltip" data-placement="top" title="Compare"><i class="font-size-22 ec ec-compare"></i></a></li> -->
                                        <!-- <li class="col d-none d-xl-block"><a href="../shop/wishlist.html" class="text-gray-90" data-toggle="tooltip" data-placement="top" title="Favorites"><i class="font-size-22 ec ec-favorites"></i></a></li> -->
                                        <li class="col d-xl-none px-2 px-sm-3"><a href="<?=site_url('/account')?>" class="text-gray-90" data-toggle="tooltip" data-placement="top" title="My Account"><i class="font-size-22 ec ec-user"></i></a></li>
                                        <li class="col pr-xl-0 px-2 px-sm-3">
                                            <a href="<?=site_url('cart/')?>" class="text-gray-90 position-relative d-flex " data-toggle="tooltip" data-placement="top" title="Cart">
                                                <i class="font-size-22 ec ec-shopping-bag"></i>
                                                <span class="cart-quantity width-22 height-22 bg-dark position-absolute flex-content-center text-white rounded-circle left-12 top-8 font-weight-bold font-size-12"><?php if (isset($cartSession['cart_all_products_info']['quantity'])) echo $cartSession['cart_all_products_info']['quantity'];?></span>
                                                <span class="cart-price d-none d-xl-block font-weight-bold font-size-16 text-white ml-3"><?=$cartSession['cart_all_products_info']['price']?> лв.</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- End Header Icons -->
                        </div>
                    </div>
                </div>
                <!-- End Logo and Menu -->

                <!-- Vertical-and-Search-Bar -->
                <div class="d-none d-xl-block bg-primary">
                    <div class="container">
                        <div class="row align-items-stretch min-height-50">
                            <!-- Vertical Menu -->
                            <div class="col-md-auto d-none d-xl-flex align-items-end">
                                <div class="max-width-270 min-width-270">
                                    <!-- Basics Accordion -->
                                    <div id="basicsAccordion">
                                        <!-- Card -->
                                        <div class="card border-0 rounded-0">
                                            <div class="card-header bg-primary rounded-0 card-collapse border-0" id="basicsHeadingOne">
                                                <button type="button" class="btn-link btn-remove-focus btn-block d-flex card-btn py-3 text-lh-1 px-4 shadow-none btn-primary rounded-top-lg border-0 font-weight-bold text-gray-90"
                                                    data-toggle="collapse"
                                                    data-target="#basicsCollapseOne"
                                                    aria-expanded="true"
                                                    aria-controls="basicsCollapseOne">
                                                    <span class="pl-1 text-white">Категории</span>
                                                    <span class="text-white ml-3">
                                                        <span class="ec ec-arrow-down-search"></span>
                                                    </span>
                                                </button>
                                            </div>
                                            <div id="basicsCollapseOne" class="collapse vertical-menu v1"
                                                aria-labelledby="basicsHeadingOne"
                                                data-parent="#basicsAccordion">
                                                <div class="card-body p-0">
                                                    <nav class="js-mega-menu navbar navbar-expand-xl u-header__navbar u-header__navbar--no-space hs-menu-initialized">
                                                        <div id="navBar" class="collapse navbar-collapse u-header__navbar-collapse">
                                                            
                                                            
                                                            <!--<ul class="navbar-nav u-header__navbar-nav border-primary border-top-0">-->
                                                            <!--<?php foreach ($categories as $category): ?>-->
                                                            <!--    <?php if (isset($category['children'])): ?>-->
                                                                <!-- Nav Item -->
                                                            <!--    <li class="nav-item hs-has-sub-menu u-header__nav-item"-->
                                                            <!--        data-event="click"-->
                                                            <!--        data-animation-in="slideInUp"-->
                                                            <!--        data-animation-out="fadeOut"-->
                                                            <!--        data-position="left">-->
                                                            <!--        <a id="homeMegaMenu" class="nav-link u-header__nav-link u-header__nav-link-toggle u-header__nav-link-toggle" href="javascript:;" aria-haspopup="true" aria-expanded="false" aria-labelledby="homeSubMenu"><?= $category['category_name']?></a>-->

                                                                    <!-- Home - Submenu -->
                                                                    
                                                            <!--        <ul id="homeSubMenu" class="hs-sub-menu u-header__sub-menu animated hs-position-left fadeOut" aria-labelledby="homeMegaMenu" style="min-width: 230px; display: none;">-->
                                                            <!--            <?php foreach ($category['children'] as $childCategory): ?>-->
                                                            <!--            <li class="hs-has-sub-menu">-->
                                                            <!--                <a class="nav-link u-header__sub-menu-nav-link " href="<?= '/shop?categoryId='.$childCategory['category_id'] ?>"><?= $childCategory['category_name']; ?> </a>-->
                                                            <!--            </li>-->
                                                            <!--            <?php endforeach; ?>-->
                                                            <!--        </ul>-->
                                                                     
                                                                    <!-- End Home - Submenu -->
                                                            <!--    </li>-->
                                                            <!--    <?php endif; ?>-->
                                                                <!-- End Nav Item -->
                                                            <!--    <?php endforeach; ?>-->
                                                            <!--</ul>-->
                                                                                                                        <ul class="navbar-nav u-header__navbar-nav border-primary border-top-0">
                                                                <?php foreach ($categories as $category): ?>
                                                                <!-- Nav Item MegaMenu -->
                                                                <li class="nav-item hs-has-mega-menu u-header__nav-item"
                                                                    data-event="hover" 
                                                                    data-animation-in="slideInUp"
                                                                    data-animation-out="fadeOut"
                                                                    data-position="left">
                                                                    <a id="homeMegaMenu" class="nav-link u-header__nav-link u-header__nav-link-toggle u-header__nav-link-toggle" href="javascript:;" aria-haspopup="true" aria-expanded="false" aria-labelledby="homeSubMenu"><?= $category['category_name']?></a>
                                                            
                                                                    <!-- Nav Item - Mega Menu -->
                                                                    <div class="hs-mega-menu vmm-tfw u-header__sub-menu" aria-labelledby="homeMegaMenu">
                                                                        <div class="vmm-bg">
                                                                            <!-- <img class="img-fluid" src="../../assets/img/500X400/img1.png" alt="Image Description"> -->
                                                                        </div>
                                                            
                                                                        <div class="row u-header__mega-menu-wrapper">
                                                                            <?php
                                                                            $childCategories = $category['children'];
                                                                            $totalCategories = count($childCategories);
                                                            
                                                                            $elementsPerColumn = 11; // Брой елементи на колона
                                                            
                                                                            if ($totalCategories > 0) {
                                                                                $columns = ceil($totalCategories / $elementsPerColumn);
                                                            
                                                                                for ($i = 0; $i < $columns; $i++) {
                                                                                    echo '<div class="col mb-3 mb-sm-0"><ul class="u-header__sub-menu-nav-group mb-3">';
                                                                                    for ($j = $i * $elementsPerColumn; $j < min(($i + 1) * $elementsPerColumn, $totalCategories); $j++) {
                                                                                        $childCategory = $childCategories[$j];
                                                                                        echo '<li><a class="nav-link u-header__sub-menu-nav-link" href="/shop?categoryId=' . $childCategory['category_id'] . '">' . $childCategory['category_name'] . '</a></li>';
                                                                                    }
                                                                                    echo '</ul></div>';
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </div>
                                                                    </div>
                                                                    <!-- End Nav Item - Mega Menu -->
                                                                </li>
                                                                <!-- End Nav Item MegaMenu-->
                                                                <?php endforeach; ?>
                                                            </ul>
                                                            
                                                            
                                                        </div>
                                                    </nav>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Card -->
                                    </div>
                                    <!-- End Basics Accordion -->
                                </div>
                            </div>
                            <!-- End Vertical Menu -->
                            <!-- Search bar -->
                            <div class="col align-self-center">
                                <!-- Search-Form -->
                                <form class="js-focus-state" method='GET' action="https://test.valpers.com/shop">
                                    <label class="sr-only" for="searchproduct">Търсене</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control py-2 pl-5 font-size-15 border-right-0 height-40 border-width-2 rounded-left-pill border-primary" name="searchName" id="searchproduct-item" placeholder="Търси продукт" aria-label="Търси продукт" aria-describedby="searchProduct1" required>
                                        <div class="input-group-append">    
                                            <button type='submit' class="btn btn-primary height-40 py-2 px-3 rounded-right-pill" type="button" id="searchProduct1">
                                                <span class="ec ec-search font-size-24"></span>
                                            </button>
                                        </div>
                                    </div>
                                </form> 
                               <div id="search1-result"></div> 
                                <!-- End Search-Form -->
                            </div>
                            <!-- End Search bar -->
                            <!-- Header Icons -->
                            <div class="col-md-auto align-self-center">
                                <div class="d-flex">
                                    <ul class="d-flex list-unstyled mb-0">
                                        <!-- <li class="col"><a href="../shop/compare.html" class="text-gray-90" data-toggle="tooltip" data-placement="top" title="Compare"><i class="font-size-22 ec ec-compare"></i></a></li> -->
                                        <!-- <li class="col"><a href="../shop/wishlist.html" class="text-gray-90" data-toggle="tooltip" data-placement="top" title="Favorites"><i class="font-size-22 ec ec-favorites"></i></a></li> -->
                                        <li class="col pr-0">
                                            <a href="<?=site_url('cart/')?>" class="text-gray-90 position-relative d-flex " data-toggle="tooltip" data-placement="top" title="Cart">
                                                <i class="font-size-22 ec ec-shopping-bag"></i>
                                                <span class="cart-quantity width-22 height-22 bg-dark position-absolute d-flex align-items-center justify-content-center rounded-circle left-12 top-8 font-weight-bold font-size-12 text-white"><?=$cartSession['cart_all_products_info']['quantity']?></span>
                                                <span class="cart-price d-none d-xl-block font-weight-bold font-size-16 text-white ml-3"><?=$cartSession['cart_all_products_info']['price']?> лв.</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- End Header Icons -->
                        </div>
                    </div>
                </div>
                <!-- End Vertical-and-secondary-menu -->
            </div>
        </header>
        <!-- ========== END HEADER ========== -->

        <!-- ========== MAIN CONTENT ========== -->
        <main id="content" role="main">
            <!-- breadcrumb -->
            <div class="bg-gray-13 bg-md-transparent">
                <div class="container">
                    <!-- breadcrumb -->
                    <div class="my-md-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-3 flex-nowrap flex-xl-wrap overflow-auto overflow-xl-visble">
                                <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1"><a href="<?="/"?>">Начало</a></li>
                                <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1 active" aria-current="page">Политика за защита на личните данни и ползването на бизквитки</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- End breadcrumb -->
                </div>
            </div>
            <!-- End breadcrumb -->



            <div class="container">
                <div class="mb-4">
                    <h4 class="text-center">ПОЛИТИКА ЗА ЗАЩИТА НА ЛИЧНИТЕ ДАНННИ И УПОТРЕБАТА НА БИСКВИТКИ НА "ВАЛПЕРС КОНСУЛТ"  ЕООД <br /> (В СИЛА ОТ 25.5.2018 Г.)</h4>
                </div>

            </div>


            <div class="container">

            <div class="row"><div
id="content" class="col-sm-12"><p><strong>Т.1 Кои сме ние</strong></p><p>Интернет сайтът <strong>www.valpers.eu</strong> се притежава и управлява от фирма<strong> Валперс Консулт ЕООД</strong>.&nbsp; Като отговорна фирма спрямо нашите потребители, Ние разбираме, че Вие, посетителите на нашият сайт, вероятно имате въпроси относно начинът, по който обработваме информацията, която ни предоставяте. Ние уважаваме правото Ви на запазване на поверителността на личната ви информация и данни, затова бихме желали да Ви обясним каква информация събираме и как я използваме. Имайте предвид, че използването на тази интернет страница от Ваша страна подлежи също и на спазването на Общите&nbsp;условия.</p><p>&nbsp;</p><p><strong>Т.2 Каква информация събираме</strong></p><p>В случай, че желаете да закупите продукт или услуга от нашият уебсайт, то Вие ще трябва да се регистрирате, за което се изисква дa предоставите някои лични данни като:&nbsp; Имена, телефонен номер, адрес, e-mail адрес и друга лична информация според спецификата и нуждите на поръчките,&nbsp;които правите.</p><p>Вие не сте задължен/а и ние не изискваме от Вас да се регистрирате или да предоставяте персонално разпознаваема (лична) информация, за да разгледате нашия уебсайт или да осъществите достъп до по-голямата част от съдържанието му.</p><p>Ако желаете да упражните правото си на достъп до Вашите лични данни или да ги коригирате, може да се <span
style="text-decoration: underline;"><span
style="color: #3366ff; text-decoration: underline;"><a
style="color: #3366ff; text-decoration: underline;" href="index.php?route=information/contact">свържете с нас</a></span></span> по удобен за Вас начин.</p><p>&nbsp;</p><p><strong>Т.3&nbsp; По какъв начин събираме и обменяме лична информация</strong></p><p>Информацията която ние сбираме, за да извършим вашите поръчки, достъпваме основно по четири начина, а именно:</p><ul><li>Форма за регистрация</li><li>Форма за контакт</li><li>Онлайн чат със сътрудник</li><li>E-mail</li></ul><p>&nbsp;</p><p><strong>Т.4 Закакви цели използваме предоставените от Вас лични данни</strong></p><p>Събраната информация се обработва от Валперс Консулт ЕООД с цел маркетингова информираност за актуални промоции, нови продукти, възможности за доставка, издаване на гаранционни карти и др.</p><p>&nbsp;</p><p><strong>Т.5 За какво използваме предоставеният от Вас имейл</strong></p><ul><li>за идентификация и обработка на направени от Вас поръчки през електронния магазин на сайта;</li><li>за абониране и идентификация при провеждане на игри и онлайн кампании;</li><li>за изпращане на информация към Вас относно продукти и услуги на компанията, в случай че сте се абонирали за електронен бюлетин от Валперс Консулт.</li></ul><p>&nbsp;</p><p><strong>Т.6 Кампании, игри и промоции</strong></p><p>На нашия уебсайт може да намерите съобщения за промоции, в които можете да участвате, както и препратки към такива инициативи (например във Facebook).</p><p>Ние използваме предоставената от Вас информация, само в случаите, че сте се съгласили да участвате в тези инициативи, за да осъществим контакт с Вас.</p><p>&nbsp;</p><p><strong>Т.7 Бисквитки</strong></p><p>&bdquo;Бисквитките&ldquo; представляват малки текстови файлове, които се записват на Вашия компютър, когато се посещава уеб страницата ни. В случай, че имате достъп до тази уеб страница в друг момент, браузърът Ви изпраща обратно съдържанието на "бисквитките" на съответния предложител и по този начин позволява повторното идентифициране на терминалното устройство. Четенето на "бисквитките" ни позволява да проектираме нашата уеб страница оптимално за Вас и Ви улеснява при използването й.</p><p>&nbsp;</p><p>&nbsp;</p><p><strong>Т.7.1 За какво използваме &bdquo;бисквитките&ldquo;</strong></p><p>Редица наши страници използват бисквитки за запаметяване на:</p><ul><li>Вашите настройки за показване, като например настройките за контраст на цветовете или размер на шрифта;</li><li>Информация за това дали вече сте попълнили проучване за полезността на съдържанието на сайта с цел да не бъдете питани отново;</li><li>Информация дали сте се съгласили или не с използването на бисквитки на този сайт.</li><li>Стъпки при поръчка, вход в сайта, детайли за логин, коментари, споделяне в социални мрежи, предпочетания и настройки на сайта (където има такива). Без тях, последните няма как да бъдат възможни, съответно и сайта няма да функционира правилно. Например, дори видеа от youtube няма да се зареждат.</li></ul><p>Освен това някои от вградените на нашите страници видеоклипове също използват бисквитки, за да събират анонимно статистика за това как сте стигнали до съответната страница и кои видеоклипове сте гледали.</p><p>Разрешаването на бисквитките не е абсолютно необходимо, за да може уебсайтът да работи, но ще допринесе за по-доброто му използване. Можете да изтриете или блокирате бисквитките, но ако го направите е възможно някои функции на сайта да не работят както трябва.</p><p>Свързаната с бисквитките информация не се използва за установяване на самоличността&nbsp;ви, а образците с данни са изцяло под наш контрол. Бисквитките не се използват за цели, различни от посочените тук.</p><p>&nbsp;</p><p><strong>Т.7.2 Изтриване и деактивиране на &bdquo;бисквитки&ldquo;</strong></p><p>Браузърът Ви позволява да изтриете всички "бисквитки" по всяко време. За да направите това, моля, обърнете се към помощните функции на Вашия браузър.С това отделни функции на уеб страницата ни могат вече да не бъдат достъпни за Вас.</p><p>&nbsp;</p><p><strong>Вашите права</strong></p><p>Вие може да имате право на достъп до лична информация, която съхраняваме относно Вас, и до свързана с това информация съгласно законодателството в областта на защитата на личните данни. Вие можете също да изискате всяка неточна лична информация да бъде поправена или заличена. Вие можете във всеки един момент да възразите срещу използването от наша страна на Вашата лична информация за целите на директния маркетинг, като при някои други обстоятелства може да имате право да възразите срещу обработването от наша страна на част или на цялата Ваша лична информация (и да изискате тя да бъде заличена).</p><p>Ако желаете да упражните някое от тези права, се свържете с нас на нашите телефони или ни пишете на фирмения мейл &ndash; <span
style="color: #3366ff;"><a style="color: #3366ff;" href="/cdn-cgi/l/email-protection#422d24242b21270234232e322730316c2737"><span class="__cf_email__" data-cfemail="157a73737c767055637479657067663b7060">[email&#160;protected]</span></a></span> &nbsp;</p><p>Освен това можете да подадете жалба относно обработването от наша страна на Вашата лична информация пред съответния орган за защита на данните:</p><p><strong><u>За България</u></strong></p><p><strong>Комисия за защита на личните данни, </strong>бул.&nbsp;&bdquo;Проф. Цветан Лазаров&ldquo; №&nbsp;2, София&nbsp;1592; тел.: +359 2 915 3580; факс: +359 2 915 3525; ел. поща: <a href="mailto: kzld@cpdp.bg" class="__cf_email__" data-cfemail="187362747c587b687c68367a7f"> kzld@cpdp.bg</a>; уебсайт: <span
style="color: #3366ff;"><a
style="color: #3366ff;" href="http://www.cpdp.bg/">http://www.cpdp.bg/</a></span></p><p>&nbsp;&nbsp;&nbsp;&nbsp;<strong>Ние от Валперс Консулт ЕООД гарантираме на своите клиенти конфиденциалност на личните данни, декларирайки, че те няма да бъдат използвани за различни от предназначението си цели, а именно: споменатите в т.4 и т.5 от политиката за защита на личните данни. Валперс Консулт ЕООД е вписан в &bdquo;Регистъра на администраторите на лични данни и на водените от тях регистри&ldquo; с удостоверение №419061.</strong></p><p>&nbsp;</p>


</div>





        </main>
        <!-- ========== END MAIN CONTENT ========== -->

        <!-- ========== FOOTER ========== -->
        <footer>
            <!-- Footer-top-widget -->
            <div class="container d-none d-lg-block mb-3">
                <div class="row">
                    <div class="col-wd-3 col-lg-4">
                        <div class="widget-column">
                            <div class="border-bottom border-color-1 mb-5">
                                <h3 class="section-title section-title__sm mb-0 pb-2 font-size-18">Featured Products</h3>
                            </div>
                            <ul class="list-unstyled products-group">
                            <?php foreach ($featured_products as $featured_product): ?>
                                <li class="product-item product-item__list row no-gutters mb-6 remove-divider">
                                    <div class="col-auto">
                                        <a href="../shop/single-product-fullwidth.html" class="d-block width-75 text-center"><img class="img-fluid" src="../../assets/img/75X75/img1.jpg" alt="Image Description"></a>
                                    </div>
                                    <div class="col pl-4 d-flex flex-column">
                                        <h5 class="product-item__title mb-0"><a href="../shop/single-product-fullwidth.html" class="text-blue font-weight-bold"><?= $featured_product['product_name'] ?></a></h5>
                                        <div class="prodcut-price mt-auto">
                                            <div class="font-size-15">$1149.00</div>
                                        </div>
                                    </div>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <div class="col-wd-3 col-lg-4">
                        <div class="border-bottom border-color-1 mb-5">
                            <h3 class="section-title section-title__sm mb-0 pb-2 font-size-18">Onsale Products</h3>
                        </div>
                        <ul class="list-unstyled products-group">
                        <?php foreach ($sales_products as $sales_product): ?>
                            <li class="product-item product-item__list row no-gutters mb-6 remove-divider">
                                <div class="col-auto">
                                    <a href="../shop/single-product-fullwidth.html" class="d-block width-75 text-center"><img class="img-fluid" src="../../assets/img/75X75/img1.jpg" alt="Image Description"></a>
                                </div>
                                <div class="col pl-4 d-flex flex-column">
                                    <h5 class="product-item__title mb-0"><a href="../shop/single-product-fullwidth.html" class="text-blue font-weight-bold"><?= $sales_product['product_name'] ?></a></h5>
                                    <div class="prodcut-price mt-auto">
                                        <div class="font-size-15">$1149.00</div>
                                    </div>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="col-wd-3 col-lg-4">
                        <div class="border-bottom border-color-1 mb-5">
                            <h3 class="section-title section-title__sm mb-0 pb-2 font-size-18">Top Rated Products</h3>
                        </div>
                        <ul class="list-unstyled products-group">
                        <?php foreach ($toprated_products as $toprated_product): ?>
                            <li class="product-item product-item__list row no-gutters mb-6 remove-divider">
                                <div class="col-auto">
                                    <a href="../shop/single-product-fullwidth.html" class="d-block width-75 text-center"><img class="img-fluid" src="../../assets/img/75X75/img1.jpg" alt="Image Description"></a>
                                </div>
                                <div class="col pl-4 d-flex flex-column">
                                    <h5 class="product-item__title mb-0"><a href="../shop/single-product-fullwidth.html" class="text-blue font-weight-bold"><?= $toprated_product['product_name'] ?></a></h5>
                                    <div class="prodcut-price mt-auto">
                                        <div class="font-size-15">$1149.00</div>
                                    </div>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="col-wd-3 d-none d-wd-block">
                        <a href="../shop/shop.html" class="d-block"><img class="img-fluid" src="../../assets/img/330X360/img1.jpg" alt="Image Description"></a>
                    </div>
                </div>
            </div>
            <!-- End Footer-top-widget -->
            <!-- Footer-newsletter -->
            <div class="bg-primary py-3">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-7 mb-md-3 mb-lg-0 text-white">
                            <div class="row align-items-center">
                                <div class="col-auto flex-horizontal-center">
                                    <i class="ec ec-newsletter font-size-40"></i>
                                    <h2 class="font-size-20 mb-0 ml-3">Sign up to Newsletter</h2>
                                </div>
                                <div class="col my-4 my-md-0">
                                    <h5 class="font-size-15 ml-4 mb-0">...and receive <strong>$20 coupon for first shopping.</strong></h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <!-- Subscribe Form -->
                            <form class="js-validate js-form-message">
                                <label class="sr-only" for="subscribeSrEmail">Email address</label>
                                <div class="input-group input-group-pill">
                                    <input type="email" class="form-control border-0 height-40" name="email" id="subscribeSrEmail" placeholder="Email address" aria-label="Email address" aria-describedby="subscribeButton" required
                                    data-msg="Please enter a valid email address.">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-dark btn-sm-wide height-40 py-2" id="subscribeButton">Sign Up</button>
                                    </div>
                                </div>
                            </form>
                            <!-- End Subscribe Form -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Footer-newsletter -->
            <!-- Footer-bottom-widgets -->
            <div class="pt-8 pb-4 bg-gray-13">
                <div class="container mt-1">
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="mb-6">
                                <a href="#" class="d-inline-block">
                                <img src="<?= base_url('assets/img/logo-230-45.png') ?>" alt="">
                                </a>
                            </div>
                            <div class="mb-4">
                                <div class="row no-gutters">
                                    <div class="col-auto">
                                        <i class="ec ec-support text-primary font-size-56"></i>
                                    </div>
                                    <div class="col pl-3">
                                        <div class="font-size-13 font-weight-light">Имаш въпроси? Обади ни се!</div>
                                        <a href="tel:+359 896 830 406" class="font-size-20 text-gray-90">+359 896 830 406
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <h6 class="mb-1 font-weight-bold">Contact info</h6>
                                <address class="">
                                    17 Princess Road, London, Greater London NW1 8JR, UK
                                </address>
                            </div>
                            <div class="my-4 my-md-4">
                                <ul class="list-inline mb-0 opacity-7">
                                    <li class="list-inline-item mr-0">
                                        <a class="btn font-size-20 btn-icon btn-soft-dark btn-bg-transparent rounded-circle" href="#">
                                            <span class="fab fa-facebook-f btn-icon__inner"></span>
                                        </a>
                                    </li>
                                    <li class="list-inline-item mr-0">
                                        <a class="btn font-size-20 btn-icon btn-soft-dark btn-bg-transparent rounded-circle" href="#">
                                            <span class="fab fa-google btn-icon__inner"></span>
                                        </a>
                                    </li>
                                    <li class="list-inline-item mr-0">
                                        <a class="btn font-size-20 btn-icon btn-soft-dark btn-bg-transparent rounded-circle" href="#">
                                            <span class="fab fa-twitter btn-icon__inner"></span>
                                        </a>
                                    </li>
                                    <li class="list-inline-item mr-0">
                                        <a class="btn font-size-20 btn-icon btn-soft-dark btn-bg-transparent rounded-circle" href="#">
                                            <span class="fab fa-github btn-icon__inner"></span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="row">
                                <div class="col-12 col-md mb-4 mb-md-0">
                                    <h6 class="mb-3 font-weight-bold">Find it Fast</h6>
                                    <!-- List Group -->
                                    <ul class="list-group list-group-flush list-group-borderless mb-0 list-group-transparent">
                                        <li><a class="list-group-item list-group-item-action" href="../shop/product-categories-5-column-sidebar.html">Laptops & Computers</a></li>
                                        <li><a class="list-group-item list-group-item-action" href="../shop/product-categories-5-column-sidebar.html">Cameras & Photography</a></li>
                                        <li><a class="list-group-item list-group-item-action" href="../shop/product-categories-5-column-sidebar.html">Smart Phones & Tablets</a></li>
                                        <li><a class="list-group-item list-group-item-action" href="../shop/product-categories-5-column-sidebar.html">Video Games & Consoles</a></li>
                                        <li><a class="list-group-item list-group-item-action" href="../shop/product-categories-5-column-sidebar.html">TV & Audio</a></li>
                                        <li><a class="list-group-item list-group-item-action" href="../shop/product-categories-5-column-sidebar.html">Gadgets</a></li>
                                        <li><a class="list-group-item list-group-item-action" href="../shop/product-categories-5-column-sidebar.html">Car Electronic & GPS</a></li>
                                    </ul>
                                    <!-- End List Group -->
                                </div>

                                <div class="col-12 col-md mb-4 mb-md-0">
                                    <!-- List Group -->
                                    <ul class="list-group list-group-flush list-group-borderless mb-0 list-group-transparent mt-md-6">
                                        <li><a class="list-group-item list-group-item-action" href="../shop/product-categories-5-column-sidebar.html">Printers & Ink</a></li>
                                        <li><a class="list-group-item list-group-item-action" href="../shop/product-categories-5-column-sidebar.html">Software</a></li>
                                        <li><a class="list-group-item list-group-item-action" href="../shop/product-categories-5-column-sidebar.html">Office Supplies</a></li>
                                        <li><a class="list-group-item list-group-item-action" href="../shop/product-categories-5-column-sidebar.html">Computer Components</a></li>
                                        <li><a class="list-group-item list-group-item-action" href="../shop/product-categories-5-column-sidebar.html">Accesories</a></li>
                                    </ul>
                                    <!-- End List Group -->
                                </div>

                                <div class="col-12 col-md mb-4 mb-md-0">
                                    <h6 class="mb-3 font-weight-bold">Customer Care</h6>
                                    <!-- List Group -->
                                    <ul class="list-group list-group-flush list-group-borderless mb-0 list-group-transparent">
                                        <li><a class="list-group-item list-group-item-action" href="../shop/my-account.html">My Account</a></li>
                                        <li><a class="list-group-item list-group-item-action" href="../shop/track-your-order.html">Order Tracking</a></li>
                                        <li><a class="list-group-item list-group-item-action" href="../shop/wishlist.html">Wish List</a></li>
                                        <li><a class="list-group-item list-group-item-action" href="../home/terms-and-conditions.html">Customer Service</a></li>
                                        <li><a class="list-group-item list-group-item-action" href="../home/terms-and-conditions.html">Returns / Exchange</a></li>
                                        <li><a class="list-group-item list-group-item-action" href="../home/faq.html">FAQs</a></li>
                                        <li><a class="list-group-item list-group-item-action" href="../home/terms-and-conditions.html">Product Support</a></li>
                                    </ul>
                                    <!-- End List Group -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Footer-bottom-widgets -->
            <!-- Footer-copy-right -->
            <div class="bg-gray-14 py-2">
                <div class="container">
                    <div class="flex-center-between d-block d-md-flex">
                        <div class="mb-3 mb-md-0">© <a href="#" class="font-weight-bold text-gray-90">Electro</a> - All rights Reserved</div>
                        <div class="text-md-right">
                            <span class="d-inline-block bg-white border rounded p-1">
                                <img class="max-width-5" src="../../assets/img/100X60/img1.jpg" alt="Image Description">
                            </span>
                            <span class="d-inline-block bg-white border rounded p-1">
                                <img class="max-width-5" src="../../assets/img/100X60/img2.jpg" alt="Image Description">
                            </span>
                            <span class="d-inline-block bg-white border rounded p-1">
                                <img class="max-width-5" src="../../assets/img/100X60/img3.jpg" alt="Image Description">
                            </span>
                            <span class="d-inline-block bg-white border rounded p-1">
                                <img class="max-width-5" src="../../assets/img/100X60/img4.jpg" alt="Image Description">
                            </span>
                            <span class="d-inline-block bg-white border rounded p-1">
                                <img class="max-width-5" src="../../assets/img/100X60/img5.jpg" alt="Image Description">
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Footer-copy-right -->
        </footer>
        <!-- ========== END FOOTER ========== -->

        <!-- ========== SECONDARY CONTENTS ========== -->
        <!-- Account Sidebar Navigation -->
        <aside id="sidebarContent" class="u-sidebar u-sidebar__lg" aria-labelledby="sidebarNavToggler">
            <div class="u-sidebar__scroller">
                <div class="u-sidebar__container">
                    <div class="js-scrollbar u-header-sidebar__footer-offset pb-3">
                        <!-- Toggle Button -->
                        <div class="d-flex align-items-center pt-4 px-7">
                            <button type="button" class="close ml-auto"
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
                                <i class="ec ec-close-remove"></i>
                            </button>
                        </div>
                        <!-- End Toggle Button -->
                        <!-- Content -->
                        <div class="js-scrollbar u-sidebar__body">
                            <div class="u-sidebar__content u-header-sidebar__content">
                                <form class="js-validate" method="POST" action="<?=site_url('/login')?>">
                                    <!-- Login -->
                                    <div id="login" data-target-group="idForm">
                                        <!-- Title -->
                                        <header class="text-center mb-7">
                                        <h2 class="h4 mb-0">Добре дошли!</h2>
                                        <p>Влезте в профила си.</p>
                                        </header>
                                        <!-- End Title -->

                                        <!-- Form Group -->
                                        <div class="form-group">
                                            <div class="js-form-message js-focus-state">
                                                <label class="sr-only" for="signinEmail">Email или Бустат</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="signinEmailLabel">
                                                            <span class="fas fa-user"></span>
                                                        </span>
                                                    </div>
                                                    <input type="text" class="form-control" name="email_bustat" id="signinEmail" placeholder="Email или Бустат" aria-label="Email или Бустат" aria-describedby="signinEmailLabel" required
                                                    data-msg="Моля попълнете имейл или бустат!"
                                                    data-error-class="u-has-error"
                                                    data-success-class="u-has-success">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Form Group -->

                                        <!-- Form Group -->
                                        <div class="form-group">
                                            <div class="js-form-message js-focus-state">
                                              <label class="sr-only" for="signinPassword">Парола</label>
                                              <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="signinPasswordLabel">
                                                        <span class="fas fa-lock"></span>
                                                    </span>
                                                </div>
                                                <input type="password" class="form-control" name="password" id="signinPassword" placeholder="Password" aria-label="Password" aria-describedby="signinPasswordLabel" required
                                                   data-msg="Вашата парола е не валидна. Моля опитайте отново."
                                                   data-error-class="u-has-error"
                                                   data-success-class="u-has-success">
                                              </div>
                                            </div>
                                        </div>
                                        <!-- End Form Group -->
                                        <div class="mb-2">
                                            <button type="submit" class="btn btn-block btn-sm btn-primary transition-3d-hover">Влез</button>
                                        </div>

                                        <div class="text-center mb-4">
                                            <span class="small text-muted">Нямате акаунт?</span>
                                            <a class="js-animation-link small text-dark" href="javascript:;"
                                               data-target="#signup"
                                               data-link-group="idForm"
                                               data-animation-in="slideInUp">Заявка за дилър
                                            </a>
                                        </div>

                                    </div>
                                </form>
                                <form class="js-validate" method="POST" action="<?=site_url('/register')?>">
                                    <div id="signup" style="display: none; opacity: 0;" data-target-group="idForm">
                                        <!-- Title -->
                                        <header class="text-center mb-7">
                                        <h2 class="h4 mb-0">ЗАЯВКА ЗА ДИЛЪР.</h2>
                                        <p>Фирмени данни.</p>
                                        </header>
                                        <!-- End Title -->


                                        <!-- Form Group -->
                                        <div class="form-group">
                                            <div class="js-form-message js-focus-state">
                                            <label class="sr-only" for="signupConfirmPassword">Фирма</label>
                                                <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="signupConfirmPasswordLabel">
                                                        <span class="fas fa-briefcase"></span>
                                                    </span>
                                                </div>
                                                <input type="text" class="form-control" name="company" id="signupConfirmPassword" placeholder="* Име на фирма" aria-label="Confirm Password" aria-describedby="signupConfirmPasswordLabel" required
                                                data-msg="Име на фирма е задължително!"
                                                data-error-class="u-has-error"
                                                data-success-class="u-has-success">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Input -->
                                        
                                        <!-- Form Group -->
                                        <div class="form-group">
                                            <div class="js-form-message js-focus-state">
                                            <label class="sr-only" for="signupConfirmPassword">МОЛ</label>
                                                <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="signupConfirmPasswordLabel">
                                                        <span class="fas fa-user"></span>
                                                    </span>
                                                </div>
                                                <input type="text" class="form-control" name="mol" id="signupConfirmPassword" placeholder="* МОЛ (име, презиме, фамилия)" aria-label="Confirm Password" aria-describedby="signupConfirmPasswordLabel" required
                                                data-msg="МОЛ е задължително!"
                                                data-error-class="u-has-error"
                                                data-success-class="u-has-success">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Input -->


                                        <!-- Form Group -->
                                        <div class="form-group">
                                            <div class="js-form-message js-focus-state">
                                            <label class="sr-only" for="signupConfirmPassword">Булстат</label>
                                                <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="signupConfirmPasswordLabel">
                                                        <span class="fas fa-id-card"></span>
                                                    </span>
                                                </div>
                                                <input type="text" class="form-control" name="bulstat" id="signupConfirmPassword" placeholder="* Булстат" aria-label="Confirm Password" aria-describedby="signupConfirmPasswordLabel" required
                                                data-msg="Булстат е задължително!"
                                                data-error-class="u-has-error"
                                                data-success-class="u-has-success">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Input -->

                                        <!-- Form Group -->
                                        <div class="form-group">
                                            <div class="js-form-message js-focus-state">
                                            <label class="sr-only" for="signupConfirmPassword">Град</label>
                                                <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="signupConfirmPasswordLabel">
                                                        <span class="fas fa-city"></span>
                                                    </span>
                                                </div>
                                                <input type="text" class="form-control" name="city" id="signupConfirmPassword" placeholder="* Град" aria-label="Confirm Password" aria-describedby="signupConfirmPasswordLabel" required
                                                data-msg="Град е задължително!"
                                                data-error-class="u-has-error"
                                                data-success-class="u-has-success">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Input -->

                                        <!-- Form Group -->
                                        <div class="form-group">
                                            <div class="js-form-message js-focus-state">
                                            <label class="sr-only" for="signupConfirmPassword">Адрес</label>
                                                <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="signupConfirmPasswordLabel">
                                                        <span class="fas fa-address-book"></span>
                                                    </span>
                                                </div>
                                                <input type="text" class="form-control" name="address" id="signupConfirmPassword" placeholder="* Адрес" aria-label="Confirm Password" aria-describedby="signupConfirmPasswordLabel" required
                                                data-msg="Адрес е задължително!"
                                                data-error-class="u-has-error"
                                                data-success-class="u-has-success">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Input -->

                                        <!-- Form Group -->
                                        <div class="form-group">
                                            <div class="js-form-message js-focus-state">
                                            <label class="sr-only" for="signupConfirmPassword">Телефон</label>
                                                <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="signupConfirmPasswordLabel">
                                                        <span class="fas fa-phone"></span>
                                                    </span>
                                                </div>
                                                <input type="text" class="form-control" name="phone" id="signupConfirmPassword" placeholder="* Телефон" aria-label="Confirm Password" aria-describedby="signupConfirmPasswordLabel" 
                                                data-msg="Адрес е задължително!"
                                                data-error-class="u-has-error"
                                                data-success-class="u-has-success">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Input -->

                                        <!-- Form Group -->
                                        <div class="form-group">
                                            <div class="js-form-message js-focus-state">
                                                <label class="sr-only" for="recoverEmail">Email</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="recoverEmailLabel">
                                                            <span class="fas fa-envelope"></span>
                                                        </span>
                                                    </div>
                                                    <input type="email" class="form-control" name="email" id="recoverEmail" placeholder="Email" aria-label="Your email" aria-describedby="recoverEmailLabel"
                                                    data-msg="Моля въведете валдиен имейл"
                                                    data-error-class="u-has-error"
                                                    data-success-class="u-has-success">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Form Group -->        
                                        <div class="form-group">
                                            <div class="js-form-message js-focus-state">
                                                <label class="sr-only" for="agreeCheckbox">Съгласие с условията</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="agreeCheckboxLabel">
                                                            <span class="fas fa-check"></span>
                                                        </span>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" name="agree" id="agreeCheckbox" aria-label="Съгласие с условията" aria-describedby="agreeCheckboxLabel" required
                                                            data-msg="Моля да се съгласите с общите условия!"
                                                            data-error-class="u-has-error"
                                                            data-success-class="u-has-success">
                                                        <label class="custom-control-label" for="agreeCheckbox">Декларирам, че цялата предоставена от мен информация е пълна и вярна, както и че предоставям свободно и доброволно личните си данни и съм съгласен/а всички и всякакви предоставени от мен и/или публично достъпни мои лични данни да бъдат обработвани съобразно предвиденото в настоящата декларация(виж) , за целите, изрично посочени в настоящата декларация като съм запознат/а, че отказът ми за предоставяне на лични данни е основание създаденото от мен съобщение във формата за изпращане на съобщение да не бъде изпратено на "ВАЛПЕРС КОНСУЛТ" ЕООД. </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                        <div class="mb-2">
                                            <button type="submit" class="btn btn-block btn-sm btn-primary transition-3d-hover">Изпрати</button>
                                        </div>

                                        <div class="text-center mb-4">
                                            <span class="small text-muted">Вече имате акаунт?</span>
                                            <a class="js-animation-link small text-dark" href="javascript:;"
                                                data-target="#login"
                                                data-link-group="idForm"
                                                data-animation-in="slideInUp">Влез
                                            </a>
                                        </div>
                                    </div>
                                    <!-- End Signup -->

                                    <!-- Forgot Password -->
                                    <div id="forgotPassword" style="display: none; opacity: 0;" data-target-group="idForm">
                                        <!-- Title -->
                                        <header class="text-center mb-7">
                                            <h2 class="h4 mb-0">Recover Password.</h2>
                                            <p>Enter your email address and an email with instructions will be sent to you.</p>
                                        </header>
                                        <!-- End Title -->

                                        <!-- Form Group -->
                                        <div class="form-group">
                                            <div class="js-form-message js-focus-state">
                                                <label class="sr-only" for="recoverEmail">Your email</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="recoverEmailLabel">
                                                            <span class="fas fa-user"></span>
                                                        </span>
                                                    </div>
                                                    <input type="email" class="form-control" name="email" id="recoverEmail" placeholder="Your email" aria-label="Your email" aria-describedby="recoverEmailLabel" required
                                                    data-msg="Please enter a valid email address."
                                                    data-error-class="u-has-error"
                                                    data-success-class="u-has-success">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Form Group -->

                                        <div class="mb-2">
                                            <button type="submit" class="btn btn-block btn-sm btn-primary transition-3d-hover">Recover Password</button>
                                        </div>

                                        <div class="text-center mb-4">
                                            <span class="small text-muted">Remember your password?</span>
                                            <a class="js-animation-link small" href="javascript:;"
                                               data-target="#login"
                                               data-link-group="idForm"
                                               data-animation-in="slideInUp">Влез
                                            </a>
                                        </div>
                                    </div>
                                    <!-- End Forgot Password -->
                                </form>
                            </div>
                        </div>
                        <!-- End Content -->
                    </div>
                </div>
            </div>
        </aside>
        <!-- End Account Sidebar Navigation -->
        <!-- ========== END SECONDARY CONTENTS ========== -->
        <!-- Sidebar Navigation -->
        <aside id="sidebarContent1" class="u-sidebar u-sidebar--left" aria-labelledby="sidebarNavToggler1">
            <div class="u-sidebar__scroller">
                <div class="u-sidebar__container">
                    <div class="">
                        <!-- Toggle Button -->
                        <div class="d-flex align-items-center pt-3 px-4 bg-white">
                            <button type="button" class="close ml-auto"
                                aria-controls="sidebarContent1"
                                aria-haspopup="true"
                                aria-expanded="false"
                                data-unfold-event="click"
                                data-unfold-hide-on-scroll="false"
                                data-unfold-target="#sidebarContent1"
                                data-unfold-type="css-animation"
                                data-unfold-animation-in="fadeInLeft"
                                data-unfold-animation-out="fadeOutLeft"
                                data-unfold-duration="500">
                                <span aria-hidden="true"><i class="ec ec-close-remove"></i></span>
                            </button>
                        </div>
                        <!-- End Toggle Button -->
<!-- !!!!!!! MOBILNO FITLER -->
                        <!-- Content -->
                        <div class="js-scrollbar u-sidebar__body">
                            <div class="u-sidebar__content u-header-sidebar__content px-4">
                          
    <!-- !!!!!!!!!!! MOBILNO FILTER!!!!!!!!!!!!!!!!!! -->
                                <div class="mb-6">
                                    <div class="border-bottom border-color-1 mb-5">
                                        <h3 class="section-title section-title__sm mb-0 pb-2 font-size-18">Филтър</h3>
                                    </div>
                                    <form method="POST" action="">

                                    <div class="range-slider">
                                        <h4 class="font-size-14 mb-3 font-weight-bold">Price</h4>
                                        <!-- Range Slider -->
                                        <input class="js-range-slider" type="text"
                                        data-extra-classes="u-range-slider u-range-slider-indicator u-range-slider-grid"
                                        data-type="double"
                                        data-grid="false"
                                        data-hide-from-to="true"
                                        data-prefix="$"
                                        data-min="0"
                                        data-max="3456"
                                        data-from="0"
                                        data-to="3456"
                                        data-result-min="#rangeSliderExample3MinResult"
                                        data-result-max="#rangeSliderExample3MaxResult">
                                        <!-- End Range Slider -->
                                        <div class="mt-1 text-gray-111 d-flex mb-4">
                                            <span class="mr-0dot5">Price: </span>
                                            <span>$</span>
                                            <span id="rangeSliderExample3MinResult" class=""></span>
                                            <span class="mx-0dot5"> — </span>
                                            <span>$</span>
                                            <span id="rangeSliderExample3MaxResult" class=""></span>
                                        </div>
                                        <button type="submit" class="btn px-4 btn-primary-dark-w py-2 rounded-lg">Търси</button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Content -->
                    </div>
                </div>
            </div>
        </aside>
        <!-- End Sidebar Navigation -->

        <!-- Go to Top -->
        <a class="js-go-to u-go-to" href="#"
            data-position='{"bottom": 15, "right": 15 }'
            data-type="fixed"
            data-offset-top="400"
            data-compensation="#header"
            data-show-effect="slideInUp"
            data-hide-effect="slideOutDown">
            <span class="fas fa-arrow-up u-go-to__inner"></span>
        </a>
        <!-- End Go to Top -->

        <!-- JS Global Compulsory -->
        <script src="../../assets/vendor/jquery/dist/jquery.min.js"></script>
        <script src="../../assets/vendor/jquery-migrate/dist/jquery-migrate.min.js"></script>
        <script src="../../assets/vendor/popper.js/dist/umd/popper.min.js"></script>
        <script src="../../assets/vendor/bootstrap/bootstrap.min.js"></script>

        <!-- JS Implementing Plugins -->
        <script src="../../assets/vendor/appear.js"></script>
        <script src="../../assets/vendor/jquery.countdown.min.js"></script>
        <script src="../../assets/vendor/hs-megamenu/src/hs.megamenu.js"></script>
        <script src="../../assets/vendor/svg-injector/dist/svg-injector.min.js"></script>
        <script src="../../assets/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
        <script src="../../assets/vendor/jquery-validation/dist/jquery.validate.min.js"></script>
        <script src="../../assets/vendor/fancybox/jquery.fancybox.min.js"></script>
        <script src="../../assets/vendor/ion-rangeslider/js/ion.rangeSlider.min.js"></script>
        <script src="../../assets/vendor/typed.js/lib/typed.min.js"></script>
        <script src="../../assets/vendor/slick-carousel/slick/slick.js"></script>
        <script src="../../assets/vendor/appear.js"></script>
        <script src="../../assets/vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>

        <!-- JS Electro -->
        <script src="../../assets/js/hs.core.js"></script>
        <script src="../../assets/js/components/hs.countdown.js"></script>
        <script src="../../assets/js/components/hs.header.js"></script>
        <script src="../../assets/js/components/hs.hamburgers.js"></script>
        <script src="../../assets/js/components/hs.unfold.js"></script>
        <script src="../../assets/js/components/hs.focus-state.js"></script>
        <script src="../../assets/js/components/hs.malihu-scrollbar.js"></script>
        <script src="../../assets/js/components/hs.validation.js"></script>
        <script src="../../assets/js/components/hs.fancybox.js"></script>
        <script src="../../assets/js/components/hs.onscroll-animation.js"></script>
        <script src="../../assets/js/components/hs.slick-carousel.js"></script>
        <script src="../../assets/js/components/hs.quantity-counter.js"></script>
        <script src="../../assets/js/components/hs.range-slider.js"></script>
        <script src="../../assets/js/components/hs.show-animation.js"></script>
        <script src="../../assets/js/components/hs.svg-injector.js"></script>
        <script src="../../assets/js/components/hs.scroll-nav.js"></script>
        <script src="../../assets/js/components/hs.go-to.js"></script>
        <script src="../../assets/js/components/hs.selectpicker.js"></script>

        <!-- JS Plugins Init. -->
        <script>
            $(window).on('load', function () {
                // initialization of HSMegaMenu component
                $('.js-mega-menu').HSMegaMenu({
                    event: 'hover',
                    direction: 'horizontal',
                    pageContainer: $('.container'),
                    breakpoint: 767.98,
                    hideTimeOut: 0
                });
            });

            $(document).on('ready', function () {
                // initialization of header
                $.HSCore.components.HSHeader.init($('#header'));

                // initialization of animation
                $.HSCore.components.HSOnScrollAnimation.init('[data-animation]');

                // initialization of unfold component
                $.HSCore.components.HSUnfold.init($('[data-unfold-target]'), {
                    afterOpen: function () {
                        $(this).find('input[type="search"]').focus();
                    }
                });

                // initialization of HSScrollNav component
                $.HSCore.components.HSScrollNav.init($('.js-scroll-nav'), {
                  duration: 700
                });

                // initialization of quantity counter
                $.HSCore.components.HSQantityCounter.init('.js-quantity');

                // initialization of popups
                $.HSCore.components.HSFancyBox.init('.js-fancybox');

                // initialization of countdowns
                var countdowns = $.HSCore.components.HSCountdown.init('.js-countdown', {
                    yearsElSelector: '.js-cd-years',
                    monthsElSelector: '.js-cd-months',
                    daysElSelector: '.js-cd-days',
                    hoursElSelector: '.js-cd-hours',
                    minutesElSelector: '.js-cd-minutes',
                    secondsElSelector: '.js-cd-seconds'
                });

                // initialization of malihu scrollbar
                $.HSCore.components.HSMalihuScrollBar.init($('.js-scrollbar'));

                // initialization of forms
                $.HSCore.components.HSFocusState.init();

                // initialization of form validation
                $.HSCore.components.HSValidation.init('.js-validate', {
                    rules: {
                        confirmPassword: {
                            equalTo: '#signupPassword'
                        }
                    }
                });

                // initialization of forms
               // $.HSCore.components.HSRangeSlider.init('.js-range-slider');

                // initialization of show animations
                $.HSCore.components.HSShowAnimation.init('.js-animation-link');

                // initialization of fancybox
                $.HSCore.components.HSFancyBox.init('.js-fancybox');

                // initialization of slick carousel
                $.HSCore.components.HSSlickCarousel.init('.js-slick-carousel');

                // initialization of go to
                $.HSCore.components.HSGoTo.init('.js-go-to');

                // initialization of hamburgers
                $.HSCore.components.HSHamburgers.init('#hamburgerTrigger');

                // initialization of unfold component
                $.HSCore.components.HSUnfold.init($('[data-unfold-target]'), {
                    beforeClose: function () {
                        $('#hamburgerTrigger').removeClass('is-active');
                    },
                    afterClose: function() {
                        $('#headerSidebarList .collapse.show').collapse('hide');
                    }
                });

                $('#headerSidebarList [data-toggle="collapse"]').on('click', function (e) {
                    e.preventDefault();

                    var target = $(this).data('target');

                    if($(this).attr('aria-expanded') === "true") {
                        $(target).collapse('hide');
                    } else {
                        $(target).collapse('show');
                    }
                });

                // При събмит на формата
                $('#change_password_form').submit(function(event) {
                    event.preventDefault(); // Предотвратява реалното изпращане на формата
  
                    // Вземете стойността на паролата
                    var password = $('#signinPassword').val();


                    var passwordLength = password.length;

                    if (passwordLength >= 3 && passwordLength <= 80) {
                    // Изпратете паролата на контролера с AJAX
                        $.ajax({
                            url: '/change-password', // Променете този URL с адреса на вашия контролер
                            method: 'POST',
                            data: {
                                password: password // Изпращаме паролата като POST параметър
                            },
                            success: function(response) {
                                if (response.success) {
                                    alert(response.message)
                                    window.location.href = "https://test.valpers.com/";
                                } else {
                                    alert(response.message);                               
                                }   
                            },
                            error: function() {
                                // Обработка на грешки при изпращане на заявката
                                alert('Грешка при изпращане на паролата');
                            }
                        });
                    }else{
                        alert("Паролата трябва да е м/у 3 и 80 символа!")
                        location.reload();
                    }

                });
                
                var cartElement = $(".ec-shopping-bag");
                var nextSpanElement = cartElement.next("span");
                if (nextSpanElement.text() > 0){
                        setInterval(function() {
                        cartElement.toggleClass("flash");
                    }, 700); // Мигайте на всеки 1 секунда (1000 милисекунди)
                }
                
                var inputVal = ''; // Променлива за съхранение на текущата стойност на input полето
                
                $('#searchproduct-item').keyup(function () {
                    var searchName = $('#searchproduct-item').val();
                
                    if (searchName.length >= 2) { // Променено условие - пращай заявка всеки път след въвеждане на поне две букви
                        inputVal = searchName;
                
                        $.ajax({
                            method: 'POST',
                            url: '/products/search', 
                            data: { searchName: searchName },
                            success: function (response) {
                                var products = response; // Асумираме, че резултатът е JSON формат
                                var productListHTML = '';
                                
                                if(products.length == 0){
                                    productListHTML += '<div class="product-search"><div class="product-details"><h5>Не бяха открити продукти, отговарящи на критериите от търсенето.</h5></div></div>';
                                }else{
                                    // Итерирайте през списъка с продукти и генерирайте HTML за всякък продукт

                                        products.forEach(function (product) {
                                            productListHTML += '<a href="/product/' + product.product_id + '">';
                                            productListHTML += '<div class="product-search">';
                                            productListHTML += '<img src="/image/productImages/' + product.image + '" alt="' + product.product_name + '" class="product-image">';
                                            productListHTML += '<div class="product-details">';
                                            productListHTML += '<h4>' + product.product_name + '</h4>';
                                            productListHTML += '<p>Цена: ' + product.price + '</p>';
                                            productListHTML += '</div>';
                                            productListHTML += '</div>';
                                            productListHTML += '</a>';
                                        });
                                }    
                                 $('#search1-result').show();
                        
                                // Вмъкнете генерирания HTML във вашия DOM
                                $('#search1-result').html(productListHTML);
                                
                                // Приложете стиловете след създаването на елементите
                                setTimeout(function () {
                                    $('.product-search .product-image').css({
                                        'max-width': '50px',
                                        'max-height': '50px',
                                        'width': 'auto',
                                        'height': 'auto',
                                        'display': 'block',
                                        'margin': '0 auto',
                                        'float': 'left',
                                         'margin-right': '10px'
                                    });
                                    
                                    $('.product-search .product-details').css({
                                        'overflow': 'hidden',
                                    });
                                    
                                    // Позиционирайте данните върху другите елементи
                                    $('#search1-result').css({
                                        'position': 'absolute',
                                        'top': '10', // Променете този параметър според вашите изисквания
                                        'left': '10', // Променете този параметър според вашите изисквания
                                        'background-color': 'rgba(255, 255, 255, 1.0)', /* Задаване на бял фон */
                                        'padding': '10',
                                        'z-index': '1000000',
                                        'border': '1px solid #000'
                                    });
                                    
                                    $('#search1-result h4').css({
                                        'color': 'black'
                                    });
                                    
                                }, 0); // Изпълнява се след текущия JavaScript цикъл
                            }
                        });
                        // Добавете кода за затваряне на #search1-result при кликане извън него
                        $(document).on('click', function (event) {
                            if (!$(event.target).closest('#search1-result').length) {
                                $('#search1-result').hide();
                            }
                        });
                        
                    }
                });
                    
                

                // initialization of unfold component
                $.HSCore.components.HSUnfold.init($('[data-unfold-target]'));

                // initialization of select picker
                $.HSCore.components.HSSelectPicker.init('.js-select');
            });
        </script>
    </body>
</html>

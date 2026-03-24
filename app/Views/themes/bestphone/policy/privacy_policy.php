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
                                <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1 active" aria-current="page">Общи рпавила и условия</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- End breadcrumb -->
                </div>
            </div>
            <!-- End breadcrumb -->



            <div class="container">
                <div class="mb-4">
                    <h4 class="text-center">ОБЩИ ПРАВИЛА И УСЛОВИЯ</h4>
                </div>
            </div>

            <div class="container">





<div id="content" <strong>Определения</strong></p><p><strong>Продавач</strong> означава Валперс Консулт ЕООД, ЕИК 126608480, седалище и адрес на управление гр. Хасково, ул. Георги Кирков №93, и адрес за доставки и комуникации гр. Хасково бул. Никола Радев, №55 , +359 87 914 0194.</p><p>&nbsp;</p><p><strong>Купувач</strong> означава юридическо или физическо лице, което е влязло в търговски отношения с Продавача и/или което има потребителски профил(и) като такова на Интернет сайта на Продавача.</p><p>&nbsp;</p><p><strong>Потребител</strong> означава юридическо или физическо лице, което използва Интернет сайта на Продавача.</p><p>&nbsp;</p><p><strong>Страна/страни по Договора и/или Поръчката</strong> - това са Продавача и Купувача, поотделно или заедно.</p><p>&nbsp;</p><p><strong>Поръчка</strong> означава поръчка или заявка за доставка на стоки/услуги, изпратена от Купувача до Продавача през Интернет сайта на последния, електронна поща или по друг начин и средство за комуникация (включително по телефон), и приета и потвърдена за изпълнение от Продавача. Продавачът не е обвързан с никакви задължения и не носи каквато и да било отговорност спрямо Купувача за изпълнението или доставката на Поръчка, която не е била надлежно получена и/или писмено потвърдена от страна на Продавача. Доставка на стоки/услуги, извършена от Продавача на Купувача, се счита за приета и потвърдена от страна на Продавача поръчка, независимо по какъв начин е направена или дали е била писмено потвърдена или не от Продавача.</p><p>&nbsp;</p><p><strong>Договор</strong> означава писмен Рамков договор за доставка на стоки/услуги, сключен между Купувача и Продавача, както и договор за доставка на стоки/услуги в конкретни специфични случаи. Ако между страните няма подписан рамков или друг договор, като договор служат подписани от Купувача и Продавача фактура или проформа-фактура, издадени от Продавача.</p><p>&nbsp;</p><p><strong>Спецификация</strong> означава технически данни за стоките/услугите и друга информация, които определят стоките/услугите.</p><p>&nbsp;</p><p><strong>Стока</strong> означава всички продукти и материали, включително програмни продукти (софтуер), описани в съответния Договор и/или Поръчка.</p><p>&nbsp;</p><p><strong>Услуга</strong> означава дейност/и, описани в Договора и/или Поръчката, включващи проектиране, инсталации, настройки, консултации, обучение, внедряване, контрол, управление на проекти и др.</p><p>&nbsp;</p><p><strong>Хардуер</strong> означава техническо оборудване, предназначено да комуникира и функционира заедно със софтуер, включително компоненти, аксесоари, елементи, принадлежности към оборудването и/или всякаква комбинация между тях.</p><p>&nbsp;</p><p><strong>Програмен продукт (софтуер)</strong> означава набор от компютърни програми, съставени от софтуерни команди, които изпълняват определени функции.</p><p>&nbsp;</p><p><strong>Система</strong> означава комбинация от някои от компонентите хардуер, софтуер, услуги.</p><p>&nbsp;</p><p><strong>Условия</strong> означава Общите условия за продажба на стоки/услуги, посочени в настоящия документ, и условията, посочени в Договор, съгласно т. 1.6 по-горе.</p><p>&nbsp;</p><p><strong>Сайт</strong> означава Интернет сайта на Продавача - http://www.valpers.eu/</p><p>&nbsp;</p><p><strong>“ЗЗП”</strong>&nbsp;– означава Закон за защита на потребителите.<br> <strong>“ЗЗД”</strong>&nbsp;– означава Закон за задълженията и договорите.&nbsp;<br> <strong>“ЗПУ”</strong>&nbsp;– означава Закон за пощенските услуги.</p><p><strong>“</strong><strong>ГПК</strong><strong>”</strong> –&nbsp; означава Граждански процесуален кодекс.</p><p>&nbsp;</p><p><strong>ИЗМЕНЕНИЕ НА ОБЩИТЕ УСЛОВИЯ<br> </strong>Дружеството има право и може едностранно, по всяко време и без предварително предупреждение да модифицира настоящите Общи условия.<br> <br> Всички изменения на настоящите Общи условия ще се прилагат занапред.<br> <br> С всяко използване на Услугите и ресурсите на Сайта, включително и зареждането на Сайта в интернет, както и чрез натискане на електронна препратка в която и да е интернет страница от Сайта, Потребителят декларира, че е запознат с настоящите Общи условия, съгласява се с тях и се задължава да ги спазва.<br> <br> Продължаването на ползването на Сайта след извършени промени на Общите условия за ползване потвърждава приемането на измененията, които валидно обвързват Потребителя.<br> <br> Ако Потребителят не е съгласен с общите условия за ползване на сайта, то той следва да не използва Сайта или която и да е от Услугите.</p><p>&nbsp;</p><p><strong>СРОК И ПРЕКРАТЯВАНЕ НА СПОРАЗУМЕНИЕТО<br> </strong>Настоящите Общи условия се прилагат към всички Договори и/или Поръчки за продажба до Продавача. <strong><br> </strong>Настоящото споразумение влиза в сила незабавно от момента на неговото приемане от Потребителя и има действие до преустановяване ползването на услугите.</p><p>&nbsp;</p><p><strong>Поръчки</strong><strong> на стоки</strong></p><p>Поръчки могат да правят само потребители приели общите условия, част от които е и <span style="text-decoration: underline;"><span style="color: #3366ff;"><a style="color: #3366ff; text-decoration: underline;" href="/gdpr1">Политиката за поверителност</a></span></span>.&nbsp;Действието представлява волеизявление, което обвързва със силата на договор в смисъла на <strong>ЗЗД</strong> клиента и&nbsp;<strong>"</strong><strong>Валперс Консулт</strong><strong>"</strong> <strong>Е</strong><strong>ООД</strong>&nbsp;, в съответствие с описаните в настоящия документ Условия за ползване и правилата на Закона за защита на потребителите <strong>(ЗЗП).</strong></p><p>За да направите поръчка е необходимо да попълните съответния <span style="color: #000000;">формуляр</span>, като следвате процедурата. При поръчката клиентът задължително посочва изискваните данни, чрез които е възможно да се установи обратна връзка от наша страна. Клиентът посочва основните параметри на желаната от него стока, съгласно предоставените опции и вида на стоката (модел, цвят, брой и&nbsp; др.).&nbsp;Не е необходима регистрация, за да се извърши поръчка от Клиент на дребно.</p><p>Офертите съдържат основна информация за предлагания&nbsp; продукт съобразно вида му и указване на цената му. Част от офертите са придружени със снимков материал.</p><p>&nbsp;</p><p><strong>Бързата поръчка</strong> в сайта не може да начислява суми за доставка, тя е просто заявление за желание за покупка, което задължително е последвано от обаждане на наш служител за уточняване на подробности за доставка.<br> Бутон "Бърза поръчка" в сайта не начислява сума по наложен платеж, който е за сметка на купувача, освен ако изрично към информацията за даден продукт, не е посочено, че доставката е за сметка на магазина.</p><p>Посочването на неверни данни прави поръчката невалидна и тя не&nbsp; обвързва <strong>"</strong><strong>Валперс Консулт</strong><strong>"</strong> <strong>Е</strong><strong>ООД</strong>&nbsp; със задължението за изпълнение на доставката.<br> Цялата информация относно предлаганите за продажба чрез онлайн магазина стоки включително, но не само технически характеристики, гаранционни условия, начин на употреба и т.н. е предоставена от производителя респективно вносителя на съответната стока, като <strong>"</strong><strong>Валперс Консулт</strong><strong>"</strong> <strong>Е</strong><strong>ООД</strong>&nbsp; не носи, каквато и да е отговорност, при невярно, неправилно или неточно представена информация, вярна информация представена по заблуждаващ начин, при разминаване между представеното фактическо и действителното положение, както и при печатни грешки.</p><p>&nbsp;</p><p><strong>Цени и начин на плащане</strong></p><p>Всички представени в сайта цени са в български лева и са валидни единствено и само към момента на публикуването им, <strong>"</strong><strong>Валперс Консулт</strong><strong>"</strong> <strong>Е</strong><strong>ООД</strong> си запазва правото без предупреждение да ги променя по всяко време. Представени в сайта цени на стоки са крайни и включват всички данъци и такси без цената за доставка, която е посочена отделно.</p><p>Ползвателят може да заплати цената на поръчана от онлайн магазина стока като използва по свой избор един следните способи:</p><ol><li><strong>Наложен платеж</strong></li><li><strong> Банков превод</strong>.</li><li><strong>Плащане с дебитна/ кредитна карта посредством V-pos</strong>.</li></ol><p>Видове карти, които се приемат: дебитни, кредитни и бизнес карти Visa и Mastеrcard. Транзакциите се осъществяват посредством програмите за сигурност MasterCard Identity check и VISA Secure. От гледна точка на сигурност, максималната сума за плащане с карта е 5000 лв . Не съхраняваме данни за банковите карти, използвани за плащане чрез сайта. При необходимост от връщане на сума, платена с банкова карта, сумата се възстановява по картата, с която е извършено плащането.</p><p>&nbsp;</p><p>Независимо от избрания способ всички плащания се извършват само в български лева. С приемането на настоящите общи условия ползвателят дава своето изрично и безусловно съгласие да заплаща авансово на <strong>"</strong><strong>Валперс Консулт</strong><strong>"</strong> <strong>Е</strong><strong>ООД</strong>, цялата продажна цена на поръчана чрез онлайн магазина стока. В случай на плащане чрез “наложен платеж”при доставката купувачът получава от куриера фактура, в която е посочена поръчаната стока, дължимата за нея продажна цена, както и цената на доставка. Купувачът предава на куриера сума равна на общата сума (включваща цената на стоката и цената на доставка) посочена във фактурата, което се отбелязва в талона за приемо – предаване, (удостоверяващ предаването на стоката посочена във фактурата от куриера на купувача) който служи за разписка. С подписването талона за приемо – предаване купувачът овластява куриера да предаде от негово име и за негова сметка на <strong>"</strong><strong>Валперс Консулт</strong><strong>"</strong> <strong>Е</strong><strong>ООД </strong>сумата представляваща продажната цена на доставената стока.</p><p>&nbsp;</p><p><strong>Доставка</strong></p><p>Доставката на поръчана чрез онлайн магазина стока се извършва с куриер или собствен транспорт (важи само за дилъри на едро). Основните куриери които се ползват са ЕКОНТ и СПИДИ. При поръчки над 180 лв. с ДДС, доставката е БЕЗПЛАТНА. Доставки се извършват до 72 часа (3 работни дни) на посоченият адрес при условие, че адресът е актуален, а поръчката е потвърдена. За доставки на едро до населени места срокът за доставка е до&nbsp;3 работни дни, при условие че адресът е актуален, а поръчката е потвърдена.</p><p>Заплащането на дължимата цена на стоката става чрез превод на сумата по банков път или наложен платеж. Ако трето лице се ангажира с приемането и потвърждаването на получаването на поръчаната стока от името на първоначалния клиент, направил поръчката, се задължава да заплати формираната цена.</p><p>Продавачът извършва доставката на стоките само на територията на Република България.</p><p>При доставката от куриер клиента подписва талон за приемо – предаване, с което удостоверява точното изпълнение на поръчката.</p><p>Ако клиентът не бъде открит в срока за изпълнение на доставката на посочения адрес или не бъдат осигурени достъп и условия за предаване на стоката в регламентирания срок, поръчката се счита за невалидна и <strong>"</strong><strong>Валперс Консулт</strong><strong>"</strong> <strong>Е</strong><strong>ООД</strong> се освобождава от задължението да извърши доставката.</p><p>В случай, че клиентът потвърди желанието си да получи поръчаната стока и след изтичане на срока за доставка, в който не е бил намерен на посочения адрес, той поема за своя сметка разходите по доставката.</p><p>&nbsp;</p><p><strong>Връщане на стоки и отказване от договора от разстояние</strong></p><p>Право на отказ от сключения договор имат само тези клиенти, които са Потребители по смисъла на Закона за защита на потребителите. <br> Чл. 50. (Изм. - ДВ, бр. 61 от 2014 г., в сила от 25.07.2014 г.) Потребителят има право да се откаже от договора от разстояние или от договора извън търговския обект, без да посочва причина, без да дължи обезщетение или неустойка и без да заплаща каквито и да е разходи, с изключение на разходите, предвидени в чл. 54, ал. 3 и чл. 55, в 14-дневен срок, считано от датата на сключване на договора, като върне поръчаната стока при следните условия:</p><ol><li>Потребителят предварително писмено да информира <strong>"</strong><strong>Валперс Консулт</strong><strong>"</strong> <strong>Е</strong><strong>ООД</strong>, на следният имейл адрес: office@valpers.eu, че на основание чл. 55, ал.1 ЗЗП се отказва от договора, като задължително посочи: а) конкретен начин по който ще върне стоката; б) банкова сметка по която да му бъде възстановена заплатената от него цена на върнатата стока. За да упражни правото си на отказ, клиентът може да използва и <span style="text-decoration: underline;"><a href="index.php?route=account/return/add"><span style="color: #3366ff;"><strong>Формуляр</strong> </span></a></span>за упражняване на правото на отказ от договора от разстояние.</li><li>Стоката да бъде върната лично от потребителя или от упълномощено от него с писмено пълномощно с нотариална заверка на подписа лице в посочения по реда на предходната точка начин за връщане на стоката.</li><li>Поставената от производителя оригинална опаковка на стоката да не е отваряна и да не е нарушена цялостта на поставени от <strong>"</strong><strong>Валперс Консулт</strong><strong>"</strong> <strong>Е</strong><strong>ООД</strong>, защитни стикери.</li></ol><ol start="4"><li>Всички транспортни и други разходи по връщането на стоката са изцяло за сметка на потребителя. До момента на обратното предаване на стоката от страна на потребителя на <strong>"</strong><strong>Валперс Консулт</strong><strong>"</strong> <strong>Е</strong><strong>ООД</strong>, рискът от случайното и унищожаване или повредждане се носи изцяло от потребителя.</li></ol><p>&nbsp;</p><p>В случай, че потребител се възползва от правото си по чл. 55, ал.1ЗЗП при положение, че е изпълнил посочените по – горе условия, <strong>"</strong><strong>Валперс Консулт</strong><strong>"</strong> <strong>Е</strong><strong>ООД</strong> &nbsp;се задължава да му възстанови заплатената цена по банков път (по посочената по реда даден по – горе банкова сметка) в срок от 5 до 30 дни от връщането на стоката.</p><p>Върнатите стоки трябва да бъдат в същото състояние като при доставката, без наранявания, в изряден търговски вид и пълна окомплектовка с прилежащите аксесоари и документи. При несъответствие на външния вид на стоката, която потребителя връща към <strong>"</strong><strong>Валперс Консулт</strong><strong>" </strong><strong>Е</strong><strong>ООД</strong>, с искане за възстановяване на цялата заплатена сума за същата, <strong>"</strong><strong>Валперс Консулт</strong><strong>"</strong> <strong>Е</strong><strong>ООД</strong>, възстановява на потребителя 50 процента от стойността, заплатена за стоката.</p><p>Като несъответствие на външния вид на стоката се приема: разкъсани опаковки, отлепени заводски стикери и фолиа, следи от употреба на продукта, които не могат да позволят препродажбата му като нов. Закупената стока трябва да бъде върната във вид, в който е получена.</p><p>&nbsp;</p><p><strong>Рекламации се приемат, както следва:</strong> <br> При получаването на стоката потребителят е длъжен незабавно да я прегледа и в случай, че констатира явни недостатъци, липсата на някой от придружаващите я аксесоари и/или на който и да е от изискваните от българското законодателство документи, незабавно да информира лицето извършващо доставката. Ако не направи това, вещта се смята за одобрена, като потребителят губи правото по - късно да претендира, че стоката му е доставена с явни недостатъци, липсата на някой от придружаващите я аксесоари и/или на който и да е от изискваните от българското законодателство документи. Рекламации на закупени, чрез онлайн магазина стоки се извършват по правилата на ЗЗП и съобразно сроковете и условията на търговската им гаранция.</p><p>&nbsp;</p><p>&nbsp;</p><p><strong>Други</strong></p><p><strong>“ВАЛПЕРС КОНСУЛТ” ЕООД</strong> се ангажира да предоставя само услугите представени в сайта, по начина по който са представени. Цялата информация представена на сайта включително, но не само дизайн, наличности, цени и местонахождение на стоките е валидна единствено и само към момента на представянето и, като “ВАЛПЕРС КОНСУЛТ” ЕООД си запазва правото по – всяко време да я променя без предупреждение. Отговорност на потребителя е да проверява редовно условията за ползване на сайта, както и представената информация за цени, наличности и т.н за да е своевременно информиран в случай, че в същите са настъпили някакви промени. При всички случаи промяната има действие занапред и не засяга потвърдени от страна на “ВАЛПЕРС КОНСУЛТ” ЕООД преди извършването ѝ &nbsp;поръчки. В случай, че е необходимо допълнително одобрение от наша страна то следва да бъде дадено, в противен случай независимо от потвърждаване на поръчката същата ще бъде считана за невалидна. “ВАЛПЕРС КОНСУЛТ” ЕООД не носи отговорност за съдържанието и безопасността на сайтове, към които препращат линкове публикувани на настоящия сайт. Кликването върху такива линкове и използването на сайтове към които същите препращат се извършва от ползватели на настоящия сайт изцяло на собствен риск и отговорност. В случаите, когато това е необходимо ползвателите на сайта се задължават да предоставят коректно и пълно изискваните от тях данни.</p><p>&nbsp;</p><p><strong>“ВАЛПЕРС КОНСУЛТ” ЕООД</strong> не носи отговорност за неизпълнение на поръчка в случаите, когато ползвателят е посочил неверни непълни и/или неточни лични данни, включително когато е посочил непълен, неточен или фиктивен адрес. Задължение на ползвателя е да опазва конфиденциалността на предоставените му от <strong>“ВАЛПЕРС КОНСУЛТ” ЕООД</strong> потребителско име и парола. Всяка направена чрез онлайн магазина поръчка ще бъде считана от страна на <strong>“ВАЛПЕРС КОНСУЛТ” ЕООД</strong> за редовна, ако при направата ѝ &nbsp;са използвани потребителско име и парола, които системата е приела за валидни независимо от това дали същите са използвани от лице различно от титуляра или от неовластено лице. При закупуване на стоки чрез онлайн магазина не важат издаваните от <strong>“ВАЛПЕРС КОНСУЛТ” ЕООД</strong> ваучери и карти за отстъпка. Достъпът до ресурсите на сайта и онлайн магазина за некоректни ползватели ще бъде блокиран. Акаунтите на ползватели, които нарушат условията за ползване на сайта и онлайн магазина, както и акаунти на ползватели, които ги използват не по предназначение ще бъдат заличени. За неуредените в настоящите общи условия въпроси, се прилагат разпоредбите на действащото в Република България законодателство. Всички спорове по тълкуването и изпълнението на настоящите общи условия и по тълкуването и изпълнението на договорите за продажба от разстояние на поръчани от онлайн магазина стоки ще бъдат решавани със споразумение, в случай на непостигане на такова и ако подсъдността не е посочена императивно, спорът ще бъде отнасян за разрешаване от компетентния съд в гр. Хасково, съгласно правилата на родовата подсъдност по ГПК, а именно – Хасковски районен съд.</p><p>&nbsp;</p><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Ние използваме Retargeting.Biz (с офис на регистрация в Румъния, Букурещ, Сектор 2, ул. "Василе Лескар" 178, ет. 2, ДДС №RO34270947, ЕИК:J40/3525/23.03.2015, имейл info@retargeting.biz, телефонен номер +40-727-383-165), софтуер за маркетинг автоматизация за електронна търговия с цел анализиране, профилиране и изпращане на персонализирана комуникация и оферти.<br><br>• Тези действия нямат правно действие или друг значителен ефект върху потребителите. Единствените последици за потребителя при използване на методи за профилиране биха довели до получаване на персонализирани маркетингови оферти и намаления. Всеки потребител има право на отказ от профилиране и получаването на търговска комуникация, без да има друг ефект, различен от този на неполучаването на персонализирани маркетингови оферти и намаления.<br><br>• За целите на обработката на данни, профилиране (проследяване на действия) и взамоидействие с уебсайта, Retargeting.Biz събира и съхранява автоматично следните данни: имейл адрес на потребителят, телефонен номер, имена, пол, адрес, град, окръг, дата на раждане, номер (ID) на поръчката, код за намаление, стойност на код за намаление, стойност на поръчката, стойност на доставката, цена на продукти, продукт/и, вариации на продукти, IP, браузър, операционна система, бисквитка, местонахождение спрямо IP, дата и час, разгледани страници, категория/и, марка/и, щракване върху снимка, захождане с мишката върху бутон за добавяне в количката, скролване надолу-нагоре, добавяне в количката, премахване от количката, избор на вариации, добавяне в списък с желани, коментар, харесване и споделяне във Facebook, посещение на Помощни страници.<br><br>• Групата от таргетирани субекти са посетители, регистрирани потребители и клиенти на уебсайта, според случая и избраната услуга. Данните на посетителите ще бъдат съхранени за срок от 2 месеца, а данните на регистрираните потребители и клиенти ще бъдат съхранени за срок от 3 години.<br><br>• При доставяне на Услугата до Клиента, Retergeting.Biz използва услуги от трети страни (Подизпълнители) намиращи се в ЕИП и САЩ (само за Push Известия), а трансфера на лични данни се основава на Защитата на личните данни между ЕС и САЩ; данните се съхраняват само през периода на договора между двете страни;<br><br>• Бисквитки: Уебсайта трябва да използва "бисквитка" от първа страна, както и да предостави достъп до нейната информация на Retargeting.Biz. "Бисквитката" се поставя от уебсайта и по този начин може да се използва само във връзка с този уебсайт. Следователно връзката между вътрешното проследяване на потребителите на този уебсайт и проследяването им на други уебсайтове не е технически възможно чрез една ѝ съща "бисквитка".<br><br>• За да се отпишете или откажете (opt-out) от Retargeting.Biz, моля изпратете имейл до... (моля, постави тук своя имейл адрес за връзка с клиенти).</p>
 <script type="text/javascript">(function(){ra_key="KL3XU5Y0KIDBEN";ra_params={add_to_cart_button_id:'button-cart',price_label_id:'price_label_id',};var ra=document.createElement("script");ra.type="text/javascript";ra.async=true;ra.src=("https:"==document.location.protocol?"https://":"http://")+"tracking.retargeting.biz/v3/rajs/"+ra_key+".js";var s=document.getElementsByTagName("script")[0];s.parentNode.insertBefore(ra,s);})();function checkEmail(email){var regex=/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,9})+$/;return regex.test(email);};jQuery(document).ready(function($){jQuery("input[type='text']").blur(function(){if(checkEmail($(this).val())){_ra.setEmail({'email':$(this).val()});console.log('setEmail fired!');}});});function checkEmail(email){var regex=/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,9})+$/;return regex.test(email);};jQuery(document).ready(function($){jQuery("input[type='text']").blur(function(){if(checkEmail($(this).val())){_ra.setEmail({'email':$(this).val()});console.log('setEmail fired!');}});});var _ra=_ra||{};_ra.visitHelpPage={'visit':true};if(_ra.ready!==undefined){_ra.visitHelpPage();}</script> </div>





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
                $.HSCore.components.HSRangeSlider.init('.js-range-slider');

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
                    
                
                var cartElement = $(".ec-shopping-bag");
                var nextSpanElement = cartElement.next("span");
                if (nextSpanElement.text() > 0){
                        setInterval(function() {
                        cartElement.toggleClass("flash");
                    }, 700); // Мигайте на всеки 1 секунда (1000 милисекунди)
                }

                // initialization of unfold component
                $.HSCore.components.HSUnfold.init($('[data-unfold-target]'));

                // initialization of select picker
                $.HSCore.components.HSSelectPicker.init('.js-select');
            });
        </script>
    </body>
</html>

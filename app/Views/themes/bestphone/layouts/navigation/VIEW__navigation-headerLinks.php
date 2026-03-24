<?php
$_unfoldTtarget   = '#basicDropdownHover';
$_unfoldTtargetId = 'basicDropdownHover';

if (!empty($isCloned)) {
    $_unfoldTtarget   = '#basicDropdownHover1';
    $_unfoldTtargetId = 'basicDropdownHover1';
}
?>

<div class="d-none d-xl-inline-block">
    <!-- Nav -->
    <nav class="js-mega-menu navbar navbar-expand-md u-header__navbar u-header__navbar--no-space">

        <!-- Navigation -->
        <div id="navBar" class="collapse navbar-collapse u-header__navbar-collapse">
            <ul class="navbar-nav u-header__navbar-nav">

                <?php if (session()->has('user_id')): ?>
                    <!-- <li class="nav-item u-header__nav-item mx-1 rounded-bottom-pill rounded-top-left-pill border" style="background: #ffe203;box-shadow: 2px 2px 6px #a1a1a1;">
                        <a class="nav-link u-header__nav-link pl-2" href="<?= route_to('Shop-promo', 'promo=1') ?>"><i class="fa "></i>&nbsp;ПРОМОЦИИ</a>
                    </li>


                    <li class="nav-item u-header__nav-item mx-1 bg-danger rounded-bottom-pill rounded-top-left-pill border" style="box-shadow: 2px 2px 6px #a1a1a1;">
                        <a class="nav-link u-header__nav-link pl-2 text-white" href="<?= route_to('Shop-promo', 'new=1') ?>"><i class="fa "></i>&nbsp;НОВИ</a>
                    </li> -->

                <?php endif ?>

                <!-- ценови листи -->
                <?php if (session()->has('user_id')): ?>
                    <?php
                    // <li class="mx-1 nav-item hs-has-mega-menu u-header__nav-item nav-icon"> ... Ценови листи ... </li>
                    ?>
                    <!-- End ценови листи-->


                    <li class="mx-1 nav-item u-header__nav-item nav-icon">
                        <a class="px-1 nav-link u-header__nav-link" href="<?= route_to('Account-index') ?>"><i class="fa fa-user font-size-28" title="Моят акаутн"
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
                                data-unfold-animation-in="slideInUp"></i>&nbsp;<?= session()->has('user_id') ? '' : 'Вход за дилъри' ?></a>
                    </li>

                    <li class="mx-1 nav-item u-header__nav-item nav-icon">
                        <a class="px-1 nav-link u-header__nav-link" href="<?= route_to('Order-index') ?>" role="button"><i class="fa fa-clipboard-list font-size-28" title="Поръчки"
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
                                data-unfold-animation-in="slideInUp"></i>&nbsp;
                        </a>
                    </li>

                    <?php
                    // <li class="mx-1 nav-item u-header__nav-item nav-icon"><a class="px-1 nav-link u-header__nav-link" href="[Xml route]"> ... XML ... </a></li>
                    ?>

                    <?php if ($customConfig->isVisible['link_zadalzenia']): ?>
                        <li class="mx-1 nav-item u-header__nav-item nav-icon">
                            <a class="px-1 nav-link u-header__nav-link" href="#"><i class="fa fa-dollar-sign font-size-28"></i>&nbsp;Задължения</a>
                        </li>
                    <?php endif ?>
                <?php endif ?>

                <?php if (empty(session()->has('user_id'))): ?>
                    <li class="mx-1 nav-item u-header__nav-item">
                        <a class="btn btn-primary px-4 py-2 font-weight-bold d-flex align-items-center css-header-btn text-white"
                           href="<?= route_to('Account-index') ?>">
                            <i class="fa fa-sign-in-alt mr-2"></i>
                            Вход
                        </a>
                    </li>

                    <?php /*
                    <li class="mx-1 nav-item u-header__nav-item">
                        <a class="btn btn-secondary px-4 py-2 font-weight-bold d-flex align-items-center css-header-btn"
                           href="<?= route_to('Dilar-index') ?>">
                            <i class="fa fa-user-plus mr-2"></i>
                            Регистрация
                        </a>
                    </li>
                    */ ?>
                <?php endif; ?>

                <?php if ($customConfig->isVisible['link_marka']): ?>
                    <li class="mx-1 nav-item u-header__nav-item">
                        <a class="px-1 nav-link u-header__nav-link" href="<?= route_to('Brand-index') ?>"><i class="fas fa-envelope1 font-size-28"></i>&nbsp;Марки</a>
                    </li>
                <?php endif ?>
            </ul>
        </div>
        <!-- End Navigation -->
    </nav>
    <!-- End Nav -->
</div>

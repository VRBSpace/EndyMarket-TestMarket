<?php
$_userPreferences = array_column($userPreferences, null, 'modul_name');

$request = \Config\Services::request();
?>

<header class="fixed-top">
    <nav class="main-header navbar navbar-expand navbar-white p-0 px-2 mx-0 bg-dark" style="z-index:111">

        <!-- LEft navbar links -->
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a href="<?= route_to('/') ?>" class="nav-link text-white">
                    <i class="fa fa-home"></i>
                </a>
            </li>
        <h1>test</h1>
            <li class="nav-item">
                <p class="py-2 m-0 bold"><b style="color:#D3E3FC;letter-spacing: 3px;"><?= service('settings') -> get('App.general')['logo'] ?? '' ?></b></p>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a href="<?= route_to('/') ?>" class="nav-link text-white">
                    <i class="fa fa-home"></i>
                </a>
            </li>

            <?php if ($acl -> isAdmin == 'admin' || $acl -> is_allowed($userGroupId, 'menu', 'root.setings', 'isActive')): ?>
                <li class="nav-item dropdown">
                    <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle text-white"><i class="fa fa-sliders"></i>&nbsp;<?= lang('LANG__headerMenu.settings.txt') ?></a>

                    <ul class="dropdown-menu border-0 shadow">
                        <li class="dropdown-submenu">
                            <a class="dropdown-item dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-gears"></i>&nbsp;<?= lang('LANG__headerMenu.spisazi.sistem') ?>
                            </a>

                            <ul class="dropdown-menu">

                                <li>
                                    <!-- sql manager -->
                                    <a class="dropdown-item" href="javascript:;" onClick="MyWindow = window.open('/assets/plugins/_sql_Manager/index.php', 'SQL мениджър', 'width=800,height=700'); return false;">  <i class="fa fa-server"></i>&nbsp;<?= lang('LANG__headerMenu.settings.sqlManager') ?> </a>
                                </li>

                                <!-- loger -->
                                <li> 
                                    <a href="<?= route_to('LogViewer') ?>" class="dropdown-item">
                                        <i class="fa fa-server"></i>&nbsp;<?= lang('LANG__headerMenu.spisazi.logErr') ?></a>
                                </li>

                                <li title="Добавяне на пакетни актуализации">
                                    <a  class="dropdown-item" href="<?= route_to('PatchFile') ?>">
                                        <i class="fa fa-info-circle"></i>&nbsp;Добавяне на Patch
                                    </a>
                                </li> 
                            </ul>
                        </li>

                        <!-- общи настройки -->
                        <li>
                            <a href="<?= route_to('Nastrojka-0') ?>" class="dropdown-item">
                                <i class="fa fa-wrench"></i>&nbsp;<?= lang('LANG__headerMenu.settings.general') ?></a>
                        </li>

                        <!-- импорт/експорт -->
                        <li>
                            <a id="openExpImp" class="dropdown-item" href="javascript:;" data-route="<?= route_to('POPup_expImp-open') ?>">
                                <i class="fa fa-beer"></i>&nbsp;<?= lang('LANG__headerMenu.settings.expImp') ?></a>
                        </li>

                        <li>
                            <a id="fileManager" class="dropdown-item" href="javascript:;">
                                <i class="fa fa-file"></i>&nbsp;Мениджър на документи</a>
                        </li>
                    </ul>
                </li>
            <?php endif ?>

            <?php if ($acl -> isAdmin == 'admin' || $acl -> is_allowed($userGroupId, 'menu', 'root.lists', 'isActive')): ?>
                <li class="nav-item dropdown">
                    <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle text-white"><i class="fa fa-cog"></i>&nbsp;<?= lang('LANG__headerMenu.spisazi.txt') ?></a>

                    <ul class="dropdown-menu border-0 shadow">
                        <!-- шаблон -->
                        <li>
                            <a id="openShablon" class="dropdown-item pointer-cursor" href="javascript:;" data-route="<?= route_to('POPup_shablon-open') ?>">
                                <i class="fa fa-beer"></i>&nbsp;<?= lang('LANG__headerMenu.spisazi.shablon') ?></a>
                        </li>

                        <!-- списък езици -->
                        <!--  <li>
                                                <a id="openLanguage" class="dropdown-item" data-route="<? // route_to('POPup_language-open')            ?>" href="javascript:;"><i class="fa fa-language"></i>&nbsp;<? // lang('LANG__headerMenu.spisazi.spLanguage')            ?></a>
                                            </li>-->

                        <!-- списък валути -->
                        <!--  <li>
                                                <a id="openValuta" class="dropdown-item" data-route="<? // route_to('POPup_valuta-open')            ?>" href="javascript:;"><i class="fa fa-money"></i>&nbsp;<? // lang('LANG__headerMenu.spisazi.spValuta')            ?></a>
       </li>-->

                        <!-- списък мерни единици -->
                        <li>
                            <a id="openMqrka" class="dropdown-item" data-route="<?= route_to('POPup_mqrka-open') ?>" href="javascript:;"><i class="fa fa-balance-scale"></i>&nbsp;<?= lang('LANG__headerMenu.spisazi.spMqrka') ?></a>
                        </li>

                        <!--  потребители -->
                        <li class="dropdown-submenu">
                            <a href="<?= route_to('Users_index') ?>" class="dropdown-item">
                                <i class="fa fa-user-circle"></i>&nbsp;<?= lang('LANG__headerMenu.spisazi.spUsers') ?></a>
                        </li>

                        <!-- категории -->
                         <li class="dropdown-submenu">
                            <a id="spCat" class="dropdown-item pointer-cursor" data-route="<?= route_to('POPup_category-open2') ?>">
                                <i class="fa fa-sitemap"></i>&nbsp;<?= lang('LANG__headerMenu.spisazi.spCat') ?></a>
                        </li>

                        <!-- атрибути на категории -->
                        <?php if ($customConfig -> isVisible['categoryAttribute']): ?>
                            <li class="dropdown-submenu">

                                <a id="spCatAttr" class="dropdown-item pointer-cursor" data-route="<?= route_to('POPup_categoryAttr-open') ?>">
                                    <i class="fa fa-sitemap"></i>&nbsp;Характеристики на подкатегории</a>
                            </li>
                        <?php endif ?>

                        <!-- списък продукти -->
                        <li>
                            <?php
                            $_tab = $_GET['tab'] ?? '';

                            $_tab                = 'spProduct';
                            $_userPreferencesTip = $_userPreferences[$_tab] ?? [];
                            $perPageParams       = urldecode(http_build_query(
                                            [
                                                'tab'     => $_tab,
                                                'page'    => 1,
                                                'perPage' => $_userPreferencesTip['per_page'] ?? 10,
                                            ]
                                    ))
                            ?>
                            <a href="<?= route_to('Product__sp', $perPageParams) ?>" class="dropdown-item">
                                <i class="fa fa-cubes"></i>&nbsp;<?= lang('LANG__headerMenu.spisazi.spProduct') ?></a>
                        </li>

                        <!-- списък клиенти -->
                        <li>
                            <?php
                            $_tab                = $_ENV['app.gensoftEnable'] ? 'gensoftKlient' : 'klient';
                            $_userPreferencesTip = $_userPreferences[$_tab] ?? [];
                            $perPageParams       = urldecode(http_build_query(
                                            [
                                                'tab'     => $_tab,
                                                'page'    => 1,
                                                'perPage' => $_userPreferencesTip['per_page'] ?? 10,
                                            ]
                                    ))
                            ?>
                            <a class="dropdown-item" href="<?= route_to('Klient__sp', $perPageParams) ?>" >
                                <i class="fa fa-address-book"></i>&nbsp;<?= lang('LANG__headerMenu.spisazi.spKlient') ?></a>
                        </li>

                        <!-- списък наши фирми -->
                        <li>
                            <a class="dropdown-item" href="<?= route_to('NashiFirmi_sp') ?>">
                                <i class="fa fa-users"></i>&nbsp;<?= lang('LANG__headerMenu.spisazi.spNaFirmi') ?></a>
                        </li>

                    </ul>
                </li>
            <?php endif ?>


            <!--        <li id="shablonNav" class="nav-item">
                        <a class="nav-link" href="javascript:;" data-route="<? // route_to('popup_open_shablon')                                                                                                                                ?>" onclick=" modal_shablon.init();">
                            <i class="fa fa-files-o"></i> <span>Списъци</span>
                        </a>
                    </li>-->

            <li class="nav-item">
                <a class="nav-link text-white" data-widget="fullscreen" href="#" role="button">
                    <i class="fa fa-arrows-alt"></i>
                </a>
            </li>

            <!-- брояч на броя поръчки от сайт за деня -->
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle text-white" data-toggle="dropdown">
                    <span class="countOrderNotification <?= $orderNotificationsCount != 0 ? 'bg-danger' : '' ?> px-1 rounded-circle"><?= $orderNotificationsCount ?></span>
                    <i class="fa fa-bell" style="font-size:18px;"></i>
                    <ul class="dropdown-menu border-0 shadow">
                        <li>Нови поръчки от сайт <span class="countOrderNotification"><?= $orderNotificationsCount ?></span></li>
                    </ul>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white">
                    <i class="fa fa-user-circle"></i>&nbsp;
                    <span><?= $userName_label ?></span> 
                    <span style="font-size: 13px;">[<?= $userGroup_label ?>]</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/logout" style="color:#D3E3FC">
                    <i class="fa fa-sign-out"></i> <span>&nbsp;<?= lang('LANG__headerMenu.exit') ?></span>
                </a>
            </li>
        </ul>
    </nav>
</header>

<script src="/assets/plugins/ckfinder/ckfinder.js"></script>

<main class="wrapper">

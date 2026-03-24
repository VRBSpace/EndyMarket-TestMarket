<?php $_sort = $settings_portal['func']['sort'] ?? ''; ?>
<aside id="navigationSidebarLeft" class="sidebar border border u-sidebar u-sidebar--left" aria-labelledby="sidebarHeaderInvokerMenu">

    <div class="u-sidebar__scroller">
        <div class="u-sidebar__container">
            <div class="pb-0">
                <!-- Toggle Button -->
                <div class="position-absolute top-0 right-0 z-index-2 pt-4 pr-7">
                    <button type="button" class="close ml-auto"
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
                        <span aria-hidden="true"><i class="ec ec-close-remove text-gray-90 font-size-20"></i></span>
                    </button>
                </div>
                <!-- End Toggle Button -->

                <!-- Content -->
                <div class="js-scrollbar u-sidebar__body">
                    <div id="headerSidebarContent" class="u-sidebar__content1 u-header-sidebar__content">

                        <div class="border-bottom mx-auto mb-2">
                            <h5 class="section-title section-title__sm mb-0 p-2 font-weight-bold font-size-18 mx-1">Категории</h5>
                        </div>

                        <!-- Категории меню -->
                        <ul id="headerSidebarList" class="u-header-collapse__nav">
                            <?php foreach ($categories as $category): ?>
                                <li class="u-has-submenu u-header-collapse__submenu py-1 border-bottom2">
                                    <?php if (empty($category['children'])) : ?>
                                        <a class="u-header-collapse__nav-link u-header-collapse__nav-pointer" href="<?= '/shop?categoryId=' . $category['category_id'] ?>">
                                            <?= $category['category_name'] ?>
                                    </a>
                                        <?php else: ?> 
                                            <a class="u-header-collapse__nav-link u-header-collapse__nav-pointer py-0" href="javascript:;" data-target="#Collaps<?= $category['category_id'] ?>" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="Collaps<?= $category['category_id'] ?>">
                                                <?= $category['category_name'] ?>
                                            </a>

                                            <div id="Collaps<?= $category['category_id'] ?>" class="collapse" data-parent="#headerSidebarList">
                                                <ul id="<?= $category['category_id'] ?>" class="u-header-collapse__nav-list">
                                                    <?php foreach ($category['children'] as $child): ?>
                                                        <li class='py-1'>
                                                            <a class="u-header-collapse__submenu-nav-link" href="<?= '/shop?categoryId=' . $child['category_id'] . $_sort ?>">
                                                                <?= $child['category_name']; ?>
                                                            </a>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <!-- End Категории меню -->

                        <br>

                        <div class="border-bottom mx-auto mb-2">
                            <h5 class="section-title section-title__sm mb-0 p-2 font-weight-bold font-size-18 mx-1">Линкове</h5>
                        </div>

                        <ul class="u-header-collapse__nav">
                            <?php if (session() -> has('user_id')): ?>
                                <li class="u-has-submenu u-header-collapse__submenu">
                                    <a class="u-header-collapse__nav-link u-header-collapse__nav-pointer" href="javascript:;" data-target="#Collaps" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="Collaps">Ценови листи</a>
                                    <div id="Collaps" class="collapse" data-parent="#headerSidebarList">
                                        <ul class="u-header-collapse__nav-list">
                                            <?php foreach ($allCenovaLista as $cenovaLista): ?>
                                                <li><a class="u-header-collapse__submenu-nav-link" href="<?= route_to('CenovaLista-getCenovaListaById', $cenovaLista['id']) ?>"><?= $cenovaLista['offersName'] ?></a></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </li>

                                <li class="u-has-submenu u-header-collapse__submenu">
                                    <a class="u-header-collapse__nav-link " href="<?= route_to('Shop-promo', 'promo=1') ?>" role="button">Промоции</a>
                                </li> 

                                <?php if ($customConfig -> isVisible ['sidebarNavLeft']['link_new']): ?>
                                    <li class="u-has-submenu u-header-collapse__submenu">
                                        <a class="u-header-collapse__nav-link " href="<?= route_to('Shop-promo', 'new=1') ?>" role="button">Нови продукти</a>
                                    </li> 
                                <?php endif ?>

                                <?php if ($customConfig -> isVisible ['sidebarNavLeft']['link_feature']): ?>
                                    <li class="u-has-submenu u-header-collapse__submenu">
                                        <a class="u-header-collapse__nav-link " href="<?= route_to('Shop-promo', 'feature=1') ?>" role="button">Очаквани продукти</a>
                                    </li> 
                                <?php endif ?>

                                <?php if ($customConfig -> isVisible ['sidebarNavLeft']['link_fixPrice']): ?>

                                    <li class="u-has-submenu u-header-collapse__submenu">
                                        <a class="u-header-collapse__nav-link" href="<?= route_to('Shop-promo', 'fixPrice=1') ?>" role="button">Фиксирана цена</a>
                                    </li> 
                                <?php endif ?>

                                <li class="u-has-submenu u-header-collapse__submenu">
                                    <a class="u-header-collapse__nav-link" href="<?= route_to('Order-index') ?>" role="button">Поръчки</a>
                                </li> 

                                <li class="u-has-submenu u-header-collapse__submenu">
                                    <a class="u-header-collapse__nav-link" href="<?= site_url('/Account') ?>" role="button">Моят профил</a>
                                </li> 
                            <?php endif ?>

                            <?php if ($customConfig -> isVisible ['sidebarNavLeft']['link_brand']): ?>
                                <li class="u-has-submenu u-header-collapse__submenu">
                                    <a class="u-header-collapse__nav-link " href="<?= site_url('/Brand') ?>" role="button">Марки</a>
                                </li> 
                            <?php endif ?>
                        </ul>
                    </div>
                </div>
                <!-- End Content -->
            </div>
        </div>
    </div>
</aside>
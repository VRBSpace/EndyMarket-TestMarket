<?php
$_sort     = $settings_portal['func']['sort'] ?? '';
$urlParams = $_GET;
$isMainCategoryPage = $isMainCategoryPage ?? false;
$isPromoPage = $isPromoPage ?? false;

$_clearFiltersUrl = $isPromoPage ? current_url() . '?promo=1' : current_url();
?>
<style>
    .treeview *,
    .treeview ul,
    .treeview li {
        list-style-type: none;
    }
</style>
<div class="shop-filters">
    <div class="border-bottom border-color-1 mb-4">
        <h3 class="section-title section-title__sm mb-0 pb-2 font-size-18">
            <i class="fas fa-sliders-h"></i>&nbsp;Филтър
            <a class="font-size-12 float-right p-2 text-danger" href="<?= $_clearFiltersUrl ?>"><i class="fa fa-trash"></i>&nbsp;изчисти филтрите</a>
        </h3>
    </div>

    <!-- Ценови филтър Range Slider -->
    <?= view($views['filter-rangePrice']) ?>
    <!-- ------------- -->

    <!-- наличност филтър-->
    <?php if (!$isPromoPage): ?>
        <?= view($views['filter-nalichnost']) ?>
    <?php endif ?>
    <!-- ------------- -->

    <!-- производител филтър-->
    <?php if (!empty($brands) && !isset($_GET['catToModel'])) { ?>
        <div class="filter-block">
            <?= view($views['filter-manufacture']) ?>
        </div>
    <?php } ?>
    <!-- край производител филтър -->

    <!-- модели в  главна категория филтър-->
    <?php if (!$isPromoPage && (isset($_GET['categoryId']) || isset($_GET['searchName']))) { ?>
        <?= view($views['filter-modelByCategory']) ?>
    <?php } ?>
    <!-- ------------- -->

    <!-- главна категория и модел филтър-->
    <?php if (!$isPromoPage && isset($_GET['catToModel'])) { ?>
        <?= view($views['filter-rootCategory']) ?>
    <?php } ?>
    <!-- ------------- -->

    <!-- Филтър по атрибути -->
    <?php if (!$isPromoPage && !$isMainCategoryPage): ?>
        <div class="filter-block">
            <?= view($views['filter-productAttr_1']) ?>
        </div>
    <?php endif ?>
    <!-- край Филтър по атрибути -->

    <!-- Нови продукти филтър-->
    <? // view('shop/rightAside/VIEW__shop-rAside-latest-products')   
    ?>
    <!-- ------------- -->
</div>

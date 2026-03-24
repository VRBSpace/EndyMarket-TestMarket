<?= view("{$_ENV['app.theme']}/layouts/header/VIEW__head") ?>
<?= view("{$_ENV['app.theme']}/layouts/header/VIEW__header") ?>

<!-- ========== MAIN CONTENT ========== -->
<main role="main">
    <div class="container-fluid">    
        <div class="row mb-12 justify-content-between no-gutters">

            <div class="max-width-270 min-width-270">
                <!-- Basics Accordion -->
                <div class="sticky card border-0">
                    <?= view($views['megaMenu']) ?>

                    <div class="col flex-center-between align-self-center mb-2">
                        <small id="js-empty-sortBtn" class="text-danger hide font-size-13 btn float-right">
                            <i class="fa fa-trash"></i>
                            <a href="<?= $_SERVER['REQUEST_URI'] ?>">изчисти сортирането</a>
                        </small>
                    </div>
                </div>
                <!-- End Basics Accordion -->

                <!-- Ценови филтър Range Slider -->
                <? // view('shop/filter/VIEW__shop-filter-rangePrice') ?>
                <!-- край Ценови филтър -->

                <!-- производител филтър-->
                <? // view('shop/filter/VIEW__shop-filter-manufacture') ?>
                <!-- край производител филтър -->

                <!-- Филтър по атрибути -->
                <? // view('shop/filter/VIEW__shop-filter-productAttr') ?>
                <!-- край Филтър по атрибути -->
            </div>

            <div class="col-xl-9 col-wd-9gdot5">
                <div class="flex-center-between">

                    <!-- breadcrumb -->
                    <div class="bg-gray-13 bg-md-transparent font-size-14">
                        <div class="my-md-1">       
                            <?= $breadcrumbs ?>
                        </div>
                    </div>
                    <!-- End breadcrumb -->
                </div>

                <div class="sticky li">
                    <?php if (ISMOBILE): ?>
                        <a id="sidebarNavToggler1" class="btn px-1 font-weight-normal" href="javascript:;" role="button"
                           data-unfold-event="click"
                           data-unfold-hide-on-scroll="false"
                           data-unfold-target="#sidebarCenovaListaContent"
                           data-unfold-type="css-animation"
                           data-unfold-animation-in="fadeInLeft"
                           data-unfold-animation-out="fadeOutLeft"
                           data-unfold-duration="500">
                            <i class="fas fa-sliders-h"></i> 

                            <span class="ml-1">Филтри</span>
                        </a>
                    <?php endif ?>

                    <small id="js-clear-filter" class="text-danger hide font-size-13 btn px-0 py-2">
                        <i class="fa fa-trash"></i>
                        <a href="<?= $_SERVER['REQUEST_URI'] ?>">изчисти филтрите</a>
                    </small>

                    <div class="col">
                        <? // dd($res) ?>
                        <h4 class="section-title pb-3 "><b><?= $cenovaListaName ?></b></h4>  
                    </div>

                    <?= ISMOBILE ? '' : view($views['cenovaLista-controlBar']) ?>
                </div>

                <div id="js-html">
                    <?= view($views['cenovaLista-products']) ?>
                </div>
            </div>
            <!-- End Tab Content -->
        </div>
    </div>
</main>
<!-- ========== END MAIN CONTENT ========== -->

<?php
echo view("{$_ENV['app.theme']}/layouts/sidebar/VIEW__sidebar-account");  // Account Sidebar Navigation
if (ISMOBILE) {
    echo view("{$_ENV['app.theme']}/layouts/sidebar/VIEW__sidebar-cenovaLista_filters");
}
//Sidebar
echo view("{$_ENV['app.theme']}/layouts/footer/VIEW__footer");
?>

<script>
    const PRODUCTIDS = '<?= implode(',', array_column($res, 'product_id')) ?>';
</script>
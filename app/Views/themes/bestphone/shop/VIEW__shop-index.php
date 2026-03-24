<?php echo view("{$_ENV['app.theme']}/layouts/header/VIEW__head", ['seo' => $seo ?? null]); ?>
<?= view("{$_ENV['app.theme']}/layouts/header/VIEW__header") ?>

<main class="container" role="main">
    <div class="container-fluid <?= ISMOBILE ? '' : 'pt-4' ?>">
        <div class="row mb-12 justify-content-center no-gutters">

            <div class="col-2 px-2">
                <!-- Basics Accordion -->
                <?php if (!ISMOBILE): ?>
                    <div class="max-width-270 col-2 w-100 py-4 bg-white rounded-8" style="font-weight: 400;">
                        <?= view($views['shop-filter']) ?>
                    </div>
                <?php endif ?>
                <!-- End Basics Accordion -->
            </div>

            <!-- MOBILE: CSS-only модали -->
            <div id="modal_relationFilter" class="m-modal p-1" role="dialog" aria-modal="true" style="display:none">
                <div class="modal-header m-modal__header d-flex justify-content-between align-items-center text-white rounded-0" style="background-color: #3C4E58;">
                    <button id="js-returnBack" class="hide btn text-white p-2" type="button"><i class="fa fa fa-chevron-left"></i></button>
                    <h6 class="modal-title"></h6>

                    <button class="fw-bold m-close btn btn-white rounded-0" type="button">X</button>
                </div>

                <div class="m-modal__body m-list"></div>
            </div>

            <div id="refreshHtml" class="col-xl-10 col-wd-9gdot51 <?= ISMOBILE ? '' : 'px-2' ?>">
                <div id="msg-place"></div>

                <!-- breadcrumb -->
                <div class="flex-center-between mb-3 flex-column bg-white rounded-8 <?= ISMOBILE ? '' : 'py-2 px-4' ?>">
                    <div class="w-100 bg-gray-13 bg-md-transparent">
                        <div class="font-size-<?= ISMOBILE ? '12' : '16' ?>">       
                            <?= $breadcrumbs ?>
                        </div>
                    </div>

                    <?php if (!empty($currentCategoryChildren)): ?>
                        <div class="w-100 mt-3 mb-2">
                            <div class="js-slick-carousel u-slick shop-subcat-carousel px-1"
                                data-slides-show="6"
                                data-slides-scroll="1"
                                data-arrows-classes="d-none d-xl-block u-slick__arrow-normal u-slick__arrow-centered--y rounded-circle text-black font-size-24 z-index-2"
                                data-arrow-left-classes="fa fa-angle-left u-slick__arrow-inner--left left-n16"
                                data-arrow-right-classes="fa fa-angle-right u-slick__arrow-inner--right right-n20"
                                data-responsive='[{"breakpoint": 1200, "settings": {"slidesToShow": 5}}, {"breakpoint": 992, "settings": {"slidesToShow": 4}}, {"breakpoint": 768, "settings": {"slidesToShow": 3}}, {"breakpoint": 576, "settings": {"slidesToShow": 2}}]'>
                                <?php foreach ($currentCategoryChildren as $_child): ?>
                                    <div class="px-2">
                                        <a href="<?= esc($_child['href']) ?>" class="shop-subcat-card d-flex flex-column align-items-center text-center p-2">
                                            <div class="shop-subcat-card__img-wrap">
                                                <img
                                                    src="<?= !empty($_child['image']) ? $_ENV['app.imagePortalDir'] . $_child['image'] : $_ENV['app.noImage'] ?>"
                                                    class="shop-subcat-card__img"
                                                    onerror="this.onerror=null;this.src='<?= $_ENV['app.noImage'] ?>';"
                                                    alt="<?= esc($_child['name']) ?>">
                                            </div>
                                            <div class="shop-subcat-card__title mt-2"><?= esc($_child['name']) ?></div>
                                        </a>
                                    </div>
                                <?php endforeach ?>
                            </div>
                        </div>
                    <?php endif ?>
                    <!-- Контролен бар под филтрите -->
                    <div class="w-100 mb-2">
                        <?= view($views['shop-controlBar']) ?>
                    </div>
                </div>
                <!-- End breadcrumb -->


                <!-- Product list -->
                <div class="js-productTable tab-content" id="pills-tabContent">
                    <?= view($views['shop-productsGrid']) ?>
                </div>
                <!-- End Product list -->

                <!-- QUICK VIEW PRODUCT -->
                <div class="modal fade" id="quickViewModal" tabindex="-1" role="dialog" aria-labelledby="quickViewLabel" aria-hidden="true">
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

                <!-- Brand Carousel -->
                <? // view('layouts/carosel/VIEW__carosel-brands')  
                ?>
                <!-- End Brand Carousel -->
            </div>

            <div class="col-2"></div>


        </div>
    </div>
</main>

<?php
echo view("{$_ENV['app.theme']}/layouts/sidebar/VIEW__sidebar-account");  // Account Sidebar Navigation
echo view("{$_ENV['app.theme']}/layouts/sidebar/VIEW__sidebar-filters"); //Sidebar Navigation

echo view("{$_ENV['app.theme']}/layouts/footer/VIEW__footer");
?>

<script>
    var PRODUCTS_JSON = '<?= json_encode(array_column($products, 'product_id')) ?>';
    var IS_PROMO = '<?= isset($_GET['promo']) ?>';
    var SEARCHNAME = '<?= isset($_GET['searchName']) ? $_GET['searchName'] : '' ?>';
</script>

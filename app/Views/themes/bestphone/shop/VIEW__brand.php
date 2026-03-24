<?= view("{$_ENV['app.theme']}/layouts/header/VIEW__head") ?>
<?= view("{$_ENV['app.theme']}/layouts/header/VIEW__header") ?>

<?php $_sort         = $settings_portal['func']['sort'] ?? ''; ?>
<!-- ========== MAIN CONTENT ========== -->
<main role="main">
    <div class="container-fluid">    
        <div class="row mb-12 justify-content-between no-gutters">

            <div class="max-width-270 min-width-270">
                <!-- Basics Accordion -->
                <div class="sticky card border-0">
                    <?= view($views['megaMenu']) // ?>

                    <div class="col flex-center-between align-self-center mb-2">
                        <small id="js-empty-sortBtn" class="text-danger hide font-size-13 btn float-right">
                            <i class="fa fa-trash"></i>
                            <a href="<?= $_SERVER['REQUEST_URI'] ?>">изчисти сортирането</a>
                        </small>
                    </div>
                </div>
                <!-- End Basics Accordion -->
            </div>

            <div class="py-4 col-xl-9 col-wd-9gdot5">
                <?php
                $prevFirstChar = '';
                foreach ($brands as $key => $brand):
                    $first_char = $brand['brandTxt'][0];
                    ?>

                    <?php
                    if ($prevFirstChar !== $first_char) {
                        if ($key > 0) {
                            echo '</div>';
                        }
                        $_isMobile = ISMOBILE ? 'js-slick-carousel' : '';

                        echo "<h5 class='section-title px-2 m-0'>$first_char</h5><div class='row $_isMobile u-slick u-slick--gutters-0 position-static overflow-hidden u-slick-overflow-visble px-1 py-3 text-lh-38' data-arrows-classes='d-none d-xl-block u-slick__arrow-normal u-slick__arrow-centered--y rounded-circle text-black font-size-30 z-index-2' data-arrow-left-classes='fa fa-angle-left u-slick__arrow-inner--left left-n16'
     data-arrow-right-classes='fa fa-angle-right u-slick__arrow-inner--right right-n20'
     data-pagi-classes='d-xl-none text-center right-0 bottom-1 left-0 u-slick__pagination u-slick__pagination--dark u-slick__pagination--long mb-2 z-index-n1 mt-4 pt-1' data-slides-show='1'
     data-slides-scroll='1' >";
                    }
                    ?>  

                    <a class="col-2 link-hover__brand d-inline" href="<?= '/shop?brandId=' . $brand['brand_id'] . '&brandTxt=' . $brand['brandTxt'] . $_sort ?>">
                        <img data-img="<?= $_ENV['app.imageDir'] . $brand['image_brand'] ?>" class="p-2 lazy" src="<?= route_to('ResizeImage-thumb') ?>?img=<?= $brand['image_brand'] ?>&w=120&h=120" onerror="this.onerror=null;this.src='<?= $_ENV['app.noImage'] ?>';" width="120" height="120" alt="<?= $brand['brandTxt'] ?>">
                    </a>

                    <?php $prevFirstChar = $first_char ?>
                <?php endforeach ?>
            </div>
            <!-- End Tab Content -->
        </div>
    </div>
</main>
<!-- ========== END MAIN CONTENT ========== -->

<?php
echo view("{$_ENV['app.theme']}/layouts/sidebar/VIEW__sidebar-account");  // Account Sidebar Navigation
echo view("{$_ENV['app.theme']}/layouts/footer/VIEW__footer");
?>

<?= view("{$_ENV['app.theme']}/layouts/header/VIEW__head") ?>
<?= view("{$_ENV['app.theme']}/layouts/header/VIEW__header") ?>

<!-- ========== MAIN CONTENT ========== -->
<main class="container" role="main">
    <br>
    <div class="row mb-10 cart-table">
        <!-- <div class="container"> -->

        <div class="col-<?= ISMOBILE ? 12 : 9 ?> <?= ISMOBILE ? 'p-0' : '' ?>">
            <div id="js-cartItems">
                <div class="row justify-content-center <?= ISMOBILE ? 'mb-0' : 'mb-4' ?>">
                    <h2 class="section-title">
                        <b>Поръчка </b> 
                        <small>(</small>
                        <small>
                            <?= count($cartSessionProductsList) ?>&nbsp;продукт/а общо кол.  
                        </small>

                        <small id="js-label-totalQuantity"><?= array_sum(array_column($cartSessionProductsList, 'quantity')) ?></small>
                        <small>)</small>
                    </h2>
                </div>
                <?= ISMOBILE ? view($views['cart-itemsMobile']) : view($views['cart-items']) ?>
            </div>
        </div>
                <!-- </div> -->


        <hr>

        <div class="col <?= ISMOBILE ? 'css-border-red mt-3' : 'css-border-orange' ?>" style="box-shadow: <?= ISMOBILE ? '1px 2px 8px rgba(238,26,25,.35)' : '1px 2px 8px #FF5722' ?>">
            <?=
            view(empty(session('user_id')) ?
                            $views['cart-aside-user'] :
                            $views['cart-aside']);
            ?>
        </div> 
    </div>
</main>
<!-- ========== END MAIN CONTENT ========== -->
<?php
echo view("{$_ENV['app.theme']}/layouts/sidebar/VIEW__sidebar-account");  // Account Sidebar Navigation
echo view("{$_ENV['app.theme']}/layouts/footer/VIEW__footer");
?>

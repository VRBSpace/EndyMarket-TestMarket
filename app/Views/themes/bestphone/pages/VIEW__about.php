<?= view("{$_ENV['app.theme']}/layouts/header/VIEW__head") ?>
<?= view("{$_ENV['app.theme']}/layouts/header/VIEW__header") ?>

<?php
$bgImg = $_ENV['app.imagePortalDir'] . 'backgrounds/contacts-bg.jpg';
?>

<!-- HERO (Bootstrap only) -->
<section class="position-relative py-5 text-white">
    <div class="position-absolute w-100 h-100" style="inset:0; background:url('<?= htmlspecialchars($bgImg) ?>') center/cover no-repeat;"></div>
    <div class="position-absolute w-100 h-100" style="inset:0; background:rgba(0,0,0,.45);"></div>

    <div class="container position-relative">
        <div class="row">
            <div class="col-12 text-center">
                <h2 class="mb-3">За нас</h2>
                <ul class="list-unstyled d-flex flex-wrap justify-content-center align-items-center mb-0">
                    <li class="mx-2 my-1">
                        <a href="<?= site_url('/') ?>" class="text-white text-decoration-none d-inline-flex align-items-center">
                            <i class="fa fa-home mr-2"></i><span>Начало</span>
                        </a>
                    </li>
                  <li class="mx-2 my-1">
                        <a href="<?= route_to('Pages-about') ?>" class="text-white text-decoration-none d-inline-flex align-items-center">
                            <i class="fa fa-envelope mr-2"></i><span>За нас</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- /HERO -->


<div id="content" class="site-content py-5" tabindex="-1">
    <div class="container">
        <div id="content" class="col-sm-12">
            <?= $settings_portal['about'] ?? '' ?> <!-- in basecontroller  -->
        </div>
    </div><!-- .col-full -->
</div><!-- #content -->

<?php
echo view("{$_ENV['app.theme']}/layouts/sidebar/VIEW__sidebar-account"); // Account Sidebar Navigation
echo view("{$_ENV['app.theme']}/layouts/footer/VIEW__footer");
?>

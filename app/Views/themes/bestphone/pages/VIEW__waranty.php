<?= view("{$_ENV['app.theme']}/layouts/header/VIEW__head") ?>
<?= view("{$_ENV['app.theme']}/layouts/header/VIEW__header") ?>

<div id="content" class="site-content py-5" tabindex="-1">
    <div class="container">
        <?= $settings_portal['waranty'] ?? '' ?> <!-- in basecontroller  -->
    </div>
</div><!-- #content -->

<?php
echo view("{$_ENV['app.theme']}/layouts/sidebar/VIEW__sidebar-account"); // Account Sidebar Navigation
echo view("{$_ENV['app.theme']}/layouts/footer/VIEW__footer");
?>

<?= view("{$_ENV['app.theme']}/layouts/header/VIEW__head") ?>
<?= view("{$_ENV['app.theme']}/layouts/header/VIEW__header") ?>

<main id="content" role="main" class="min-vh-50 account-page">

    <div class="container">
        <div class="mb-4">
            <h1 class="text-center fw-bolder">Моят профил</h1>
            <h6 class="text-center"><?= session('username') ?> </h6>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row mb-8">

            <div class="col-xl-12 col-wd-12gdot5">
                <div id="msg-place"></div>

                <div class="row">
                    <div class="col">
                        <div class="position-relative bg-white text-center z-index-2">
                            <ul class="nav nav-classic nav-tab justify-content-center" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active " data-toggle="pill" href="#customer-tab" role="tab">
                                        <div class="d-md-flex justify-content-md-center align-items-md-center">
                                            <i class="fa fa-user">&nbsp;</i>
                                            Данни на потребителя
                                        </div>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="pill" href="#delivery-tab" role="tab">
                                        <div class="d-md-flex justify-content-md-center align-items-md-center">
                                            <i class="fa fa-truck">&nbsp;</i>
                                            Данни за доставка
                                        </div>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="pill" href="#userLoginData" role="tab">
                                        <div class="d-md-flex justify-content-md-center align-items-md-center">
                                            <i class="fa fa-key">&nbsp;</i>
                                            Смяна на парола
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Tab Content -->
                        <div id="pills-tabContent" class="tab-content d-flex justify-content-center" >

                            <div id="customer-tab" class="tab-pane text-center <?= ISMOBILE ? '' : 'w-40' ?> fade pt-2 show active"  role="tabpanel">
                                <?= view($views['acc-customerData']) ?>
                            </div>

                            <div id="delivery-tab" class="tab-pane container fade pt-2" role="tabpanel">
                                <?= view($views['acc-deliveryData']) ?>
                            </div>

                            <div id="userLoginData" class="tab-pane text-center <?= ISMOBILE ? '' : 'w-35' ?> fade pt-2"  role="tabpanel">
                                <?= view($views['acc-changePassword']) ?>
                            </div>
                        </div>
                        <!-- End Tab Content -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<!-- ========== END MAIN CONTENT ========== -->
<!-- ========== END MAIN CONTENT ========== -->
<?php
echo view("{$_ENV['app.theme']}/layouts/sidebar/VIEW__sidebar-account"); // Account Sidebar Navigation
echo view("{$_ENV['app.theme']}/layouts/footer/VIEW__footer");
?>

<?= view("{$_ENV['app.theme']}/layouts/header/VIEW__head") ?>
<?= view("{$_ENV['app.theme']}/layouts/header/VIEW__header") ?>

<!-- ========== MAIN CONTENT ========== -->
<main role="main">

    <div class="container">

        <div class="my-4 my-xl-8">
            <div class="d-flex justify-content-center">                                                            
                <div class="col-md-5">
                    <!-- Title -->
                    <div class="border-bottom border-color-1 mb-6">
                        <h3 class="d-inline-block section-title mb-0 pb-2 font-size-26">ПОТРЕБИТЕЛСКИ ВХОД</h3>


                        <?php if (session() -> has('error')): ?>
                            <br />
                            <span class="text-danger"><?= session('error') ?></span>
                        <?php endif; ?>

                        <?php if (session() -> has('message')): ?>
                            <br />
                            <span class="text-success"><?= session('message') ?></span>
                        <?php endif; ?>
                    </div>
                    <!-- End Title -->

                    <form id="login-form" class="js-validate" novalidate="novalidate" method="POST" action="<?= site_url('/login') ?>">
                        <!-- Form Group -->
                        <div class="js-form-message form-group">
                            <label class="form-label" for="signinSrEmailExample3">Email
                                <span class="text-danger">*</span>
                            </label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="signinEmailLabel">
                                        <span class="fas fa-user"></span>
                                    </span>
                                </div>

                                <input type="text" class="form-control" name="email_bustat" id="signinEmail" placeholder="Email" aria-label="Email или Бустат" aria-describedby="signinEmailLabel" required
                                       data-msg="Моля попълнете имейл или бустат!"
                                       data-error-class="u-has-error"
                                       data-success-class="u-has-success">
                            </div>
                        </div>
                        <!-- End Form Group -->

                        <!-- Form Group -->
                        <div class="js-form-message form-group">
                            <label class="form-label" for="signinSrPasswordExample2">Парола <span class="text-danger">*</span></label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="signinPasswordLabel">
                                        <span class="fas fa-lock"></span>
                                    </span>
                                </div>

                                <input type="password" class="form-control" name="password" id="signinSrPasswordExample2" placeholder="Въведете парола" aria-label="Password" required
                                       data-msg="Your password is invalid. Please try again."
                                       data-error-class="u-has-error"
                                       data-success-class="u-has-success">
                            </div>

                        </div>
                        <!-- End Form Group -->

                        <!-- Button -->
                        <div class="mb-1">
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary-orange px-5 text-white rounded-0">Вход</button>
                            </div>
                            <p class="mb-2">
                                <a href="<?= route_to('Account-forgotPassword') ?>">Забравена парола?</a>
                            </p>
                            <p class="mb-3">
                                Нямате профил? <a href="<?= route_to('Account-register') ?>">Регистрирай се тук</a>.
                            </p>
                        </div>
                        <!-- End Button -->
                    </form>
                </div>

                <!--                <div class="col-md-1 d-none d-md-block">
                                    <div class="flex-content-center h-100">
                                        <div class="width-1 bg-1 h-100"></div>
                                        <div class="width-50 height-50 border border-color-1 rounded-circle flex-content-center font-italic bg-white position-absolute">или</div>
                                    </div>
                                </div>-->

            </div>
        </div>
    </div>

    <div class="container">
        <div class="row mb-8">
            <div class="d-none d-xl-block col-xl-3 col-wd-2gdot5"></div>

            <div class="col-xl-9 col-wd-9gdot5">
                <div id="msg-place"></div>
            </div>
        </div>
    </div>
</main>
<!-- ========== END MAIN CONTENT ========== -->

<?php
echo view("{$_ENV['app.theme']}/layouts/sidebar/VIEW__sidebar-account");  // Account Sidebar Navigation
echo view("{$_ENV['app.theme']}/layouts/footer/VIEW__footer");
?>



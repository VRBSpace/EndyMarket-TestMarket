<?= view("{$_ENV['app.theme']}/layouts/header/VIEW__head") ?>
<?= view("{$_ENV['app.theme']}/layouts/header/VIEW__header") ?>

<main role="main">
    <div class="container">
        <div class="my-4 my-xl-8">
            <div class="d-flex justify-content-center">
                <div class="col-md-5">
                    <div class="border-bottom border-color-1 mb-6">
                        <h3 class="d-inline-block section-title mb-0 pb-2 font-size-26">ЗАБРАВЕНА ПАРОЛА</h3>

                        <?php if (session() -> has('error')): ?>
                            <br />
                            <span class="text-danger"><?= session('error') ?></span>
                        <?php endif; ?>

                        <?php if (session() -> has('message')): ?>
                            <br />
                            <span class="text-success"><?= session('message') ?></span>
                        <?php endif; ?>
                    </div>

                    <p class="mb-4">Въведете email адреса си и ще изпратим линк за смяна на паролата.</p>

                    <form class="js-validate" novalidate="novalidate" method="POST" action="<?= route_to('Account-forgotPassword') ?>">
                        <div class="js-form-message form-group">
                            <label class="form-label" for="resetEmail">Email <span class="text-danger">*</span></label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <span class="fas fa-envelope"></span>
                                    </span>
                                </div>

                                <input type="email" class="form-control" name="email" id="resetEmail" placeholder="Въведете email" required
                                       value="<?= esc(old('email')) ?>"
                                       data-msg="Моля въведете валиден email."
                                       data-error-class="u-has-error"
                                       data-success-class="u-has-success">
                            </div>
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary-orange px-5 text-white rounded-0">Изпрати линк</button>
                        </div>

                        <p class="mb-0">
                            <a href="<?= route_to('Account-index') ?>">Обратно към вход</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
echo view("{$_ENV['app.theme']}/layouts/sidebar/VIEW__sidebar-account");
echo view("{$_ENV['app.theme']}/layouts/footer/VIEW__footer");
?>

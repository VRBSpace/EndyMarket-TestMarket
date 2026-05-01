<?= view("{$_ENV['app.theme']}/layouts/header/VIEW__head") ?>
<?= view("{$_ENV['app.theme']}/layouts/header/VIEW__header") ?>

<main role="main">
    <div class="container">
        <div class="my-4 my-xl-8">
            <div class="d-flex justify-content-center">
                <div class="col-md-5">
                    <div class="border-bottom border-color-1 mb-6">
                        <h3 class="d-inline-block section-title mb-0 pb-2 font-size-26">НОВА ПАРОЛА</h3>

                        <?php if (session() -> has('error')): ?>
                            <br />
                            <span class="text-danger"><?= session('error') ?></span>
                        <?php endif; ?>
                    </div>

                    <form class="js-validate" novalidate="novalidate" method="POST" action="<?= route_to('Account-resetPassword', $token) ?>">
                        <div class="js-form-message form-group">
                            <label class="form-label" for="resetPassword">Нова парола <span class="text-danger">*</span></label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </span>
                                </div>

                                <input type="password" class="form-control" name="password" id="resetPassword" placeholder="Въведете нова парола" required
                                       autocomplete="new-password" minlength="8"
                                       pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}"
                                       data-msg="Моля въведете валидна парола."
                                       data-error-class="u-has-error"
                                       data-success-class="u-has-success">
                            </div>

                            <small class="form-text text-muted">Минимум 8 символа, поне 1 малка буква, 1 главна буква, 1 цифра и 1 специален символ.</small>
                        </div>

                        <div class="js-form-message form-group">
                            <label class="form-label" for="resetPasswordConfirm">Потвърди парола <span class="text-danger">*</span></label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </span>
                                </div>

                                <input type="password" class="form-control" name="password_confirm" id="resetPasswordConfirm" placeholder="Потвърдете новата парола" required
                                       autocomplete="new-password"
                                       data-msg="Моля потвърдете паролата."
                                       data-error-class="u-has-error"
                                       data-success-class="u-has-success">
                            </div>
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary-orange px-5 text-white rounded-0">Запази новата парола</button>
                        </div>
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

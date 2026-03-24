<?= view("{$_ENV['app.theme']}/layouts/header/VIEW__head") ?>
<?= view("{$_ENV['app.theme']}/layouts/header/VIEW__header") ?>

<main role="main">
    <div class="container">
        <div class="my-4 my-xl-8">
            <div class="d-flex justify-content-center">
                <div class="col-md-5">
                    <div class="border-bottom border-color-1 mb-6">
                        <h3 class="d-inline-block section-title mb-0 pb-2 font-size-26">РЕГИСТРАЦИЯ</h3>
                        <?php if (session() -> has('error')): ?>
                            <br />
                            <span class="text-danger"><?= session('error') ?></span>
                        <?php endif; ?>
                    </div>

                    <form id="register-form" class="js-validate" novalidate="novalidate" method="POST" action="<?= route_to('Account-register') ?>">
                        <div class="js-form-message form-group">
                            <label class="form-label" for="registerFullName">Имена <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><span class="fas fa-user"></span></span>
                                </div>
                                <input type="text" class="form-control" name="full_name" id="registerFullName" value="<?= old('full_name') ?>" placeholder="Въведете име и фамилия" required>
                            </div>
                        </div>

                        <div class="js-form-message form-group">
                            <label class="form-label" for="registerPhone">Телефон <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><span class="fas fa-phone"></span></span>
                                </div>
                                <input type="tel" class="form-control" name="phone" id="registerPhone" value="<?= old('phone') ?>" placeholder="Въведете телефон" required>
                            </div>
                        </div>

                        <div class="js-form-message form-group">
                            <label class="form-label" for="registerEmail">Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><span class="fas fa-envelope"></span></span>
                                </div>
                                <input type="email" class="form-control" name="email" id="registerEmail" value="<?= old('email') ?>" placeholder="Въведете email" required>
                            </div>
                        </div>

                        <div class="js-form-message form-group">
                            <label class="form-label" for="registerPassword">Парола <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><span class="fas fa-lock"></span></span>
                                </div>
                                <input type="password" class="form-control" name="password" id="registerPassword" placeholder="Минимум 8 символа" minlength="8" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}" title="Минимум 8 символа, поне 1 малка, 1 главна буква, 1 цифра и 1 специален символ." required>
                            </div>
                            <small class="form-text text-muted">Изискване: минимум 8 символа, поне 1 малка буква, 1 главна буква, 1 цифра и 1 специален символ.</small>
                        </div>

                        <div class="js-form-message form-group">
                            <label class="form-label" for="registerPasswordConfirm">Потвърди парола <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><span class="fas fa-lock"></span></span>
                                </div>
                                <input type="password" class="form-control" name="password_confirm" id="registerPasswordConfirm" placeholder="Повтори паролата" required>
                            </div>
                        </div>

                        <div class="mb-1">
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary-orange px-5 text-white rounded-0">Регистрация</button>
                            </div>
                            <p class="mb-3">
                                Имате профил? <a href="<?= route_to('Account-index') ?>">Влезте оттук</a>.
                            </p>
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

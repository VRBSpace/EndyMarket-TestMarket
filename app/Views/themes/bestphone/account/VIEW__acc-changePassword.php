
<form id="form-changePassword" class="js-validate" data-route="<?= route_to('Account-changePassword') ?>" data-skip-preloader method="post">
    <!-- Login -->
    <div id="login" data-target-group="idForm">
        <!-- Title -->
        <header class="text-center mb-7">
            <?= empty(session('is_password_changed')) ? '<h6 class="text-danger">При първоначално влизане е необходимо за промените паролата си за вход</h6>' : '' ?>
        </header>
        <!-- End Title -->

        <!-- Form Group -->
        <div class="form-group text-left">
            <div class="mb-2"><strong>Име:</strong> <?= esc($user['username'] ?? session('username') ?? '-') ?></div>
            <div><strong>Email:</strong> <?= esc($user['email_login'] ?? '-') ?></div>
            <input type="hidden" name="email_bustat" id="signinEmail" value="<?= esc($user['email_login'] ?? '') ?>">
        </div>
        <!-- End Form Group -->

        <!-- Form Group -->
        <div class="form-group">
            <div class="js-form-message js-focus-state1">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="signinPasswordLabel">
                            <span class="fas fa-lock"></span>
                        </span>
                    </div>

                    <input type="password" class="form-control" name="password" id="signinPassword" placeholder="въведете нова парола" autocomplete="new-password" minlength="8" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}" title="Минимум 8 символа, поне 1 малка, 1 главна буква, 1 цифра и 1 специален символ."
                           data-msg="Вашата парола е не валидна. Моля опитайте отново."
                           data-error-class="u-has-error"
                           data-success-class="u-has-success" 
                           value="">
                </div>
                <small class="form-text text-muted">Изискване: минимум 8 символа, поне 1 малка буква, 1 главна буква, 1 цифра и 1 специален символ.</small>
            </div>
        </div>
        <!-- End Form Group -->

        <!-- Form Group -->
        <div class="form-group">
            <div class="js-form-message js-focus-state1">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </span>
                    </div>

                    <input type="password" class="form-control" name="password_confirm" id="signinPasswordConfirm" placeholder="потвърдете новата парола" autocomplete="new-password"
                           data-msg="Моля потвърдете паролата."
                           data-error-class="u-has-error"
                           data-success-class="u-has-success" 
                           value="">
                </div>
            </div>
        </div>
        <!-- End Form Group -->
    </div>
</form>

<button class="btn btn-primary-orange w-100 rounded-0" type="submit" form="form-changePassword">Запис</button>


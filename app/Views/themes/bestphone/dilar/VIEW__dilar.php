<?= view("{$_ENV['app.theme']}/layouts/header/VIEW__head") ?>
<?= view("{$_ENV['app.theme']}/layouts/header/VIEW__header") ?>

<?php
$settings = json_decode(service('settings') -> get('App.firma')[0] ?? '', true);
?>

<!-- ========== MAIN CONTENT ========== -->
<main role="main">
    <div class="container">
        <div class="my-4 my-xl-8">
            <div class="d-flex justify-content-center">                                          
                <div class="col-md-5 ml-md-auto ml-xl-0">

                    <!-- Title -->
                    <header class="text-center mb-7">
                        <h3 class="d-inline-block section-title mb-0 pb-2 font-size-26">ЗАЯВКА ЗА ДИЛЪР</h3>                                        
                        <p>Фирмени данни.</p>
                    </header>
                    <!-- End Title -->

                    <form id="form-dilar" class="js-validate justify-content-center" action="<?= route_to('Dilar-sendForm') ?>" method="post" onkeydown="return event.key != 'Enter';">

                        <!-- Фирма --------------------------------- -->
                        <div class="js-form-message mb-2 js-focus-state input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-briefcase"></i>
                                </span>
                            </div>

                            <input class="form-control rounded-0" name="company" type="text" placeholder="* Име на фирма" 
                                   data-msg="Фирма е задълтелно поле"
                                   data-error-class="u-has-error"
                                   data-success-class="u-has-success"
                                   required>
                        </div>
                        <!-- End Фирма -->

                        <!-- МОЛ --------------------------------- -->
                        <div class="js-form-message mb-2 js-focus-state input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-user"></i>
                                </span>
                            </div>

                            <input class="form-control rounded-0" name="mol" type="text" placeholder="* МОЛ (име, презиме, фамилия)"  
                                   data-msg="МОЛ е задължително!"
                                   data-error-class="u-has-error"
                                   data-success-class="u-has-success"
                                   required >
                        </div>
                        <!-- End МОЛ -->

                        <!-- Булстат --------------------------------- -->
                        <div class="js-form-message mb-2 js-focus-state input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="signupConfirmPasswordLabel">
                                    <i class="fas fa-id-card"></i>
                                </span>
                            </div>

                            <input class="form-control rounded-0" name="bulstat" type="text" placeholder="* Булстат" 
                                   data-msg="Булстат е задължително!"
                                   data-error-class="u-has-error"
                                   data-success-class="u-has-success"
                                   required>
                        </div>
                        <!-- End Булстат -->

                        <!-- Град --------------------------------- -->
                        <div class="js-form-message mb-2 js-focus-state input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-city"></i>
                                </span>
                            </div>

                            <input class="form-control rounded-0" name="city" type="text" placeholder="* Град"
                                   data-msg="Град е задължително!"
                                   data-error-class="u-has-error"
                                   data-success-class="u-has-success"
                                   required>
                        </div>
                        <!-- End Град -->

                        <!-- Адрес --------------------------------- -->
                        <div class="js-form-message mb-2 js-focus-state input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" >
                                    <i class="fas fa-address-book"></i>
                                </span>
                            </div>

                            <input class="form-control rounded-0" name="address" type="text" placeholder="* Адрес" 
                                   data-msg="Адрес е задължително!"
                                   data-error-class="u-has-error"
                                   data-success-class="u-has-success"
                                   required>
                        </div>
                        <!-- End Адрес -->

                        <!-- Телефон --------------------------------- -->
                        <div class="js-form-message mb-2 js-focus-state input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-phone"></i>
                                </span>
                            </div>

                            <input class="form-control rounded-0" name="phone" type="number" placeholder="* Телефон" 
                                   data-msg="Въведете валиден телефонен номер"
                                   data-error-class="u-has-error"
                                   data-success-class="u-has-success"
                                   required>
                        </div>
                        <!-- End Телефон -->

                        <!-- email --------------------------------- -->
                        <div class="js-form-message mb-2 js-focus-state input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                            </div>

                            <input class="form-control rounded-0" name="email" type="email" placeholder="Email"
                                   data-msg="Моля въведете валдиен имейл"
                                   data-error-class="u-has-error"
                                   data-success-class="u-has-success">
                        </div>
                        <!-- End email -->   

                        <!-- Съгласие с условията --------------------------------- -->
                        <div class="js-form-message mb-2 js-focus-state input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text" id="agreeCheckboxLabel">
                                    <div class="custom-control custom-checkbox">

                                        <input id="agreeCheckbox" class="custom-control-input" name="agree" type="checkbox"
                                               data-msg="Моля да се съгласите с общите условия!"
                                               data-error-class="u-has-error"
                                               data-success-class="u-has-success"
                                               required>

                                        <label class="custom-control-label text-wrap" for="agreeCheckbox">Декларирам, че цялата предоставена от мен информация е пълна и вярна, както и че предоставям свободно и доброволно личните си данни и съм съгласен/а всички и всякакви предоставени от мен и/или публично достъпни мои лични данни да бъдат обработвани съобразно предвиденото в настоящата декларация(виж) , за целите, изрично посочени в настоящата декларация като съм запознат/а, че отказът ми за предоставяне на лични данни е основание създаденото от мен съобщение във формата за изпращане на съобщение да не бъде изпратено на <?= $settings['f_name'] ?? '' ?>. </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Съгласие с условията -->   

                        <div class="mb-2">
                            <button type="submit" class="btn btn-block btn-sm btn-primary-orange transition-3d-hover rounded-0">Изпрати</button>
                        </div>
                    </form>
                </div>
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
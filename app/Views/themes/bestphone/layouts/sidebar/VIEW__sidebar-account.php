<!-- Account Sidebar Navigation -->
<aside id="sidebarContent" class="border backdrop u-sidebar u-sidebar__lg" aria-labelledby="sidebarNavToggler">
    
    <div class="u-sidebar__scroller">
        <div class="u-sidebar__container">
            <div class="js-scrollbar u-header-sidebar__footer-offset pb-3">
                
                <!-- Toggle Button -->
                <div class="d-flex align-items-center pt-4 px-7">
                    <button type="button" class="close ml-auto"
                            aria-controls="sidebarContent"
                            aria-haspopup="true"
                            aria-expanded="false"
                            data-unfold-event="click"
                            data-unfold-hide-on-scroll="false"
                            data-unfold-target="#sidebarContent"
                            data-unfold-type="css-animation"
                            data-unfold-animation-in="fadeInRight"
                            data-unfold-animation-out="fadeOutRight"
                            data-unfold-duration="500">
                        <i class="ec ec-close-remove"></i>
                    </button>
                </div>
                <!-- End Toggle Button -->

                <!-- Content -->
                <div class="js-scrollbar u-sidebar__body">
                    <div class="u-sidebar__content1 u-header-sidebar__content">
                        <form class="js-validate" method="POST" action="<?= site_url('/login') ?>">
                            <!-- Login -->
                            <div id="login" data-target-group="idForm">
                                <!-- Title -->
                                <header class="text-center mb-7">
                                    <h2 class="h4 mb-0">Добре дошли!</h2>
                                    <p>Влезте в профила си.</p>
                                </header>
                                <!-- End Title -->

                             <!-- Form Group -->
                                <div class="form-group">
                                    <div class="js-form-message js-focus-state">
                                        <label class="sr-only" for="signinEmail">Email</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="signinEmailLabel">
                                                    <span class="fas fa-user"></span>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" name="email_bustat" id="signinEmail" placeholder="Email" aria-label="Email" aria-describedby="signinEmailLabel" required
                                                   data-msg="Моля попълнете email!"
                                                   data-error-class="u-has-error"
                                                   data-success-class="u-has-success">
                                        </div>
                                    </div>
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <div class="js-form-message js-focus-state">
                                        <label class="sr-only" for="signinPassword">Парола</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="signinPasswordLabel">
                                                    <span class="fas fa-lock"></span>
                                                </span>
                                            </div>
                                            <input type="password" class="form-control" name="password" id="signinPassword" placeholder="Въведете парола" aria-label="Password" aria-describedby="signinPasswordLabel" required
                                                   data-msg="Вашата парола е не валидна. Моля опитайте отново."
                                                   data-error-class="u-has-error"
                                                   data-success-class="u-has-success">
                                        </div>
                                    </div>
                             </div>
                                <!-- End Form Group -->
                                <div class="mb-2">
                                    <button type="submit" class="btn btn-block btn-sm btn-primary-orange rounded-0 text-white transition-3d-hover">Влез</button>
                                </div>

                                <div class="text-center mb-4">
                                    <span class="small">Нямате акаунт?</span>
                                    
                                    <a class="small text-blue" href="<?= route_to('Account-register') ?>">Регистрирай се
                                    </a>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
                <!-- End Content -->
            </div>
        </div>
    </div>
</aside>
<!-- End Account Sidebar Navigation -->

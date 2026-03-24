<?= view("{$_ENV['app.theme']}/layouts/header/VIEW__head") ?>
<?= view("{$_ENV['app.theme']}/layouts/header/VIEW__header") ?>

<?php
$bgImg = $_ENV['app.imagePortalDir'] . 'backgrounds/contacts-bg.jpg';
$bgImgContactsDiv = $_ENV['app.imagePortalDir'] . 'backgrounds/contacts-box-bg.jpg';
?>

<!-- HERO (Bootstrap only) -->
<section class="position-relative py-5 text-white">
    <div class="position-absolute w-100 h-100" style="inset:0; background:url('<?= htmlspecialchars($bgImg) ?>') center/cover no-repeat;"></div>
    <div class="position-absolute w-100 h-100" style="inset:0; background:rgba(0,0,0,.45);"></div>

    <div class="container position-relative">
        <div class="row">
            <div class="col-12 text-center">
                <h2 class="mb-3">Свържете се с нас</h2>
                <ul class="list-unstyled d-flex flex-wrap justify-content-center align-items-center mb-0">
                    <li class="mx-2 my-1">
                        <a href="<?= site_url('/') ?>" class="text-white text-decoration-none d-inline-flex align-items-center">
                            <i class="fa fa-home mr-2"></i><span>Начало</span>
                        </a>
                    </li>
                    <li class="mx-2 my-1">
                        <a href="<?= route_to('Pages-contact') ?>" class="text-white text-decoration-none d-inline-flex align-items-center">
                            <i class="fa fa-envelope mr-2"></i><span>Контакти</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- /HERO -->

<div id="content" class="site-content" tabindex="-1">
    <div class="container my-5">

        <div class="row">
            <!-- Лява колона: Contact Detail -->
            <div class="col-md-5 mb-4 p-0">
                <div class="card shadow-sm h-100 text-white position-relative overflow-hidden">

                    <!-- Фонова снимка -->
                    <div class="position-absolute w-100 h-100"
                        style="inset:0; background:url('<?= htmlspecialchars($bgImgContactsDiv) ?>') center/cover no-repeat;">
                    </div>

                    <!-- По-четим тъмен overlay -->
                    <div class="position-absolute w-100 h-100"
                        style="inset:0; background:
                        linear-gradient(145deg, rgba(24,28,36,.86) 0%, rgba(34,39,48,.78) 52%, rgba(16,18,24,.70) 100%);"></div>

                    <!-- Съдържание -->
                    <div class="card-body-contacts position-relative z-1 p-4">
                        <h3 class="h4 font-weight-bold mb-2 text-white">Контакт детайли</h3>
                        <p class="mb-4 text-white">Ще се радваме да помогнем. Свържете се по телефон, имейл или ни пишете през формата.</p>

                        <!-- Твоето съдържание от настройките (рендерира се както е) -->
                        <div class="contacts-information">
                            <?= $settings_portal['contact'] ?? '' ?>
                        </div>
                        <!-- Социални икони -->
                        <!-- <div class="mt-4">
                            <div class="fw-bold mb-2 text-white">Последвайте ни:</div>
                            <div class="container social-media-icons py-3 d-flex justify-content-left position-relative" style="z-index: 2;">
                                <a href="#" class="text-red-theme mx-2"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="text-red-theme mx-2"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="text-red-theme mx-2"><i class="fab fa-instagram"></i></a>
                                <a href="#" class="text-red-theme mx-2"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div> -->
                    </div>

                </div>


            </div>

            <!-- Дясна колона: ТВОЯТА ФОРМА (структурата е непокътната) -->
            <div class="col-md-7 mb-4 p-0">
                <div class="card shadow-sm h-100" style="border-top:6px solid #ee1a19;">
                    <div class="card-body p-4 p-md-5">
                        <h3 class="display-6 mb-2" style="font-weight:700; line-height:1.1;">Пишете ни — ще се свържем скоро</h3>
                        <p class="text-muted mb-4">
                            Оставете ни съобщение и ще се свържем с вас при първа възможност.
                        </p>

                        <div role="form" class="wpcf7">
                            <div class="screen-reader-response"></div>

                            <!-- Оставям твоята структура/имена; добавям само Bootstrap класове и още 2 опционални полета -->
                            <form action="#" method="post" class="wpcf7-form">

                                <!-- Ред: Име / Телефон -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="mb-1">Вашето име*</label>
                                        <span class="d-block">
                                            <input
                                                type="text"
                                                name="first-name"
                                                value=""
                                                size="40"
                                                class="wpcf7-form-control input-text form-control form-control-lg bg-light border-0"
                                                placeholder="Име"
                                                aria-required="true"
                                                aria-invalid="false" />
                                        </span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="mb-1">Телефон</label>
                                        <span class="d-block">
                                            <input
                                                type="text"
                                                name="phone"
                                                value=""
                                                size="40"
                                                class="form-control form-control-lg bg-light border-0"
                                                placeholder="Телефон"
                                                aria-invalid="false" />
                                        </span>
                                    </div>
                                </div>

                                <!-- Ред: Имейл / Тема -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="mb-1">Имейл адрес</label>
                                        <span class="d-block">
                                            <!-- НЕ сменям името (subject), само тип и класове за визия -->
                                            <input
                                                type="email"
                                                name="subject"
                                                value=""
                                                size="40"
                                                class="w-100 form-control form-control-lg bg-light border-0"
                                                placeholder="Email"
                                                aria-invalid="false" />
                                        </span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="mb-1">Тема</label>
                                        <span class="d-block">
                                            <input
                                                type="text"
                                                name="topic"
                                                value=""
                                                size="40"
                                                class="form-control form-control-lg bg-light border-0"
                                                placeholder="Тема"
                                                aria-invalid="false" />
                                        </span>
                                    </div>
                                </div>

                                <!-- Съобщение -->
                                <div class="mb-3">
                                    <label class="mb-1">Съобщение</label>
                                    <span class="d-block">
                                        <textarea
                                            name="your-message"
                                            cols="40"
                                            rows="6"
                                            class="w-100 form-control form-control-lg bg-light border-0"
                                            placeholder="Съобщение"
                                            aria-invalid="false"></textarea>
                                    </span>
                                </div>

                                <!-- Бутон -->
                                <div class="mt-3">
                                    <button type="submit"
                                        class="btn btn-lg w-100 text-white rounded-0"
                                        style="background:#ee1a19;">
                                        <i class="fa fa-paper-plane mr-2"></i> Изпрати съобщение
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Карта (по избор, само Bootstrap обвивка) -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="embed-responsive embed-responsive-21by9">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2962.2383190655046!2d25.585253476918798!3d42.05951085403529!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40a801f03383bc2b%3A0x8a84c695c4a83274!2z0JXQvdC00Lgg0LzQsNGA0LrQtdGC!5e0!3m2!1sbg!2sbg!4v1774086259181!5m2!1sbg!2sbg" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php
echo view("{$_ENV['app.theme']}/layouts/sidebar/VIEW__sidebar-account"); // Account Sidebar Navigation
echo view("{$_ENV['app.theme']}/layouts/footer/VIEW__footer");
?>

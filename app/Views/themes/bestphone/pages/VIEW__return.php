<?= view("{$_ENV['app.theme']}/layouts/header/VIEW__head") ?>
<?= view("{$_ENV['app.theme']}/layouts/header/VIEW__header") ?>

<?php
$bgImg = $_ENV['app.imagePortalDir'] . 'backgrounds/contacts-bg.jpg';
?>

<!-- HERO (Bootstrap only) -->
<section class="position-relative py-5 text-white">
    <div class="position-absolute w-100 h-100" style="inset:0; background:url('<?= htmlspecialchars($bgImg) ?>') center/cover no-repeat;"></div>
    <div class="position-absolute w-100 h-100" style="inset:0; background:rgba(0,0,0,.45);"></div>

    <div class="container position-relative">
        <div class="row">
            <div class="col-12 text-center">
                <h2 class="mb-3">Рекламации</h2>
                <ul class="list-unstyled d-flex flex-wrap justify-content-center align-items-center mb-0">
                    <li class="mx-2 my-1">
                        <a href="<?= site_url('/') ?>" class="text-white text-decoration-none d-inline-flex align-items-center">
                            <i class="fa fa-home mr-2"></i><span>Начало</span>
                        </a>
                    </li>
                    <li class="mx-2 my-1">
                        <p href="<?= route_to('Pages-about') ?>" class="text-white text-decoration-none d-inline-flex align-items-center p-0 m-0">
                            <i class="fa fa-envelope mr-2"></i><span>Рекламации</span>
                      </p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- /HERO -->

<div class="content py-5">
    <div class="container">
        <h2 class="heading-title text-center"><strong>ВРЪЩАНЕ НА ПРОДУКТИ</strong></h2>

        <p>Стандартен Формуляр за упражняване правото на отказ от сключен договор от разстояние: (попълнете и изпратете настоящия формуляр единствено ако желаете да се откажете от сключен договор от разстояние)</p>
        <br>
        <form action="https://valpers.eu/index.php?route=account/return/add" method="post" enctype="multipart/form-data" class="form-horizontal">
            <fieldset>
                <h5 class="secondary-title">Информация за поръчка</h5>

                <div class="row form-group required">
                    <label class="col-sm-2 control-label" for="input-firstname">Име</label>
                    <div class="col-sm-10">
                        <input type="text" name="firstname" value="" placeholder="Име" id="input-firstname" class="form-control">
                    </div>
                </div>

                <div class="row form-group required">
                    <label class="col-sm-2 control-label" for="input-lastname">Фамилия</label>
                    <div class="col-sm-10">
                        <input type="text" name="lastname" value="" placeholder="Фамилия" id="input-lastname" class="form-control">
                    </div>
                </div>

                <div class="row form-group required">
                    <label class="col-sm-2 control-label" for="input-email">Имейл</label>
                    <div class="col-sm-10">
                        <input type="text" name="email" value="" placeholder="Имейл" id="input-email" class="form-control">
                    </div>
                </div>

                <div class="row form-group required">
                    <label class="col-sm-2 control-label" for="input-telephone">Телефон</label>
                    <div class="col-sm-10">
                        <input type="text" name="telephone" value="" placeholder="Телефон" id="input-telephone" class="form-control">
                    </div>
                </div>

                <div class="row form-group required">
                    <label class="col-sm-2 control-label" for="input-order-id">Поръчка No</label>
                    <div class="col-sm-10">
                        <input type="text" name="order_id" value="" placeholder="Поръчка No" id="input-order-id" class="form-control">
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-sm-2 control-label" for="input-date-ordered">Дата на поръчката</label>
                    <div class="col-sm-3">
                        <div class="input-group date">
                            <input type="text" name="date_ordered" value="" placeholder="Дата на поръчката" data-date-format="YYYY-MM-DD" id="input-date-ordered" class="form-control">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default">
                                    <i class="fa fa-calendar"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </fieldset>

            <br>

            <fieldset>
                <h5 class="secondary-title">Информация за продукта и причина за връщане</h5>

                <div class="row form-group required">
                    <label class="col-sm-2 control-label" for="input-product">Име на продукта</label>
                    <div class="col-sm-10">
                        <input type="text" name="product" value="" placeholder="Име на продукта" id="input-product" class="form-control">
                    </div>
                </div>

                <div class="row form-group required">
                    <label class="col-sm-2 control-label" for="input-model">Код на продукта</label>
                    <div class="col-sm-10">
                        <input type="text" name="model" value="" placeholder="Код на продукта" id="input-model" class="form-control">
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-sm-2 control-label" for="input-quantity">Количество</label>
                    <div class="col-sm-10">
                        <input type="text" name="quantity" value="1" placeholder="Количество" id="input-quantity" class="form-control">
                    </div>
                </div>

                <div class="row form-group required">
                    <label class="col-sm-2 control-label">Причина за връщане</label>
                    <div class="col-sm-10">
                        <div class="radio">
                            <label>
                                <input type="radio" name="return_reason_id" value="1"> Грешка при поръчка </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="return_reason_id" value="4"> Объркана поръчка, моля опишете подробно </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="return_reason_id" value="3"> Повредена стока при пристигането </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="return_reason_id" value="5"> Повредена стока, моля опишете подробно </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="return_reason_id" value="2"> Получих грешен артикул </label>
                        </div>
                    </div>
                </div>

                <div class="row form-group required">
                    <label class="col-sm-2 control-label">Продуктът е отварян</label>
                    <div class="col-sm-10">
                        <label class="radio-inline">
                            <input type="radio" name="opened" value="1"> Да </label>
                        <label class="radio-inline">
                            <input type="radio" name="opened" value="0" checked="checked"> Не </label>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-sm-2 control-label" for="input-comment">Повреден или други</label>
                    <div class="col-sm-10">
                        <textarea name="comment" rows="10" placeholder="Повреден или други" id="input-comment" class="form-control"></textarea>
                    </div>
                </div>
            </fieldset>

            <div class="buttons">
                <div class="pull-right">Прочел съм и съм съгласен с <a href="<?= route_to('Pages-uslovia') ?>" class="agree">
                        <b>Общи правила и условия</b>
                    </a>
                    <input type="checkbox" name="agree" value="1">
                    <input type="submit" value="Подайте" class="btn btn-primary-orange button rounded-0 px-5">
                </div>
            </div>
        </form>

        <script type="text/javascript">
            ( function () {
                ra_key = "KL3XU5Y0KIDBEN";
                ra_params = {
                    add_to_cart_button_id: 'button-cart',
                    price_label_id: 'price_label_id',
                };
                var ra = document.createElement("script");
                ra.type = "text/javascript";
                ra.async = true;
                ra.src = ( "https:" == document.location.protocol ? "https://" : "http://" ) + "tracking.retargeting.biz/v3/rajs/" + ra_key + ".js";
                var s = document.getElementsByTagName("script")[0];
                s.parentNode.insertBefore(ra, s);
            } )();

            function checkEmail (email) {
                var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,9})+$/;
                return regex.test(email);
            }
            ;
            jQuery(document).ready(function ($) {
                jQuery("input[type='text']").blur(function () {
                    if (checkEmail($(this).val())) {
                        _ra.setEmail({
                            'email': $(this).val()
                        });
                        console.log('setEmail fired!');
                    }
                });
            });

            function checkEmail (email) {
                var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,9})+$/;
                return regex.test(email);
            }
            ;
            jQuery(document).ready(function ($) {
                jQuery("input[type='text']").blur(function () {
                    if (checkEmail($(this).val())) {
                        _ra.setEmail({
                            'email': $(this).val()
                        });
                        console.log('setEmail fired!');
                    }
                });
            });

            function checkEmail (email) {
                var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,9})+$/;
                return regex.test(email);
            }
            ;
            jQuery(document).ready(function ($) {
                jQuery("input[type='text']").blur(function () {
                    if (checkEmail($(this).val())) {
                        _ra.setEmail({
                            'email': $(this).val()
                        });
                        console.log('setEmail fired!');
                    }
                });
            });

            function checkEmail (email) {
                var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,9})+$/;
                return regex.test(email);
            }
            ;
            jQuery(document).ready(function ($) {
                jQuery("input[type='text']").blur(function () {
                    if (checkEmail($(this).val())) {
                        _ra.setEmail({
                            'email': $(this).val()
                        });
                        console.log('setEmail fired!');
                    }
                });
            });
        </script>
    </div>
</div>

<?php
echo view("{$_ENV['app.theme']}/layouts/sidebar/VIEW__sidebar-account"); // Account Sidebar Navigation
echo view("{$_ENV['app.theme']}/layouts/footer/VIEW__footer");
?>

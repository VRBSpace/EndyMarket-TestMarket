<?php $_companyData = $settings_portal['companyData'] ?? ''; ?> <!-- in basecontroller  -->
<!-- ========== FOOTER ========== -->
<footer style="background-color: #f3f3f3;">



    <!-- Footer-top-social -->
    <div class="container social-media-icons py-3 d-flex justify-content-left position-relative" style="z-index: 2;">
        <a href="https://www.facebook.com/profile.php?id=61556565086507" target="_blank" class="orange-theme mx-2"><i class="fab fa-facebook-f"></i></a>
        <a href="https://www.instagram.com/endy_market/" target="_blank" class="orange-theme mx-2"><i class="fab fa-instagram"></i></a>
    </div>


    <!-- Footer-bottom-widgets -->
    <div class="pt-5 position-relative footer-navigation" style="z-index: 2;">
        <div class="container mt-1">
            <div class="row border-bottom-1">
                <!-- Left Column: Logo and Address -->
                <div class="col-lg-4">
                    <div class="mb-3">
                        <a href="/" class="d-inline-block">
                            <?php $logo         = get_logo() ?>
                            <img src="<?= !empty($logo) ? $_ENV['app.imagePortalDir'] . $logo : '' ?>" alt="" style="width: 100%;">
                        </a>
                    </div>
                    <div class="mb-4">
                        <p class="fw-300">Вашият надежден партньор за всичко, от което се нуждаете в дома и строителството. Създадени с мисъл за удобството на нашите клиенти, нашите хипермаркети се стремят да предложат всичко на едно място, за да улесним вашето ежедневие и проекти.</p>
                    </div>
                </div>

                <!-- Middle Column: Information and Customer Service Links -->
                <div class="col-lg-5">
                    <div class="row">
                       <div class="col-12 col-md mb-4 mb-md-0 <?= ISMOBILE ? 'p-0' : '' ?>">
                            <h6 class="mb-3 fw-500">ИНФОРМАЦИЯ</h6>
                            <ul class="list-group1 fw-300 list-group-flush list-group-borderless mb-0 list-group-transparent list-unstyled">
                                <li><a class="list-group-item list-group-item-action" href="<?= route_to('Pages-about') ?>">За нас</a></li>
                                <li><a class="list-group-item list-group-item-action" href="<?= route_to('Pages-privacyData') ?>">Политика за защита на личните данни</a></li>
                                <li><a class="list-group-item list-group-item-action" href="<?= route_to('Pages-cookiePolicy') ?>">Политика за бисквитки</a></li>
                                <li><a class="list-group-item list-group-item-action" href="<?= route_to('Pages-uslovia') ?>">Общи правила и условия</a></li>
                            </ul>
                        </div>
                        <div class="col-12 col-md mb-4 mb-md-0 <?= ISMOBILE ? 'p-0' : '' ?>">
                            <h6 class="mb-3 fw-500">ОБСЛУЖВАНЕ НА КЛИЕНТИ</h6>
                            <ul class="list-group1 fw-300 list-group-flush list-group-borderless mb-0 list-group-transparent list-unstyled">
                                <li><a class="list-group-item list-group-item-action" href="<?= route_to('Pages-contact') ?>">Контакт с нас</a></li>
                                <li><a class="list-group-item list-group-item-action" href="<?= route_to('Pages-return') ?>">Рекламации</a></li>
                                <li><a class="list-group-item list-group-item-action" href="<?= route_to('Pages-waranty') ?>">Гаранционни условия</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Other Links -->
                <div class="col-lg-3">
                    <h6 class="mb-3 fw-500">КОНТАКТИ</h6>
                    <address class="fw-300">
                        <div><i class="fa fa-map-marker orange-theme mr-1"></i>&nbsp;<?= $_companyData['city'] ?? '' ?></div>
                        <div>&nbsp;<?= $_companyData['adres'] ?? '' ?></div>
                        <div><i class="fa fa-phone orange-theme mr1"></i>&nbsp;<?= $_companyData['tel'] ?? '' ?></div>
                        <div><i class="fa fa-envelope orange-theme mr1 "></i>&nbsp;<?= $_companyData['email'] ?? '' ?></div>
                        <br>
                        <p class="fw-300"><u><strong>Работно време:</strong></br></u>&nbsp;<?= $_companyData['workdays'] ?? '' ?></p>
                    </address>
                </div>
            </div>
        </div>
    </div>

    <!-- End Footer-bottom-widgets -->

    <!-- Footer-copy-right -->
    <div class="py-4">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center flex-column flex-md-row">
                <!-- Ляво: Авторски права -->
                <div class="mb-2 mb-md-0">
                    <?= $customConfig -> copyRight ?>
                </div>

                <!-- Дясно: Навигационни линкове -->
                <div class="footer-links text-md-right">
                    <a href="<?= route_to('Pages-about') ?>" class="">За нас</a>
                    <span class="mx-2">|</span>
                    <a href="<?= route_to('Pages-contact') ?>" class="">Контакт</a>
                </div>
            </div>
        </div>
    </div>
    <!-- End Footer-copy-right -->


</footer>
<!-- ========== END FOOTER ========== -->

<!-- Go to Top -->
<a class="js-go-to u-go-to" href="#"
   data-position='{"bottom": 15, "right": 15 }'
   data-type="fixed"
   data-offset-top="400"
   data-compensation="#header"
   data-show-effect="slideInUp"
   data-hide-effect="slideOutDown">
    <span class="fas fa-arrow-up u-go-to__inner"></span>
</a>
<!-- End Go to Top -->

<!--<script src="/assets/vendor/jquery-migrate/dist/jquery-migrate.min.js"></script>-->
<script src="/assets/vendor/popper.js/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/2.2.1/js.cookie.min.js"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.plugins.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.serializeJSON/3.2.1/jquery.serializejson.min.js" integrity="sha512-SdWDXwOhhVS/wWMRlwz3wZu3O5e4lm2/vKK3oD0E5slvGFg/swCYyZmts7+6si8WeJYIUsTrT3KZWWCknSopjg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/htmx/2.0.7/htmx.min.js" integrity="sha512-IisGoumHahmfNIhP4wUV3OhgQZaaDBuD6IG4XlyjT77IUkwreZL3T3afO4xXuDanSalZ57Un+UlAbarQjNZCTQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
 <script src="https://cdn.jsdelivr.net/npm/htmx-ext-preload@2.1.2" integrity="sha384-PRIcY6hH1Y5784C76/Y8SqLyTanY9rnI3B8F3+hKZFNED55hsEqMJyqWhp95lgfk" crossorigin="anonymous"></script>

<!-- --- ЗАРЕЖДАНЕ НА CSS ОТ CONTROLLER --- -->
<?php if (isset($addCSS) && is_array($addCSS)) { ?>
    <?php foreach ($addCSS as $css) { ?>
        <link href="<?= auto('assets/' . $css . '.css') ?>" rel="stylesheet" >
    <?php } ?>
<?php } ?>

<!-- --- ЗАРЕЖДАНЕ НА ГЛОБАЛНИ JS ОТ BASE CONTROLLER --- -->
<?php
if (isset($addGlobalJS) && is_array($addGlobalJS)) {
    foreach ($addGlobalJS as $js) {
        $minifiedCode[] = \JShrink\Minifier::minify(file_get_contents('assets/' . $js . '.js'));
    }
    file_put_contents('assets/js/theme/electro/scripts_template.min.js', $minifiedCode);
    ?>

    <script src="<?= auto('assets/js/theme/electro/scripts_template.min.js') ?>"></script>
<?php } ?>

<script>
    const IMAGEDIR = '<?= $_ENV['app.imageDir'] ?>';
    const NOIMAGE = '<?= $_ENV['app.noImage'] ?>';
    const HEADERFULL = '<?= $customConfig -> header['appendBlockModels'] ?>';
    const DDS = '<?= session('user_id') ? 0.20 : 0 ?>';
    const EURORATE = 1.95583;
    const VALUTASIGN = '<?= get_valuta() ?>';
    const EUROSYMBOL = '<?= '€' ?>';
</script>   

<!-- --- ЗАРЕЖДАНЕ НА JS ОТ CONTROLLER --- -->
<?php if (isset($addJS) && is_array($addJS)) { ?>
    <?php foreach ($addJS as $js) { ?>
        <script src="<?= auto('assets/' . $js . '.js'); ?>"></script>
    <?php } ?>
<?php } ?>

<script>
    const ISMOBILE = '<?= ISMOBILE ?>';
</script> 

<!-- preloader за прежареждане на стриница и при заявки ajax-->
<script src="assets/js/theme/electro/pagePreloader.js"></script>

</body>
</html>

<!-- =========================================
     COOKIE CONSENT ULTRA v4 — FULL
========================================= -->

<div id="ucBar">

  <div class="uc-wrap">

    <div class="uc-text">
      <h3>Поверителност и бисквитки</h3>
      <p>
        Използваме бисквитки за осигуряване на функционалност,
        анализ на трафика и персонализиране на съдържание и реклами.
        Можеш да приемеш всички или да управляваш предпочитанията си.
      </p>
    </div>

    <div class="uc-actions">

      <button class="uc-btn primary"
              onclick="ucAcceptAll()">
        Приемам
      </button>

      <button class="uc-btn ghost"
              onclick="ucDecline()">
        Отказ
      </button>

      <button class="uc-btn dark"
              onclick="ucOpenSettings()">
        Персонализиране
      </button>

    </div>

  </div>
</div>



<!-- SETTINGS OVERLAY -->
<div id="ucOverlay">

  <div class="uc-panel">

    <div class="uc-panel-head">
      <h3>Настройки за бисквитки</h3>
      <button onclick="ucCloseSettings()">✕</button>
    </div>


    <!-- NECESSARY -->
    <div class="uc-cat">

      <div class="uc-row">
        <label class="uc-switch">
          <input type="checkbox" checked disabled>
          <span></span>
        </label>

        <div>
          <strong>Задължителни бисквитки</strong>
          <small>Винаги активни</small>
        </div>
      </div>

      <p>
        Тези бисквитки са строго необходими за функционирането на
        уебсайта и не могат да бъдат деактивирани. Те се използват
        за основни функции като сигурност, управление на сесии,
        вход в профили и техническа стабилност на платформата.
      </p>

    </div>


    <!-- ANALYTICS -->
    <div class="uc-cat">

      <div class="uc-row">
        <label class="uc-switch">
          <input type="checkbox" id="ucAnalytics">
          <span></span>
        </label>

        <div>
          <strong>Аналитични бисквитки</strong>
          <small>Статистика и измерване</small>
        </div>
      </div>

      <p>
        Аналитичните бисквитки събират информация за начина,
        по който посетителите използват сайта — страници,
        време на престой, източници на трафик и поведение.
        Данните се използват за подобрение на услугите.
      </p>

    </div>


    <!-- MARKETING -->
    <div class="uc-cat">

      <div class="uc-row">
        <label class="uc-switch">
          <input type="checkbox" id="ucMarketing">
          <span></span>
        </label>

        <div>
          <strong>Маркетинг бисквитки</strong>
          <small>Реклама и ремаркетинг</small>
        </div>
      </div>

      <p>
        Маркетинг бисквитките позволяват показване на
        персонализирани реклами и измерване ефективността
        на кампании чрез платформи като Google Ads и Meta.
      </p>

    </div>


    <div class="uc-panel-actions">
      <button class="uc-btn primary"
              onclick="ucSaveCustom()">
        Запази избора
      </button>
    </div>

  </div>
</div>



<style>

/* =========================================
   PRIMARY COLOR
========================================= */
:root{
  --primary-color:#ee0000;
}


/* =========================================
   BOTTOM BAR
========================================= */
#ucBar{
  position:fixed;
  bottom:0;
  left:0;
  width:100%;
  background:rgba(20,20,20,.95);
  backdrop-filter:blur(14px);
  box-shadow:0 -6px 30px rgba(0,0,0,.4);
  z-index:9998;
  font-family:Inter,Arial,sans-serif;
}

.uc-wrap{
  max-width:1300px;
  margin:auto;
  padding:18px 24px;
  display:flex;
  gap:20px;
  justify-content:space-between;
  align-items:center;
  flex-wrap:wrap;
}

.uc-text h3{
  color:#fff;
  margin:0 0 4px;
  font-size:16px;
}

.uc-text p{
  color:#bbb;
  font-size:13px;
  max-width:620px;
}


/* =========================================
   BUTTONS
========================================= */
.uc-actions{
  display:flex;
  gap:10px;
}

.uc-btn{
  padding:8px 14px;
  font-size:12px;
  border-radius:10px;
  border:none;
  cursor:pointer;
  font-weight:600;
  transition:.25s;
}

.uc-btn.primary{
  background:var(--primary-color) !important;
  color:#fff;
}

.uc-btn.primary:hover{
  box-shadow:0 4px 18px rgba(238,0,0,.45);
  transform:translateY(-1px);
}

.uc-btn.ghost{
  background:transparent;
  border:1px solid #555;
  color:#ddd;
}

.uc-btn.dark{
  background:#2a2a2a;
  color:#fff;
}


/* =========================================
   OVERLAY
========================================= */
#ucOverlay{
  position:fixed;
  inset:0;
  background:rgba(0,0,0,.65);
  backdrop-filter:blur(6px);
  display:none;
  align-items:flex-end;
  z-index:9999;
}

.uc-panel{
  width:100%;
  background:#111;
  padding:26px;
  border-radius:18px 18px 0 0;
  animation:ucSlide .45s ease;
}

@keyframes ucSlide{
  from{transform:translateY(100%);}
  to{transform:translateY(0);}
}

.uc-panel-head{
  display:flex;
  justify-content:space-between;
  margin-bottom:18px;
}

.uc-panel-head h3{color:#fff;margin:0;}

.uc-panel-head button{
  background:none;
  border:none;
  color:#fff;
  font-size:18px;
  cursor:pointer;
}


/* =========================================
   CATEGORY
========================================= */
.uc-cat{
  padding:14px 0;
  border-bottom:1px solid rgba(255,255,255,.06);
}

.uc-row{
  display:flex;
  gap:12px;
  align-items:center;
}

.uc-cat strong{color:#fff;}
.uc-cat small{color:#888;font-size:12px;}

.uc-cat p{
  color:#aaa;
  font-size:13px;
  margin:8px 0 0 52px;
}


/* =========================================
   SWITCH
========================================= */
.uc-switch{
  position:relative;
  width:46px;
  height:24px;
}

.uc-switch input{opacity:0;width:0;height:0;}

.uc-switch span{
  position:absolute;
  inset:0;
  background:#444;
  border-radius:50px;
}

.uc-switch span:before{
  content:"";
  position:absolute;
  width:18px;
  height:18px;
  left:3px;
  bottom:3px;
  background:#fff;
  border-radius:50%;
  transition:.3s;
}

.uc-switch input:checked + span{
  background:var(--primary-color) !important;
}

#js-searchproduct-item{
    z-index:999999999999999999999999;
}

.uc-switch input:checked + span:before{
  transform:translateX(22px);
}

.uc-panel-actions{margin-top:20px;}

</style>



<script>
const UC_KEY="uc_v4";

/* INIT */
document.addEventListener("DOMContentLoaded",()=>{

 const saved=localStorage.getItem(UC_KEY);

 if(saved){
   const consent=JSON.parse(saved);
   document.getElementById("ucBar").style.display="none";
   ucApply(consent);
 }
});


function ucOpenSettings(){
 document.getElementById("ucOverlay").style.display="flex";
}

function ucCloseSettings(){
 document.getElementById("ucOverlay").style.display="none";
}


function ucAcceptAll(){
 ucSave({necessary:true,analytics:true,marketing:true});
}

function ucDecline(){
 ucSave({necessary:true,analytics:false,marketing:false});
}

function ucSaveCustom(){
 ucSave({
  necessary:true,
  analytics:
   document.getElementById("ucAnalytics").checked,
  marketing:
   document.getElementById("ucMarketing").checked
 });
}


function ucSave(consent){

 localStorage.setItem(UC_KEY,
  JSON.stringify(consent));

 document.getElementById("ucBar").style.display="none";
 document.getElementById("ucOverlay").style.display="none";

 ucApply(consent);
}


function ucApply(consent){

 if(consent.marketing) loadMarketing();
}

$('#js-searchproduct-item').on('click', function (event) {
    $('#css-searchBoxEffect').addClass('open');
}).blur(function () {
    $('#css-searchBoxEffect').removeClass('open');
});

function loadMarketing(){

 if(window.mkLoaded) return;
 window.mkLoaded=true;
}

</script>

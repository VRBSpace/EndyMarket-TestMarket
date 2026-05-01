<?php

$_companyData   = $settings_portal['companyData'] ?? '';
$cookieSettings = $settings_portal['coocie'] ?? '';
?> 

<style><?php echo file_get_contents('assets/css/layouts/footer.css') ?> </style>

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
                            <?php $logo           = get_logo() ?>
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
                                <li><a class="list-group-item list-group-item-action" href="<?= route_to('Pages-privacySafe') ?>">Начини за плащане и доставка</a></li>
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

    $('#js-searchproduct-item').on('click', function (event) {
        $('#css-searchBoxEffect').addClass('open');
    }).blur(function () {
        $('#css-searchBoxEffect').removeClass('open');
    });
</script> 

<!-- =========================================
     COOKIE CONSENT ULTRA v4 — FULL
========================================= -->
<?php

/**
 * Подготовка на скриптовете и правилата за блокиране
 */
$scriptsToLoad    = [];
$cookieBlockRules = [];
$seenHosts        = [];

foreach (($cookieSettings['tools'] ?? []) as $tool) {
    $rawCode = $tool['code'] ?? '';
    // Използваме ID на категорията като ключ (напр. "1" или "2")
    $catId   = (string) ($tool['category_id'] ?? '');

    if (!$rawCode || !$catId)
        continue;

    // Ггрупираме скриптовете по ID на категория
    $scriptsToLoad[$catId][] = $rawCode;

    // Извличане на хост за автоматично блокиране на външни скриптове
    if (preg_match('/src=["\'](?:https?:)?\/\/([^"\'>\/]+)/i', $rawCode, $m)) {
        $host = $m[1];
        if (!isset($seenHosts[$host . $catId])) {
            $cookieBlockRules[]        = [
                'pattern'  => preg_quote($host, '/'),
                'category' => $catId
            ];
            $seenHosts[$host . $catId] = true;
        }
    }
}
?>

<div id="ucBar" style="display:none">
    <div class="uc-wrap">
        <div class="uc-text">
            <h3><?= $cookieSettings['bar_title'] ?? 'Поверителност' ?></h3>
            <p><?= nl2br($cookieSettings['bar_text'] ?? '') ?></p>
            <?php if (!empty($cookieSettings['show_policy'])): ?>
                <a href="<?= $cookieSettings['policy_url'] ?>" target="_blank" style="color:#a6a6ba;">Виж политиката</a>
            <?php endif; ?>
        </div>
        <div class="uc-actions">
            <button class="uc-btn primary" onclick="ucAction('all')">Приемам всичко</button>

            <?php if (!empty($cookieSettings['show_policy'])): ?>
                <button class="uc-btn ghost" onclick="ucAction('decline')">Отказ</button>
            <?php endif; ?>


            <button class="uc-btn dark" onclick="ucOpenSettings()">Персонализиране</button>
        </div>
    </div>
</div>

<div id="ucOverlay" style="display:none">
    <div class="uc-panel">
        <div class="uc-panel-head">
            <h3>Настройки за бисквитки</h3>
            <button class="uc-btn primary" onclick="ucAction('custom')">Запази избора</button>
            <button type="button" class="close-btn" onclick="ucCloseSettings()">✕</button>
        </div>

        <div class="uc-body">
            <?php foreach (($cookieSettings['categories'] ?? []) as $cat): ?>
                <?php

                $isNecessary  = !empty($cat['is_required']);
                // Динамично извличане на инструментите за тази категория
                $currentCatId = $cat['id'] ?? '';

                $relatedTools = array_filter($cookieSettings['decl'] ?? [], function ($t) use ($currentCatId) {
                    return ($t['category_id'] ?? '') === $currentCatId && !empty($t['name']);
                });
                ?>
                <div class="uc-cat">
                    <div class="uc-row">
                        <label class="uc-switch">
                            <input type="checkbox" 
                                   data-code="<?= $cat['id'] ?>" 
                                   <?= $isNecessary ? 'checked disabled' : '' ?>>
                            <span></span>
                        </label>
                        <strong><?= $cat['title'] ?></strong>
                    </div>
                    <p><?= $cat['description'] ?></p>

                    <?php if (!empty($relatedTools)): ?>
                        <?php $collapseId = 'relatedTools_' . uniqid(); ?>

                        <div class="dynamic-cookies-badges mt-2" style="margin-left: 45px;">
                            <a class="btn btn-sm btn-outline-light"
                               data-toggle="collapse"
                               href="#<?= $collapseId ?>"
                               role="button" >
                                Повече информация (<?= count($relatedTools) ?>)
                            </a>

                            <div class="collapse mt-3" id="<?= $collapseId ?>">
                                <div style="display:flex; flex-direction:column; gap:10px;">
                                    <?php foreach ($relatedTools as $tool): ?>
                                        <div class="row" style="border:1px solid #3f3f3f; border-radius:8px; padding:10px 12px;background:rgba(255,255,255,0.03);">
                                            <div class="mb-1 pr-2">
                                                <span
                                                    class="badge badge-outline-secondary"
                                                    style="border:1px solid #666; color:#fff;font-size:12px;padding:6px 10px; " >
                                                        <?= $tool['name'] ?? '' ?>
                                                </span>
                                            </div>

                                            <?php if (!empty($tool['desc'])): ?>
                                                <div style=" font-size:13px; line-height:1.45;color:#cfcfcf;">
                                                    <?= $tool['desc'] ?>
                                                </div>
                                            <?php else: ?>
                                                <div style="font-size:12px; color:#8f8f8f;font-style:italic;">
                                                    Няма описание
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
// Данни от PHP
    window.DYNAMIC_SCRIPTS = <?= json_encode($scriptsToLoad, JSON_UNESCAPED_UNICODE) ?>;
    window.RAW_RULES = <?= json_encode($cookieBlockRules, JSON_UNESCAPED_UNICODE) ?>;
    window.UC_POLICY_VERSION = <?= json_encode((string) ($cookieSettings['policy_version'] ?? '1.0'), JSON_UNESCAPED_UNICODE) ?>;

    (($, window, document) => {
        'use strict';

        const CURRENT_POLICY_VERSION = String(window.UC_POLICY_VERSION || '1.0').trim();
        const UC_KEY = `uc_v_${CURRENT_POLICY_VERSION}`;

        const UI = {
            get bar() {
                return $('#ucBar');
            },
            get overlay() {
                return $('#ucOverlay');
            },
            get inputs() {
                return $('.uc-cat input');
            }
        };

        const RULES = (window.RAW_RULES || []).map(r => ({
                regex: new RegExp(r.pattern, 'i'),
                category: String(r.category)
            }));

        const ConsentManager = {
            // Вземане на състоянието от localStorage
            get: function () {
                try {
                    const saved = localStorage.getItem(UC_KEY);
                    if (saved)
                        return JSON.parse(saved);
                } catch (e) {
                }

                // Първоначално състояние (дефолт)
                const data = {};
                UI.inputs.each(function () {
                    const id = String($(this).data('code'));
                    if (id)
                        data[id] = !!$(this).prop('disabled');
                });
                return data;
            },

            // Запис на избора
            save: function (type) {
                const data = {};

                UI.inputs.each(function () {
                    const $input = $(this);
                    const id = String($input.data('code'));
                    // Намираме заглавието на категорията от съседния елемент <strong>
                    const title = $input.closest('.uc-cat').find('strong').text().trim();

                    if (!id)
                        return;

                    let status = false;
                    if ($input.prop('disabled')) {
                        status = true; // За "Необходими"
                    } else if (type === 'all') {
                        status = true;
                    } else if (type === 'decline') {
                        status = false;
                    } else {
                        status = $input.is(':checked');
                    }

                    // ЗАПИСВАМЕ ОБЕКТ СЪС СТАТУС И ОПИСАНИЕ
                    data[id] = {
                        status: status,
                        title: title
                    };
                });

                localStorage.setItem(UC_KEY, JSON.stringify(data));

                // Изпращане към PHP за запис в лог/база (опционално)
                fetch('/saveCoocie', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: `consent=${encodeURIComponent(JSON.stringify(data))}&timestamp=${Date.now()}`
                }).finally(() => window.location.reload());
            },

            // Инжектиране на разрешените скриптове
            injectScripts: function (consent) {
                const groups = window.DYNAMIC_SCRIPTS || {};
                Object.keys(groups).forEach(id => {

                    if (consent[id] && consent[id].status === true) {
                        groups[id].forEach(html => this.executeScript(html));
                        delete window.DYNAMIC_SCRIPTS[id];
                    }
                });
            },

            executeScript: function (html) {
                const div = document.createElement('div');
                div.innerHTML = html.trim();

                Array.from(div.querySelectorAll('script')).forEach(oldScript => {
                    const newScript = document.createElement('script');

                    // Копираме атрибутите и махаме text/plain блокажа
                    Array.from(oldScript.attributes).forEach(attr => {
                        if (attr.name.toLowerCase() === 'type')
                            return;
                        newScript.setAttribute(attr.name, attr.value);
                    });

                    newScript.type = 'text/javascript';
                    if (oldScript.src)
                        newScript.src = oldScript.src;
                    if (oldScript.textContent)
                        newScript.textContent = oldScript.textContent;

                    document.body.appendChild(newScript);
                });
            }
        };

        const Security = {
            shouldBlock: (src, consent) => {
                if (!src)
                    return false;
                const match = RULES.find(r => r.regex.test(src));
                return match ? consent[match.category] === false : false;
            },

            init: function (consent) {
                this.patchCreateElement(consent);
                this.patchNodeMethods(consent);

                // Блокираме статични скриптове, които вече са в HTML
                document.querySelectorAll('script[src]').forEach(s => {
                    const src = s.getAttribute('src');
                    if (this.shouldBlock(src, consent)) {
                        s.type = 'text/plain';
                        s.setAttribute('data-blocked-src', src);
                        s.removeAttribute('src');
                    }
                });
            },

            patchCreateElement: function (consent) {
                const original = document.createElement;
                document.createElement = function (name) {
                    const el = original.call(document, name);
                    if (name.toLowerCase() === 'script') {
                        const setter = Object.getOwnPropertyDescriptor(HTMLScriptElement.prototype, 'src').set;
                        Object.defineProperty(el, 'src', {
                            set: function (val) {
                                if (Security.shouldBlock(val, consent)) {
                                    el.type = 'text/plain';
                                    el.setAttribute('data-blocked-src', val);
                                    return;
                                }
                                setter.call(this, val);
                            },
                            get: function () {
                                return this.getAttribute('src');
                            }
                        });
                    }
                    return el;
                };
            },

            patchNodeMethods: function (consent) {
                const patch = (proto, method) => {
                    const orig = proto[method];
                    proto[method] = function (node) {
                        if (node?.tagName?.toLowerCase( ) === 'script') {
                            const src = node.src || node.getAttribute('src');
                            if (Security.shouldBlock(src, consent)) {
                                node.type = 'text/plain';
                            }
                        }
                        return orig.apply(this, arguments);
                    };
                };
                patch(Node.prototype, 'appendChild');
                patch(Node.prototype, 'insertBefore');
            }
        };

        // Глобални функции за бутоните
        window.ucOpenSettings = () => UI.overlay.css('display', 'flex');
        window.ucCloseSettings = () => UI.overlay.hide();
        window.ucAction = (type) => ConsentManager.save(type);

        // Старт
        $(() => {
            const consent = ConsentManager.get();


            // 1. Прилагаме състоянието към чекбоксовете
            UI.inputs.each(function () {
                const id = $(this).data('code');
                if (id && !$(this).prop('disabled')) {
                    $(this).prop('checked', !!consent[id]);
                }
            });

            // 2. Активираме защитата
            Security.init(consent);

            // 3. Пускаме разрешените скриптове
            ConsentManager.injectScripts(consent);

            // 4. Показваме лентата, ако няма запис в localStorage
            if (!localStorage.getItem(UC_KEY)) {
                UI.bar.fadeIn();
            }
        });

    })(jQuery, window, document);
</script>

</body>
</html>

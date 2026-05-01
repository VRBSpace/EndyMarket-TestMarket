(function ($) {
    var $pre = $('#page-preloader');
    var navigating = false;
    var ajaxCount = 0;
    var failSafeTo = null;
    var showTO = null;

    function _showNow() {
        $('body').addClass('preloader-open');

        requestAnimationFrame(function () {
            $pre.addClass('is-active');
        });
    }

    function showPreloader(opts) {
        clearTimeout(showTO);

        var delay = (opts && typeof opts.delay === 'number') ? opts.delay : 0;
        showTO = setTimeout(_showNow, delay);
    }

    function hidePreloader(force) {
        if (navigating && !force) {
            return;
        }

        clearTimeout(showTO);
        clearTimeout(failSafeTo);

        $pre.removeClass('is-active');
        $('body').removeClass('preloader-open');
    }

    function startFailSafe() {
        clearTimeout(failSafeTo);

        // аварийно скриване след 15 сек, ако нещо забие
        failSafeTo = setTimeout(function () {
            ajaxCount = 0;
            navigating = false;
            hidePreloader(true);
        }, 15000);
    }

    /* ----- ПЪЛНА НАВИГАЦИЯ ----- */
    $(window)
        .on('load', function () {
            navigating = false;
            hidePreloader(true);
        })
        .on('pageshow', function (e) {
            if (e.originalEvent && e.originalEvent.persisted) {
                navigating = false;
                hidePreloader(true);
            }
        })
        .on('beforeunload', function () {
            navigating = true;
            showPreloader();
        });

    $(document)
//        .on('click', 'a', function (ev) {
//            var a = this;
//            var href = a.getAttribute('href') || '';
//
//            if (ev.which === 2 || ev.ctrlKey || ev.metaKey || ev.shiftKey || ev.altKey) return;
//            if (a.target && a.target !== '' && a.target !== '_self') return;
//            if (!href || href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:')) return;
//            if (a.hasAttribute('download')) return;
//
//            setTimeout(function () {
//                navigating = true;
//                showPreloader();
//            }, 0);
//        })
        .on('submit', 'form', function () {
            var t = this.getAttribute('target');
            if (t && t !== '_self') return;
            if (this.hasAttribute('data-skip-preloader')) return;

            navigating = true;
            showPreloader();
        })
        .on('change', '.js-shopRelationFilter', function () {
            navigating = true;
            showPreloader();
        })

        /* ----- AJAX ----- */
        .on('ajaxSend', function () {
            ajaxCount++;
            navigating = false;

            if (ajaxCount === 1) {
                showPreloader({ delay: 80 });
                startFailSafe();
            }
        })
        .on('ajaxComplete', function () {
            ajaxCount = Math.max(0, ajaxCount - 1);

            if (ajaxCount === 0) {
                hidePreloader(true);
            }
        })
        .on('ajaxError', function () {
            if (ajaxCount <= 0) {
                ajaxCount = 0;
                hidePreloader(true);
            }
        })
        .on('ajaxStop', function () {
            ajaxCount = 0;
            hidePreloader(true);
        });

})(jQuery);

( function ($) {
    var $pre = $('#page-preloader');
    var navigating = false;
    var ajaxCount = 0;
    var failSafeTo = null;
    var showTO = null;

    function _showNow () {
        $('body').addClass('preloader-open');
        // rAF за да избегнем layout thrash
        requestAnimationFrame(function () {
            $pre.addClass('is-active');
        });
    }
    function showPreloader (opts) {
        clearTimeout(showTO);
        var delay = ( opts && typeof opts.delay === 'number' ) ? opts.delay : 0;

        showTO = setTimeout(_showNow, delay);
    }
    function hidePreloader () {
        if (navigating)
            return;

        clearTimeout(showTO);
        clearTimeout(failSafeTo);
        $pre.removeClass('is-active');
        $('body').removeClass('preloader-open');
    }
    function startFailSafe () {
        clearTimeout(failSafeTo);
        failSafeTo = setTimeout(hidePreloader, 0);
    }

    /* ----- ПЪЛНА НАВИГАЦИЯ ----- */
       
    $(window)
            .on('load', function () {
                hidePreloader();
            })
            .on('pageshow', function (e) {
                if (e.originalEvent && e.originalEvent.persisted) {
                    navigating = false;
                    hidePreloader();
                }
            })
            .on('beforeunload', function () {
                navigating = true;
                showPreloader();
            });

    $(document)
            .on('click', 'a', function (ev) {
                var a = this, href = a.getAttribute('href') || '';
                if (ev.which === 2 || ev.ctrlKey || ev.metaKey || ev.shiftKey || ev.altKey)
                    return;
                if (a.target && a.target !== '' && a.target !== '_self')
                    return;
                if (!href || href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:'))
                    return;
                if (a.hasAttribute('download'))
                    return;
                setTimeout(function () {
                    navigating = true;
                    showPreloader();
                }, 0);
            })
            .on('submit', 'form', function () {
                var t = this.getAttribute('target');
                if (t && t !== '_self')
                    return;
                // AJAX forms (submit is intercepted in JS) should not trigger full-page preloader
                if ($(this).data('route'))
                    return;
                navigating = true;
                showPreloader();
            })
            .on('change', '.js-shopRelationFilter', function () {
                navigating = true;
                showPreloader();
            })
            /* ----- AJAX (бързо и без фликър) ----- */
            .on('ajaxSend', function () {
                ajaxCount++;
                navigating = false;
                // не мигай за светкавични заявки
                showPreloader({delay: 80});
                startFailSafe();
            })
            .on('ajaxSuccess', function () {
                hidePreloader();
            })
            .on('ajaxComplete', function () {
                ajaxCount = Math.max(0, ajaxCount - 1);
            })
            .on('ajaxStop', function () {
                ajaxCount = 0;
                hidePreloader();
            })
            .on('ajaxError', function () {
                if (ajaxCount === 0)
                    hidePreloader();
            });

} )(jQuery);

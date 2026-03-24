/**
 * Focus state wrapper.
 *
 * @author Htmlstream
 * @version 1.0
 *
 */
;
( function ($) {
    'use strict';

    $.HSCore.components.HSFocusState = {
        /**
         * Focus state.
         *
         * @return undefined
         */
        init: function () {
            var collection = $('.js-focus-state input:not([type="checkbox"], [type="radio"]), .js-focus-state textarea, .js-focus-state select');

            if (!collection.length)
                return;

            collection.on('focusin', function () {
                var $this = $(this),
                        $thisParent = $this.closest('.js-focus-state');

                var header = $('header');
                var headerPos = header.offset().top + header.height();

                $('html, body').animate({
                    scrollTop: $thisParent.offset().top - headerPos - 100
                }, 300);

                $thisParent.addClass('u-focus-state');
            });

            collection.on('focusout', function () {
                var $this = $(this),
                        $thisParent = $this.closest('.js-focus-state');

                $thisParent.removeClass('u-focus-state');
            });
        }
    };
} )(jQuery);

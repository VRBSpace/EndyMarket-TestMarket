/* global PRODUCTS_JSON, imageDir, axios, Promise */
(function ($, window, document, undefined) {
    var slideshowSlider = slideshowSlider || {};
    slideshowSlider = {
        'init': function () {
            var $slider = $('#js-slideshowSlider');

            switchable({
                $element: $slider,
                // animateSpeed: 2000,
                interval: 5000,
                effect: 'fade',
                pauseOnHover: true,
                loadImg: true,
            });

            // touch swipe navigation
            var startX = 0;
            $slider.on('touchstart', function (e) {
                startX = e.originalEvent.touches[0].clientX;
            });

            $slider.on('touchend', function (e) {
                var endX = e.originalEvent.changedTouches[0].clientX;
                var delta = endX - startX;

                if (Math.abs(delta) > 30) {
                    if (delta < 0) {
                        $slider.find('.next').trigger('click');
                    } else {
                        $slider.find('.prev').trigger('click');
                    }
                }
            });
        },
    };
    slideshowSlider.init();
})(jQuery, window, document);

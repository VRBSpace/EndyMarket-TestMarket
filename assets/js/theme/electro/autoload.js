$(window).on('load', function () {

    // $('#js-searchproduct-item').on('click', function (event) {
    //     $('#css-searchBoxEffect').addClass('open');
    // }).blur(function () {
    //     $('#css-searchBoxEffect').removeClass('open');

    // });

    // initialization of HSMegaMenu component
    $('.js-mega-menu').HSMegaMenu({
        event: 'click',
        direction: 'horizontal',
        pageContainer: $('.container'),
        breakpoint: 767.98,
        hideTimeOut: 0
    });

    window.onclick = function (event) {
        if ($('.vertical-menu show')) {
            $('.vertical-menu').removeClass('show');
        }
    };


});

//( function ($, window, document, undefined) {

var autoload = autoload || {};

autoload = {

    'init': function ( ) {
        // initialization of header 
   
        $.HSCore.components.HSHeader.init($('#header'));

        // initialization of animation
        $.HSCore.components.HSOnScrollAnimation.init('[data-animation]');
//--------------------------------------


        // initialization of malihu scrollbar
        $.HSCore.components.HSMalihuScrollBar.init($('.js-scrollbar'));

        // initialization of forms
        //$.HSCore.components.HSFocusState.init();

        // initialization of form validation
        $.HSCore.components.HSValidation.init('.js-validate', {
            rules: {
                confirmPassword: {
                    equalTo: '#signupPassword'
                }
            }
        });

        // initialization of show animations
        $.HSCore.components.HSShowAnimation.init('.js-animation-link');

        // initialization of fancybox
        $.HSCore.components.HSFancyBox.init('.js-fancybox');

        $(".img-fancybox").fancybox({
            'hideOnContentClick': false,
            'autoScale': true,
//        'width': 350,
//        'height': 'auto',
            'transitionIn': 'elastic',
            'transitionOut': 'elastic'

        });

        //$.fancybox.close

        // initialization of slick carousel
        $.HSCore.components.HSSlickCarousel.init('.js-slick-carousel');

        // initialization of go to
        $.HSCore.components.HSGoTo.init('.js-go-to');

        // initialization of hamburgers
        $.HSCore.components.HSHamburgers.init('#hamburgerTrigger');

        // initialization of select picker
        $.HSCore.components.HSSelectPicker.init('.js-select');
        //---------------------------------------------
        // initialization of quantity counter
        $.HSCore.components.HSQantityCounter.init('.js-quantity');
        try {
            if ($.HSCore && $.HSCore.components && $.HSCore.components.HSQantityCounter) {
                $.HSCore.components.HSQantityCounter.init = function () {
                    return $();
                }; // no-op
            }
        } catch (e) {
        }

        $.HSCore.components.HSRangeSlider.init('.js-range-slider', {

            onFinish: function (arg) {
                const _this = $(this);

                const getParamSet = (url, param) => {
                    const paramValue = url.searchParams.get(param);
                    return new Set(paramValue ? paramValue : [ ]);
                };

                const currentURL = new URL(window.location.href);
                let priceSet = getParamSet(currentURL, 'f_price');

                priceSet = new Set([ arg.from + '_' + arg.to ]);// Replace with the latest value, not add

                const updateURLParams = () => {
                    currentURL.searchParams.set('f_price', Array.from(priceSet));

                    window.history.pushState({}, '', currentURL);
                };

                updateURLParams();
                //return false;
                window.history.go(0);
                $('html, body').scrollTop(0);
            }
        });

        // initialization of HSScrollNav component
        $.HSCore.components.HSScrollNav.init($('.js-scroll-nav'), {
            duration: 700
        });
        //---------------------------------------------
        // initialization of unfold component
        $.HSCore.components.HSUnfold.init($('[data-unfold-target]'), {
            afterOpen: function ( ) {
                $(this).find('input[type="search"]').focus( );
            },
            beforeClose: function ( ) {
                $('#hamburgerTrigger').removeClass('is-active');
            },
            afterClose: function ( ) {
                $('#headerSidebarList .collapse.show').collapse('hide');
            }
        });

        $('#headerSidebarList [data-toggle="collapse"]').on('click', function (e) {
            e.preventDefault();

            var target = $(this).data('target');

            if ($(this).attr('aria-expanded') === "true") {
                $(target).collapse('hide');
            }
            else {
                $(target).collapse('show');
            }
        });

        $('.nav-link').on('shown.bs.tab', function (e) {
            // Remove 'active' class from all tabs
            $('.nav-link').removeClass('active');

            // Add 'active' class to the currently shown tab
            $(e.target).addClass('active');
        });
    },

}

autoload.init();

//    var cartElement = $(".ec-shopping-bag");
//    var nextSpanElement = cartElement.next("span");
//
//    if (nextSpanElement.text() > 0) {
//        setInterval(function () {
//            cartElement.toggleClass("flash");
//        }, 700); // Мигайте на всеки 1 секунда (1000 милисекунди)
//    }

//} )(jQuery, window, document);

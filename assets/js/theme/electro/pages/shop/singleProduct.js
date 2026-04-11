// глобална init функция
window.initProductPlugins = function (scopeEl) {
  var $scope = $(scopeEl || document);

  // Slick (галерия)
  $scope.find('.js-slick-carousel').each(function () {
    var $el = $(this);
    if (!$el.hasClass('slick-initialized')) {
      $el.slick($el.data('slick') || { dots:false, arrows:true, infinite:true });
    }
  });

  // Fancybox (ако е наличен)
  if ($.fancybox) {
    $scope.find('.img-fancybox').fancybox({ loop: false });
  }

  // Quantity (+/-)
  $scope.find('.js-quantity').each(function () {
    var $w = $(this), $inp = $w.find('.js-result');
    // remove any other click handlers (HS quantity counter) to avoid double increments
    $w.find('.js-plus').off('click').on('click.qv', function(){ $inp.val((+($inp.val()||1))+1); });
    $w.find('.js-minus').off('click').on('click.qv', function(){
      var v = +($inp.val()||1); $inp.val(Math.max(1, v-1));
    });
  });
};

// стартиране за нормалната продуктова страница
$(function(){ window.initProductPlugins(document); });


( function ($, window, document, undefined) {

    var singleProduct = singleProduct || {};

    singleProduct = {
        'init': function ( ) {
            $root = this;
            self = this;

            //this.plugins( );
            this.setUpListeners( );
        },
        'plugins': function ( ) {
            // Lp = loadPlugins;
        },
        'setUpListeners': function ( ) {

            // EVENTS
            $(document).off('submit').on('submit', '#form_fastOrder', this.event.submit_fastOrder);

            //BTN
            $(document).on('click', '#btn_fast_order', this.btn.fast_order);

        },

        'event': {
            'submit_fastOrder': function (e) {
                e.preventDefault();
                var form = $(this);
                var isAgree = $('#is_agree:checked');
                var isPrivacyAgree = $('#is_privacy_agree:checked');

                if (!isAgree.is(':checked')) {
                    Lp.sweetAlert({
                        'text': 'Отметнете Съгласяване с общите правила и условия.',
                        'icon': 'warning',
                        'showConfirmButton': true,
                    });

                    return false;
                }

                if (!isPrivacyAgree.is(':checked')) {
                    Lp.sweetAlert({
                        'text': 'Отметнете съгласяване с политиката за поверителност.',
                        'icon': 'warning',
                        'showConfirmButton': true,
                    });

                    return false;
                }

                Lp.sweetAlert({
                    'title': 'Потвърждание на заявката',
                    'text': 'Сигурни ли сте, че искате да финализирате заявката?',
                    'icon': 'question',
                    'showCancelButton': true,
                    'showConfirmButton': true
                }, function (confirmed) {
                    if (confirmed) {
                        ajaxSend();
                    }
                });

                ajaxSend = function () {
                    Lp.sweetAlert({
                        'text': 'Моля изчакйте...',
                        'icon': 'info',
                        'allowOutsideClick': false,
                        'allowEscapeKey': false,
                        'didOpen': () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: form.serializeJSON( ),
                        dataType: 'json',
                        success: function (response) {

                            var data = response;
                            console.log(data);

                            Lp.sweetAlert({
                                'titleText': 'Статус на поръчката',
                                'html': data.errMessage ? data.message + data.errMessage : data.message,
                                'icon': 'success',
                                'showConfirmButton': true,
                                'allowOutsideClick': false,
                                'allowEscapeKey': false
                            }, function (confirmed) {
                                if (confirmed) {
                                    window.location.reload();
                                }
                            });
                        }
                    });
                };
            }
        },

        'btn': {
            'fast_order': function ( ) {
                $("#btn_fast_order").hide();
                $("#fastOrder_block").show();
            }
        }
    };

    singleProduct.init( );
} )(jQuery, window, document);






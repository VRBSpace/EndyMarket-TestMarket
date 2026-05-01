
/* global PRODUCTS_JSON, imageDir, axios, Promise */

( function ($, window, document, undefined) {


    var cart = cart || {};

    cart = {
        '__var': {
            table: '#cart-table'
        },
        'init': function ( ) {
            v = this.__var;
            $root = this;
            self = this;

            this.plugins( );
            this.setUpListeners( );
        },
        'plugins': function ( ) {
            Lp = loadPlugins;
            Lp.axios();

            if ($(location).attr('pathname') == '/Cart') {
                Lp.calculateCartTotals({'table': v.table});
            }
        },
        'setUpListeners': function ( ) {
            $(document).off('htmx:afterSwap').on('htmx:afterSwap', function (e) {

            })

            // EVENTS
            $(document)
                    // Метод на доставка
                    .on('change', '#js-deliveryMethod', this.events.change_deliveryMethod)
                    .on('change', '.js-delivery-source', this.events.change_deliverySource)
                    .on('input focusout', '.js-result', this.events.change_qty);

            //BTN
            $(document)
                    .on('click', '.btn-add-cart,#btn-add-cart-single', this.btn.addProductToCart)
                    .on('click', '.remove-product,.js-remove-from-cart', this.btn.removeProductFromCart)
                    .on('click', '.js-empty-cart', this.btn.emptyCart)
                    .on('click', '.js-plus, .js-minus', this.btn.plusMinus_qty)

                    .on('click', '#js-open-deliveryObekt', this.btn.openDeliveryObekt)
                    .on('click', '.js-delivery_izborObekt', this.btn.delivery_izborObekt)
                    .on('click', '#exportXls', this.btn.exportXls)
                    //.on('click', '.js-miniCart-btn', this.btn.openMiniCart)
                    .on('click', '.js-checkout-btn', this.btn.checkout);
        },

        'events': {

            'change_qty': function (e) {
                e.stopImmediatePropagation( );
                var parent = $(this).parents('.js-product-item');
                var productId = $(this).data('product-id');
                var settingMinPrice = $("#js-settingMinPrice");
                var text_minPrice = $("#js-text_minPrice");
                let freeDostavkaPrice = $("#js-freeDostavkaPrice").text();
                let freeDostRange = $("#js-freeDostRange").val() || 0;
                let totalFreePrice = $("#js-totalFreePrice");
                var qty = $(this).val();
                var result = '';
                let isMinPrice = '';
                let totalFreePriceValue = '';

                if (!productId || qty == 0) {
                    if (qty == 0) {
                        parent.find('.js-result').val(1);
                    }
                    return false;
                }

                if (e.type === 'input') {
                    result = Lp.calculateCartTotals({'table': v.table});
                    $('#js-label-totalQuantity').text(result.totalQty);

                    $(this).data('totalQty', result.totalQty);
                    $(this).data('totalPrice', result.totalPrice);

                    totalFreePriceValue = parseFloat(freeDostavkaPrice) - parseFloat(result.totalPrice);
                    totalFreePrice.text(totalFreePriceValue.toFixed(2));
                    isMinPrice = parseFloat(result.totalPrice) >= settingMinPrice.val();
                    text_minPrice.toggleClass('hide', isMinPrice);

                    $('#js-blockTotalFreePrice').toggleClass('hide', !( totalFreePriceValue > 0 && totalFreePriceValue <= freeDostRange ));
                    $('.js-checkout-btn').toggleClass('hide', !isMinPrice);

                    return false;
                }

                $.ajax({
                    url: $(this).data('route'),
                    type: 'POST',
                    data: {
                        'product_id': productId,
                        'qty': qty,
                        'totalQty': $(this).data('totalQty'),
                        'totalPrice': $(this).data('totalPrice')
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (!response.success) {
                            alert(response.message);
                        }

                        $('#js-cartItems #cart-table tfoot').load(location.href + " #js-cartItems #cart-table tfoot>*", function () {
                            $(document).trigger('cart:cartUpdated');
                        });
                    }
                });
            },

            // ако изберем друг метод на доставка разл от куриер
            'change_deliveryMethod': function (e) {
                e.stopImmediatePropagation( );
                var block = $('#js-deliveryObekt-block');

                $(this).val() !== 'curier' ? block.hide() : block.show();
            },

            'change_deliverySource': function (e) {
                e.stopImmediatePropagation();
                var source = $(this).val();

                $('#js-deliveryObekt-existing').toggleClass('hide', source !== 'object');
                $('#js-deliveryObekt-custom').toggleClass('hide', source !== 'custom');

                if (source === 'custom') {
                    var checkedCourier = $('#js-deliveryObekt-custom .js-deliveryMethod:checked');
                    if (!checkedCourier.length) {
                        checkedCourier = $('#js-deliveryObekt-custom .js-deliveryMethod:first');
                        if (checkedCourier.length) {
                            checkedCourier.prop('checked', true);
                        }
                    }

                    if (checkedCourier.length) {
                        checkedCourier.trigger('change');
                    }
                }

                $(document).trigger('cart:deliveryObjectChanged');
            }
        },

        'btn': {
            'plusMinus_qty': function (e) {
                e.stopImmediatePropagation( );
                var productId = $(this).data("product-id");
                var settingMinPrice = $("#js-settingMinPrice");
                var text_minPrice = $("#js-text_minPrice");
                var qty = $(this).closest('td').find('.js-result');
                let freeDostavkaPrice = $("#js-freeDostavkaPrice").text();
                let freeDostRange = $("#js-freeDostRange").val() || 0;
                let totalFreePrice = $("#js-totalFreePrice");
                var result = '';
                let isMinPrice = '';
                let totalFreePriceValue = '';

                if (!productId) {
                    return false;
                }

                if (Number(qty.val()) == 0) {
                    qty.val(1);
                    return false;
                }

                result = Lp.calculateCartTotals({'table': v.table});

                totalFreePriceValue = parseFloat(freeDostavkaPrice) - parseFloat(result.totalPrice);
                totalFreePrice.text(totalFreePriceValue.toFixed(2));
                isMinPrice = parseFloat(result.totalPrice) >= settingMinPrice.val();

                $('#js-label-totalQuantity').text(result.totalQty);
                $('#js-blockTotalFreePrice').toggleClass('hide', !( totalFreePriceValue > 0 && totalFreePriceValue <= freeDostRange ));
                console.log(qty.val());
                $.ajax({
                    url: $(this).data('route'),
                    type: 'POST',
                    data: {
                        'product_id': productId,
                        'qty': Number(qty.val()),
                        'totalQty': result.totalQty,
                        'totalPrice': result.totalPrice
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (!response.success) {
                            alert(response.message);
                        }
                        const values = {isRefresh: true};

                        htmx.ajax('GET', location.href, {
                            target: '#js-cartItems #cart-table tfoot',
                            swap: 'innerHTML',
                            select: '#js-cartItems #cart-table tfoot >*',
                            values: values
                        });

                        //$('#js-cartItems #cart-table tfoot').load(location.href + " #js-cartItems #cart-table tfoot>*");

                        text_minPrice.toggleClass('hide', isMinPrice);
                        $('.js-checkout-btn').toggleClass('hide', !isMinPrice);
                    }
                });
            },
//            'openMiniCart': function (e) {
//
//                $('.js-miniCart-content').show();
//
//                $(document).mouseup(function (e) {
//                    var container = $(this);
//                    if (!$(".js-miniCart-content *").is(e.target) && !container.is(e.target))
//                    {
//                        $('.js-miniCart-content').hide();
//                    }
//                });
//            },

            'exportXls': function (e) {
                e.stopImmediatePropagation( );
                e.preventDefault();
                var route = $(this).data('route');

                $.ajax({
                    url: route,
                    type: 'POST',
                    data: {'html': $('.js-printTable').html( )},
                    dataType: 'json',
                    success: function (data) {
                        var $a = $("<a>");
                        $a.attr("href", data.file);
                        $("body").append($a);
                        $a.attr("download", data.fileName);
                        $a[0].click();
                        $a.remove();
                    }
                });
            },

            'delivery_izborObekt': function (e) {
                var obekt = $(this).data('obekt');

                $.ajax({
                    url: $(this).data('route'),
                    method: 'POST',
                    dataType: "json",
                    data: {'obekt': obekt},
                    success: function (response) {
                        $('#js-deliveryObekt-block').html(response.view);
                        alert('Обектът е избран');
                        swal.close();
                        $(document).trigger('cart:deliveryObjectChanged');
                    }
                });
            },

            'openDeliveryObekt': function (e) {
                $.ajax({
                    url: $(this).data('route'),
                    method: 'POST',
                    dataType: "json",
                    success: function (response) {

                        Lp.sweetAlert({
                            'customClass': 'w-75 overflow-hidden',
                            'html': response.view,
                            'showCloseButton': true,
                            'showConfirmButton': false
                        });
                    }
                });
            },

            'addProductToCart': function (e) {
                e.stopImmediatePropagation( );
                var productId = $(this).data("product-id");
                var parent = $(this).parents('.js-product-item');
                // Намираме инпута за количество в рамките на родителския елемент
                var quantity = parent.find('.js-result').val();
                //var quantity = 1; // от този бутон винаги само по един брой можем да купуваме

                if (quantity == undefined) {
                    quantity = 1;
                }

                $.ajax({
                    url: $(this).data('route'),
                    method: 'POST',
                    data: {'product_id': productId, 'quantity': parseInt(quantity)},
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {

                            $('.js-miniCart-content').load(location.href + " .js-miniCart-content:first>*");
                            Lp.sweetAlert({
                                'titleText': response.productName,
                                'toast': true,
                                'position': 'top-end',
                                'text': response.message,
                                'icon': 'success',
                                'iconColor': 'white',
                                'showCloseButton': true,
                                'width': 'auto',
                                'customClass': {
                                    popup: 'colored-toast'
                                },
                                'showConfirmButton': false,
                                'timer': 2000,
                                'timerProgressBar': true
                            });

                            $('.js-cart-quantity').addClass('cart-effect').html(response.quantity);
                            parent.find('.js-result').val(1);
                        }
                        else {
                            Lp.sweetAlert({
                                'text': response.message,
                                'icon': 'error',
                                'showCloseButton': true,
                                'showConfirmButton': true
                            });
                        }
                    }
                });
            },

            'emptyCart': function (e) {
                e.stopImmediatePropagation( );
                e.preventDefault();

                var _this = $(this);

                Lp.sweetAlert({
                    'text': 'Сигурни ли сте, че искате да итриете цялата заявка за поръчка?',
                    'icon': 'warning',
                    'showCancelButton': true,
                    'showConfirmButton': true
                }, function (confirmed) {
                    if (confirmed) {
                        ajaxSend();
                    }
                });

                ajaxSend = function () {
                    $.ajax({
                        url: _this.attr('href'), // Заменете със URL на вашия рутер
                        type: 'POST',
                        dataType: 'json',
                        success: function (response) {
                            location.href = '/';
                        }
                    });
                };
            },

            'removeProductFromCart': function (e) {
                e.stopImmediatePropagation();
                var _this = $(this);
                var productId = $(this).data('product-id');
                var settingMinPrice = $('#js-settingMinPrice');
                var text_minPrice = $('#js-text_minPrice');

                Lp.sweetAlert({
                    'text': 'Сигурни ли сте, че искате да премахнете избраният продукт?',
                    'icon': 'warning',
                    'showCancelButton': true
                }, function (confirmed) {

                    if (confirmed) {
                        ajaxSend();
                    }
                });

                ajaxSend = function ( ) {
                    $.ajax({
                        url: _this.data("route"), // Заменете със URL на вашия рутер
                        type: 'POST',
                        data: {'product_id': productId}, // Пратете product_id като данни
                        dataType: 'json',
                        success: function (response) {
                            if (!response.success) {
                                return alert(response.message);
                            }

                            let miniCartTotalQty = $('.js-cart-quantity:first').text();
                            let isMinPrice = parseFloat(response.totalPrice) * 1.2 >= settingMinPrice.val();

                            if (response.totalQuantity == 0) {
                                return location.href = '/';
                            }

                            // ако сме в изглед Cart
                            if ($(location).attr('pathname') == '/Cart') {
                                $('#js-cartItems').load(location.href + " #js-cartItems>*", function () {
                                    $.HSCore.components.HSQantityCounter.init('.js-quantity');

                                    text_minPrice.toggleClass('hide', isMinPrice);
                                    $('.js-checkout-btn').toggleClass('hide', !isMinPrice);
                                    $(document).trigger('cart:cartUpdated');
                                });
                            }
                            else {
                                $('.js-miniCart-content').load(location.href + " .js-miniCart-content:first>*", function () {
                                    //$('.cart-price').text(response.totalPrice);
                                    $('.js-cart-quantity').text(Number(miniCartTotalQty) - 1);
                                });
                            }
                        }
                    });
                };
            },

            'checkout': function (e) {
                var cart = {};
                var route = $(this).data('route');
                var deliveryObj = $('#deliveryObj').val();
                var deliveryMethod = $('#js-deliveryMethod').val();
                // var totalPrice = $('#total_price').val();
                var paymentMethod = $('.js-payment_method:checked');
                var deliveryPrice = $(document).find('#cart-table tfoot tr#deliveryRow #deliveryPrice').val() || 0;
                var missingObekt = $('#missingObekt');
                var deliveryMethod = $('#js-deliveryMethod').val(); // метод на доставка
                var belezka = $('#js-belezka').val(); // метод на доставка
                var deliverySource = $('.js-delivery-source:checked').val() || 'object';

                if (!paymentMethod.is(':checked')) {
                    Lp.sweetAlert({
                        'text': 'Изберете метод на плащане',
                        'icon': 'warning',
                        'showConfirmButton': true,
                    });

                    return false;
                }

                // ако липсва  обект на дост и ако метода на доставка е куриер
                if (missingObekt.length && deliveryMethod == 'curier' && deliverySource === 'object') {
                    Lp.sweetAlert({
                        'html': 'Липсва обект на доставка с куриер.<br><a href="/Account">Линк към профила</a>',
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
                    cart.payment_method = paymentMethod.val();
                    //cart.total_price = totalPrice;

                    cart.delivery_obekt = deliveryObj;
                    cart.delivery_method = deliveryMethod;
                    cart.belezka = belezka;
                    cart.deliveryPrice = deliveryPrice;

                    if (deliveryMethod === 'curier' && deliverySource === 'custom') {
                        var courierMethod = $('input[name="delivery_json[izborKurier]"]:checked').val();
                        var city = $('#js-grad').val();
                        var postCode = $('#js-postCode').val();
                        var office = $('#js-ofis').val();
                        var quarter = $('input[name="delivery_json[quarter]"]').val();
                        var street = $('input[name="delivery_json[street]"]').val();
                        var streetNum = $('input[name="delivery_json[street_num]"]').val();
                        var other = $('input[name="delivery_json[other]"]').val();

                        if (!courierMethod || !city) {
                            Lp.sweetAlert({
                                'text': 'Попълнете данните за куриер и населено място.',
                                'icon': 'warning',
                                'showConfirmButton': true
                            });
                            return false;
                        }

                        if ((courierMethod.indexOf('_office') > -1 || courierMethod.indexOf('_machina') > -1) && !office) {
                            Lp.sweetAlert({
                                'text': 'Моля попълнете офис/автомат за доставка.',
                                'icon': 'warning',
                                'showConfirmButton': true
                            });
                            return false;
                        }

                        if (courierMethod.indexOf('_door') > -1 && !streetNum) {
                            Lp.sweetAlert({
                                'text': 'Моля попълнете адрес за доставка.',
                                'icon': 'warning',
                                'showConfirmButton': true
                            });
                            return false;
                        }

                        cart.delivery_obekt = '';
                        cart.delivery_json = {
                            izborKurier: courierMethod,
                            grad: city,
                            postCode: postCode,
                            ofis: office,
                            quarter: quarter,
                            street: street,
                            street_num: streetNum,
                            other: other
                        };
                    }

                    Lp.sweetAlert({
                        'text': 'Моля изчакайте...',
                        'icon': 'info',
                        'allowOutsideClick': false,
                        'allowEscapeKey': false,
                        'didOpen': () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: route,
                        type: 'POST',
                        data: JSON.stringify(cart),
                        dataType: 'json',
                        success: function (response) {

                            var data = response;

                            Lp.sweetAlert({
                                'titleText': 'Статус на поръчката',
                                'html': data.errMessage ? data.errMessage : data.message,
                                'icon': data.errMessage ? 'error' : 'success',
                                'showConfirmButton': true,
                                'allowOutsideClick': false,
                                'allowEscapeKey': false
                            }, function (confirmed) {
                                if (confirmed && !data.errMessag) {
                                    location.href = '/';
                                }
                            });

                        }
                    });

                };
            }
        }
    };

    cart.init( );

} )(jQuery, window, document);

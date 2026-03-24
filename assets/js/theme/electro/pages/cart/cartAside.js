
/* global PRODUCTS_JSON, imageDir, axios, Promise */

( function ($, window, document, undefined) {


    var cartAside = cartAside || {};

    cartAside = {
        '__var': {
            table: '#cart-table'
        },
        'init': function ( ) {
            v = this.__var;
            $root = this;
            self = this;

            this.plugins( );
            this.setUpListeners( );
            $('.js-deliveryMethod:checked').trigger('change');
        },
        'plugins': function ( ) {
            Lp = loadPlugins;
            Lp.axios();
        },
        'setUpListeners': function ( ) {

            // EVENTS
            $(document)
                    .on('keyup', '#js-grad', Lp.debounce(this.event.autocomplete_city, 500))
                    .on('keyup', '#js-ofis', this.event.filter_ofice)
                    .on('change', '.js-deliveryMethod', this.event.choose_deliveryMethod);


            //BTN
            $(document)
                    .on('click', '#js-ofis', this.btn.get_ofice)
                    .on('click', '#js-checkout-klient-btn', this.btn.checkout);

        },

        'event': {
            'filter_ofice': function (e) {
                e.stopImmediatePropagation();
                var _this = $(this);
                var listItems = $('.autocomplete:visible').find('ul li');
                var searchText = _this.val().toLowerCase().trim();
                let autocompleteContainer = _this.parent().find('.autocomplete');

                if (searchText == '') {
                    _this.parent('.js-form-message').removeClass('u-has-success').addClass('u-has-error');
                    _this.val('');
                    autocompleteContainer.empty();
                    return false;
                }

                listItems.each(function () {
                    var listItemText = $(this).find('div').text().toLowerCase().trim();
                    var shouldShow = listItemText.includes(searchText);
                    $(this).toggle(shouldShow);
                });

            },

            'autocomplete_city': function (e) {
                e.stopImmediatePropagation();

                let _this = $(this);
                let route = _this.data('route');
                let postCode = $(document).find('#js-postCode');
                let ofis = $(document).find('#js-ofis');
                let autocompleteContainer = _this.parent().find('.autocomplete');
                let autocompleteHtml = '';

                _this.removeAttr('data-city-id');

                if (!route) {
                    let selectedCourier = $('.js-deliveryMethod:checked');
                    if (selectedCourier.length) {
                        route = selectedCourier.data('route');
                        let kurier = selectedCourier.val();

                        _this.attr('data-route', route).attr('data-kurier', kurier).data('route', route).data('kurier', kurier);
                        ofis.attr('data-route', route).attr('data-kurier', kurier).data('route', route).data('kurier', kurier);
                    }

                    if (!route) {
                        Lp.sweetAlert({
                            text: 'изберете куриер за доставка',
                            icon: 'warning',
                            showConfirmButton: true
                        });
                        return false;
                    }
                }

                if (!_this.val().length) {
                    postCode.val('');
                    ofis.val('');
                    autocompleteContainer.empty();
                    return false;
                }

                $.ajax({
                    url: route,
                    type: 'POST',
                    dataType: 'json',
                    data: {'method': 'get_cities', 'cityName': _this.val()},
                    success: function (data) {

                        data = typeof data === 'string' ? JSON.parse(data) : data;

                        if (!data.result || !data.result.cities || data.result.cities.length === 0) {
                            autocompleteContainer.html('Няма намерени населени места.');
                            return false;
                        }

                        autocompleteHtml = `<ul>${data.result.cities.map(city => {
                            let myObj = {'id': city.id, 'postCode': city.postCode, 'name': city.name};
                            return `<li data-info='${JSON.stringify(myObj)}'>${city.name}</li>`;
                        }).join('')}</ul>`;

                        autocompleteContainer.html(autocompleteHtml);

                        // когато изберем от списъка autocomplete
                        autocompleteContainer.off('mousedown').on('mousedown', 'li', function () {
                            let info = $(this).data('info');
                            _this.val(info.name).attr('data-city-id', info.id);
                            autocompleteContainer.empty(); // Clear autocomplete list after selection
                            postCode.val(info.postCode);
                        });

                        // Clear autocomplete on input blur
                        _this.on('blur', () => autocompleteContainer.empty());
                    }
                });
            },

            // избор на куриер
            'choose_deliveryMethod': function (e) {
                let $this = $(this);
                let scope = $this.closest('#js-deliveryObekt-custom');
                if (!scope.length) {
                    scope = $this.closest('form');
                }
                if (!scope.length) {
                    scope = $(document);
                }
                let name = $this.val();
                let route = $this.data('route');
                let autocompleteContainer = $('.autocomplete');

                let deliveryOptions = {
                    'econt_office': {showToOfis: true, showToDoor: false},
                    'speedy_office': {showToOfis: true, showToDoor: false},
                    'econt_door': {showToOfis: false, showToDoor: true},
                    'speedy_door': {showToOfis: false, showToDoor: true}
                };

                if ($this.is(':checked')) {
                    let options = deliveryOptions[name] || {showToOfis: false, showToDoor: false};
                    let toOfis = scope.find('#toOfis');
                    let toDoor = scope.find('#toDoor');
                    let gradAndOfis = scope.find('#js-grad, #js-ofis');

                    toOfis.toggleClass('hide', !options.showToOfis);
                    toDoor.toggleClass('hide', !options.showToDoor);
                    toOfis.add(toDoor).find(':input').val('');
                    gradAndOfis.attr('data-route', route).attr('data-kurier', name).data('route', route).data('kurier', name);
                    autocompleteContainer.empty();
                }
            },
        },

        'btn': {
            'get_ofice': function (e) {
                e.stopImmediatePropagation();
                let _this = $(this);
                let route = _this.data('route');
                let kurier = _this.data('kurier');
                let cityName = $('#js-grad');
                let cityId = cityName.attr('data-city-id');
                let autocompleteContainer = _this.parent().find('.autocomplete');
                let alertText = '';
                let autocompleteHtml = '';

                if (!cityName.val()) {
                    alertText = 'Моля попълнете първо населеното място.';
                }
                else if (!cityId && kurier === 'econt_office') {
                    alertText = 'За избор на Еконт офис след намиране на населеното място от списъка е необходимо да се избере от списъка с намерени резултати.';
                }
                console.log(cityId);
                console.log(kurier);
                if (alertText) {
                    Lp.sweetAlert({
                        text: alertText,
                        icon: 'warning',
                        showConfirmButton: true
                    });
                    return false;
                }

                $.ajax({
                    url: route,
                    type: 'POST',
                    dataType: 'json',
                    data: {'method': 'get_ofices', 'cityId': cityId, 'cityName': cityName.val()},
                    success: function (data) {
                        if (!data.offices || data.offices.length === 0) {
                            autocompleteContainer.html('Няма намерени офиси за избраното населено място.');
                            return false;
                        }

                        autocompleteHtml = `<ul>${data.offices.map(obj => {
                            let myObj = {id: obj.id, address: obj.address, name: obj.name};
                            return `<li data-info='${JSON.stringify(myObj)}' value='${obj.id}'>
                            <div class="fw-bold">${obj.name}</div>
                            <small>${obj.address.fullAddressString || obj.address.fullAddress}</small>
                        </li>`;
                        }).join('')}</ul>`;

                        autocompleteContainer.html(autocompleteHtml);

                        // когато изберем от списъка autocomplete
                        autocompleteContainer.off('mousedown').on('mousedown', 'li', function () {
                            let info = $(this).data('info');
                            let myObj = {'id': info.id, 'code': info.code, 'postCode': info.address.postCode, 'name': info.name};
                            let selectedText = $(this).html();
                            let textarea = $(e.target);



                            textarea.attr('data-info', JSON.stringify(myObj));
                            textarea.height(this.scrollHeight);

                            // Check if the selected item has a <small> tag in the text
                            if (selectedText.includes('<small>')) {
                                // Insert text into the textarea with a new line
                                let t = $('<div>').html(selectedText.replace(/<small>.*<\/small>/, '').trim() + '\n' + selectedText.match(/<small>(.*)<\/small>/)[1] + '\n');

                                textarea.val(t.text());
                            }

                            autoResize.call(textarea[0]);
                            autocompleteContainer.empty(); // Clear autocomplete list
                        });

                        // Clear autocomplete on input blur
                        _this.on('blur', () => autocompleteContainer.empty());

                        function autoResize () {
                            $(this).height(this.scrollHeight); // Set the height to scrollHeight
                        }
                    }
                });
            },

            'checkout': function (e) {
                var route = $(this).data('route');
                var form = $('#form-chekout');
                var isAgree = $('#is_agree:checked');
                var deliveryPrice = $(document).find('#cart-table tfoot tr#deliveryRow #deliveryPrice').val() || 0;
                let isValid = form.valid();

                if (!isValid) {
                    return false;
                }

                if (!isAgree.is(':checked')) {
                    Lp.sweetAlert({
                        'text': 'Отметнете Съгласяване с общите правила и условия.',
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
                        'text': 'Моля изчакайте...',
                        'icon': 'info',
                        'allowOutsideClick': false,
                        'allowEscapeKey': false,
                        'didOpen': () => {
                            Swal.showLoading();
                        }
                    });

                    var cleanJsonData = Object.fromEntries(
                            Object.entries(form.serializeJSON( )).filter(([_, v]) => v != null && v !== "")
                            );
                    ;
                    var requestData = {
                        ...cleanJsonData,
                        'deliveryPrice': deliveryPrice
                    };

                    $.ajax({
                        url: route,
                        type: 'POST',
                        data: requestData,
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
                                if (confirmed && !data.errMessage) {
                                    location.href = '/';
                                }
                            });
                        }
                    });
                };
            }
        }
    };

    cartAside.init( );

} )(jQuery, window, document);

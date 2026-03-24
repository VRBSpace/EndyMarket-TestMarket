/* global PRODUCTS_JSON, imageDir */

( function ($, window, document, undefined) {
    var account = account || {};

    account = {
        'init': function ( ) {
            $root = this;
            self = this;

            this.plugins( );
            this.setUpListeners( );
        },
        'plugins': function ( ) {
            Lp = loadPlugins;
        },
        'setUpListeners': function ( ) {

            // EVENTS
            $(document)
                    .on('keyup', '.js-autocompleteCity', Lp.debounce(this.event.autocomplete_city, 300))
                    .on('change', '.js-izborKurier', this.event.changeKurier)
                    .on('submit', '#form-changePassword', this.event.save_changePassword)
                    .on('submit', '#form-customerData', this.event.save_customerData)
                    .on('submit', '#form-deliveryData', this.event.save_deliveryData);

            //BTN
            $(document)
                    .on('click', 'tbody .econtLocator', this.btn.econtLocator)
                    .on('click', 'tbody .speedyLocator', this.btn.speedyLocator)
                    .on('click', '.js-grad', this.btn.open_autocomplete_city)
                    .on('click', '.js-clearCity', function () {
                        var tbody = $(this).closest('tbody');
                        var section = $(this).closest('td');
                        section.find('.js-grad').attr('value', '').val('');
                        tbody.find('tr.toCity .js-postCode').attr('value', '').val('');
                    })
                    .on('click', '#delivery-tab .js-duplicateTable', this.btn.duplicateTable)
                    .on('click', 'thead .js-deleteTable', this.btn.deleteTable);
        },

        'event': {
            // autocomplete за градовете
            'autocomplete_city': function (e) {
                e.stopImmediatePropagation();
                var _this = $(this);
                var tbody = _this.closest('tbody');
                var section = _this.closest('td');
                var autocomplete = section.find('.js-autocomplete');
                var inputWidth = _this.outerWidth();
                var route = autocomplete.data('route');
                var currentVal = _this.val().trim();
                let maxChar = 3;
                let remainingChars = maxChar - currentVal.length;
                let autocompleteHtml = '';

                // Clear autocomplete suggestions if input is empty
                if (!currentVal) {
                    autocomplete.find('ul').remove();
                    return false;
                }

                if (currentVal.length < maxChar) {
                    autocomplete.find('ul').remove(); // Ensure a fresh start
                    autocomplete.append(`<ul style="width: ${inputWidth}px;"><li>Въведете най-малко ${remainingChars} символа</li></ul>`);
                    return false;
                }

                $.ajax({
                    url: route,
                    type: 'POST',
                    global: false,
                    dataType: 'json',
                    data: {'method': 'get_cities', 'cityName': currentVal},
                    success: function (response) {
                        const data = typeof response === 'string' ? JSON.parse(response) : response;

                        if (!data.result || !data.result.cities || data.result.cities.length === 0) {
                            autocomplete.find('ul').remove(); // Clear previous content
                            autocomplete.append(`<ul style="width: ${inputWidth}px;"><li>Няма намерени населени места.</li></ul>`);
                            return;
                        }

                        autocompleteHtml = data.result.cities.map(city => {
                            let cityInfo = JSON.stringify({id: city.id, postCode: city.postCode, name: city.name});
                            return `<li data-info='${cityInfo}'>${city.name}</li>`;
                        }).join('');

                        autocomplete.find('ul').remove(); // Clear previous content
                        autocomplete.append(`<ul style="width: ${inputWidth}px;">${autocompleteHtml}</ul>`);
                    }
                });

                autocomplete.on('mousedown', 'li', function () {

                    const info = $(this).data('info');
                    section.find('.js-grad').val(info.name);
                    tbody.find('tr.toCity .js-postCode').val(info.postCode);
                    autocomplete.empty();
                });
            },

            // избор на куриер
            'changeKurier': function (e) {
                var _this = $(this);
                var data = _this.find('option:selected').data('arg');
                var tbody = _this.closest('tbody');
                const isEcont = [ 'econt_office', 'econt_machina' ].includes(_this.val( ));
                const isSpeedy = [ 'speedy_office', 'speedy_machina' ].includes(_this.val( ));

                tbody.find('tr.toOfice :input:not(select):not(button)').val('');
                tbody.find('tr.toAdres :input:not(select):not(button)').val('');
                tbody.find('tr.toCity :input:not(select):not(button)').val('');


                tbody.find('tr.toOfice').toggleClass('hide', data !== 'office');
                tbody.find('tr.toAdres').toggleClass('hide', data !== 'door');
                tbody.find('tr.toCity').removeClass('hide');
                tbody.find('tr.toCity input, tr.toOfice input').toggleClass('css-pointer-events-none', data === 'office');
                tbody.find('tr.toCity .js-clearCity').toggleClass('hide', data !== 'door');


                tbody.find('.econtLocator').toggleClass('hide', !isEcont);
                tbody.find('.speedyLocator').toggleClass('hide', !isSpeedy);
            },

            'save_changePassword': function (e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                e.stopPropagation();

                var password = $('#signinPassword').val();
                var passwordConfirm = $('#signinPasswordConfirm').val();
                var email = $('#signinEmail').val();
                var passwordPolicy = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/;

                if (password !== '' && password !== passwordConfirm) {
                    Lp.sweetAlert({
                        'text': 'Паролите не съвпадат.',
                        'icon': 'error',
                        'showCloseButton': true,
                        'showConfirmButton': true
                    });
                    return false;
                }

                if (password === '' || passwordPolicy.test(password)) {

                    axios({
                        url: $(this).data('route'),
                        method: 'POST',
                        data: {'password': password, 'password_confirm': passwordConfirm, 'email': email}
                    }).then(function (response) {
                        var data = response.data;

                        Lp.sweetAlert({
                            'text': data.message,
                            'icon': data.success ? 'success' : 'error',
                            'showCloseButton': true,
                            'showConfirmButton': true
                        }, function () {
                            if (data.success) {
                                history.go(0);
                            }
                        });
                    }).catch(function () {
                        Lp.sweetAlert({
                            'text': 'Възникна техническа грешка.',
                            'icon': 'error',
                            'showCloseButton': true,
                            'showConfirmButton': true
                        });
                    });
                }
                else {
                    Lp.sweetAlert({
                        'text': 'Паролата трябва да е минимум 8 символа и да съдържа малка буква, главна буква, цифра и специален символ.',
                        'icon': 'error',
                        'showCloseButton': true,
                        'showConfirmButton': true
                    });
                }

                return false;
            },

            // При събмит на формата данни за фирмата
            'save_customerData': function (e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                e.stopPropagation();
                var form = $(this);
                $('#page-preloader').removeClass('is-active');
                $('body').removeClass('preloader-open');

                if (!form.valid()) {
                    return false;
                }

                axios({
                    url: form.data('route'),
                    method: 'POST',
                    data: {'form-customerData': form.serializeJSON()}
                }).then(function (response) {
                    var data = response.data;

                    Lp.sweetAlert({
                        'text': data.message,
                        'icon': data.success ? 'success' : 'error',
                        'showCloseButton': true,
                        'showConfirmButton': true
                    });
                }).catch(function () {
                    Lp.sweetAlert({
                        'text': 'Възникна техническа грешка при записа.',
                        'icon': 'error',
                        'showCloseButton': true,
                        'showConfirmButton': true
                    });
                });

                return false;
            },

            // При събмит на формата данни за доставка
            'save_deliveryData': function (e) {
                e.preventDefault();
                let defaultObekt = $('.js-defaultObekt');
                let jsonData = $(this).serializeJSON();

                if (defaultObekt.is(':visible') && !defaultObekt.is(':checked')) {
                    alert('Изберете основен адрес');
                    return false;
                }



                axios({
                    url: $(this).data('route'),
                    method: 'POST',
                    data: jsonData
                }).then(function (response) {
                    var data = response.data;

                    if (data.success) {
                        Lp.sweetAlert({
                            'text': data.message,
                            'icon': 'success',
                            'showCloseButton': true,
                            'showConfirmButton': true
                        });
                    }
                    else {
                        alert(data.message);
                    }
                });
            }
        },

        'btn': {
            'open_autocomplete_city': function (e) {
                var _this = $(this);
                var section = _this.closest('td');
                var autocomplete = section.find('.js-autocomplete');
                var autocompleteHtml = '';

                autocompleteHtml = `<input class="js-autocompleteCity w-100" type="text" placeholder="въведете населено място" style="border: 5px solid #eac263;"><ul class="bg-transparent"></ul>`;
                autocomplete.html(autocompleteHtml);
                autocomplete.find('.js-autocompleteCity').focus();

                $(document).on('mousedown.autocomplete', function (e) {
                    if (!autocomplete.is(e.target) && autocomplete.has(e.target).length === 0) {
                        autocomplete.empty(); // Clear suggestions
                    }
                });
            },

            'econtLocator': function (e) {
                e.stopImmediatePropagation( );

                $('body').append('<div id="iframe-container" class="modal-backdrop"></div>');

                var _this = $(this);
                var iframeContainer = $('#iframe-container');
                var iframeSrc = 'https://officelocator.econt.com?officeType=office&lang=bg&shopUrl=demo';
                var shippingMethod = $('.js-izborKurier');

                iframeContainer.css({'z-index': '11111111111111111', 'width': '95%', 'left': '3%', 'max-height': '95%'})
                        .html('<span class="d-table bg-warning w-100"><a class="float-right p-1" href="javascript:;" onclick="$(this).closest(\'#iframe-container\').remove();$(\'.modal-backdrop:first\').remove()">[X]</a><span>')
                        .append($('<iframe></iframe>')
                                .attr({src: iframeSrc})
                                .css({width: '100%', height: '100%'}));

                iframeContainer.after('<div class="modal-backdrop fade show"></div>');

                $(window).on('message', function (e) {
                    var office = e.originalEvent.data.office;

                    // _this.closest('section').find('.grad').val(office.id);
                    _this.closest('tbody').find('.js-grad').val(office.address.city.name);
                    _this.closest('tbody').find('.js-postCode').val(office.address.city.postCode);
                    _this.closest('tbody').find('.js-izborOfice').val(office.id + ' ' + office.name);

                    if (office.type === 'ekontomat') {
                        _this.closest('tbody').find('.js-izborKurier option').removeAttr('selected');
                        _this.closest('tbody').find('.js-izborKurier option[value="econt_machina"]').prop("selected", true);
                    }

                    //премахваме локатора
                    iframeContainer.remove( );
                    $('.modal-backdrop:first').remove( );
                });
            },

            'speedyLocator': function (e) {
                e.stopImmediatePropagation( );

                $('body').append('<div id="iframe-container" class="modal-backdrop"></div>');

                var _this = $(this);
                var iframeContainer = $('#iframe-container');
                var iframeSrc = 'https://services.speedy.bg/speedy_office_locator_widget/office_locator.php?officeID=47&selectOfficeButtonCaption=Избери офис';

                iframeContainer.css({'position': 'fixed', 'z-index': '11111111111111111', 'width': '95%', 'left': '3%', 'max-height': '95%'})
                        .html('<span class="d-table bg-warning w-100"><a class="float-right p-1" href="javascript:;" onclick="$(this).closest(\'#iframe-container\').remove();$(\'.modal-backdrop:first\').remove()">[X]</a><span>')
                        .append($('<iframe></iframe>')
                                .attr({src: iframeSrc})
                                .css({width: '100%', height: '100%'}));

                iframeContainer.after('<div class="modal-backdrop fade show"></div>');


                $(window).off('message').on('message', function (event) {
                    if (event.originalEvent.data) {

                        $.ajax({
                            // url: '/modul/Order/corsProxy',
                            url: _this.data('route'),
                            type: "POST",
                            dataType: 'json',
                            data: {'siteId': event.originalEvent.data},
                            success: function (data) {
                                var office = data.office;

                                _this.closest('tbody').find('.js-grad').val(office.address.siteAddressString);
                                _this.closest('tbody').find('.js-postCode').val(office.address.postCode);
                                _this.closest('tbody').find('.js-izborOfice').val(office.id + ' ' + office.name);
                                if (office.type === 'APT') {
                                    _this.closest('tbody').find('.js-izborKurier option').removeAttr('selected');
                                    _this.closest('tbody').find('.js-izborKurier option[value="speedy_machina"]').prop("selected", true);
                                }

                                iframeContainer.remove( );
                                $('.modal-backdrop:first').remove( );
                            }
                        });
                    }
                });
            },

            // дублиране на обект за доставка
            'duplicateTable': function (e) {
                var form = $(this).closest('form');
                var originalClone = form.find('.clone');
                var clone = originalClone.first().clone(true);
                let len = form.children('.clone:visible').length;
                let isClonedHiden = clone.hasClass('hide');

                clone.find(":input").attr('name', function () {
                    return this.name.replace(/^\[.*?\]/, '[' + ( len + 1 ) + ']');
                });

                clone.find(':input:not(:radio)').val('');
                clone.find(':radio').prop('checked', isClonedHiden && len == 1 ? true : false);

                clone.find('.js-izborKurier').attr('required', true);

                clone.find('tr.curier').removeClass('hide');
                clone.find('tr.toOfice, tr.toAdres, tr.toCity').toggleClass('hide', true);
                clone.find('tr.toCity input, tr.toOfice input').toggleClass('css-pointer-events-none2', true);
                clone.removeClass('hide');

                originalClone.last().after(clone);
                form[0].scrollIntoView({behavior: 'smooth', block: 'end'});
            },

            'deleteTable': function (e) {
                var parentForm = $(this).closest('form');
                var clone = $(this).closest('.clone');

                if (parentForm.children('div').length === 1) {
                    clone.addClass('hide');
                    clone.find(':input').val('');
                    clone.find('.js-defaultObekt').attr('value', 1);
                    $('.invalid-feedback').remove('.invalid-feedback');
                }
                else {
                    clone.remove();
                }
            }
        }
    };

    account.init( );

} )(jQuery, window, document);

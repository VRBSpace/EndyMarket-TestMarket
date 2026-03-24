// филтри за атрибути на категории в стр. shop
( function ($, window, document, undefined) {
    if (localStorage.getItem('isListView')) {
        $('#js-list-view').addClass('active');
        $('#js-grid-view').removeClass('active');
        $('#js-products-grid').removeClass('active show');
        $('#js-products-list').addClass('active show');
    }
    
    var filters = filters || {};
    filters = {
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
            $(document)
                    .on('change', '.js-shopRelationFilter', this.event.shopRelationFilter)
                    .on('change', '.js-productFilter', this.event.product_char_filter)
                    .on('keyup', '#filter-modelsByName', this.event.filter_modelsByName)
                    .on('click', '#js-list-view', this.btn.changeToList)
                    .on('click', '#js-grid-view', this.btn.changeToGrid)
        },
        'event': {
            // филтър по релация в shop страницата по марка модел подкатегория
            'shopRelationFilter': function (e) {
                e.stopImmediatePropagation();

                const $el = $(this);
                let catLevel = parseInt($el.data('level'), 10);
                let selectedOption = $el.find('option:selected');
                let dataUrl = selectedOption.data('url');
                let loader = $('#page-preloader');



                // Ако няма избрана стойност за подкатегория
                if (!selectedOption.val() && catLevel && dataUrl) {
                    let url = new URL(dataUrl, window.location.origin);
                    let f_subCatIds = url.searchParams.get("f_subCatIds");

                    if (f_subCatIds) {
                        let parts = f_subCatIds.split("_");
                        parts = parts.slice(0, catLevel - 1); // ниво 1 => 0 елемента, ниво 2 => 1 и т.н.

                        url.searchParams.set("f_subCatIds", parts.join("_"));
                        dataUrl = url.toString(); // Промененият dataUrl
                    }
                }

                loader.addClass('is-active');
                
                history.pushState(null, '', dataUrl);

                htmx.ajax('GET', dataUrl, {
                    target: 'main',
                    select: 'main > *'
                });

                htmx.on('htmx:afterRequest', (evt) => {
                    loader.removeClass('is-active');
                });
            },

            // филтър на име на модел
            'filter_modelsByName': function (e) {
                var value = $(this).val().toLowerCase();
                let parentLi = $(this).closest('li'); // Find the closest parent div

                parentLi.find('ul li').filter(function () {
                    $(this).toggle($(this).text().trim().toLowerCase().indexOf(value) > -1);
                });
            },

            // филтър на х-ки на продукти
            'product_char_filter': function (e) {
                const _this = $(this);
                const currentURL = new URL(window.location.href);
                const isChecked = _this.is(':checked');
                const value = _this.val();

                const getParamSet = (url, param) => {
                    const paramValue = url.searchParams.get(param);
                    return new Set(paramValue ? paramValue.split(/[_ ,]+/).filter(Boolean) : [ ]);
                };

                // f_podCharValId
                let prodCharIdSet = getParamSet(currentURL, 'f_podCharValId');

                if (_this.hasClass('js-attribute')) {
                    if (isChecked) {
                        prodCharIdSet.add(value);
                    }
                    else {
                        prodCharIdSet.delete(value);
                    }

                    if (prodCharIdSet.size > 0) {
                        currentURL.searchParams.set('f_podCharValId', Array.from(prodCharIdSet).join('_'));
                    }
                    else {
                        currentURL.searchParams.delete('f_podCharValId');
                    }
                }

                // f_brandId / brandTxt
                if (_this.hasClass('js-manufactureChk') || _this.data('tip') === 'manufacture') {
                    let brandIdSet = getParamSet(currentURL, 'f_brandId');

                    if (isChecked) {
                        brandIdSet.add(value);
                    } else {
                        brandIdSet.delete(value);
                    }

                    if (brandIdSet.size > 0) {
                        currentURL.searchParams.set('f_brandId', Array.from(brandIdSet).join('_'));

                        // Поддържаме четим brandTxt за control bar.
                        const selectedBrandNames = $('.js-manufactureChk:checked')
                            .map(function () {
                                return $(this).closest('li').text().trim();
                            })
                            .get()
                            .filter(Boolean);

                        if (selectedBrandNames.length > 0) {
                            currentURL.searchParams.set('brandTxt', selectedBrandNames.join(', '));
                        } else {
                            currentURL.searchParams.delete('brandTxt');
                        }
                    } else {
                        currentURL.searchParams.delete('f_brandId');
                        currentURL.searchParams.delete('brandTxt');
                    }

                    // при смяна/чистене на марка нулираме моделите
                    currentURL.searchParams.delete('f_models');
                    currentURL.searchParams.delete('f_modelsOther');
                }

                // f_models (multi-select с "_")
                if (_this.hasClass('js-models')) {
                    let modelsSet = getParamSet(currentURL, 'f_models');

                    if (isChecked) {
                        modelsSet.add(value);
                    } else {
                        modelsSet.delete(value);
                    }

                    if (modelsSet.size > 0) {
                        currentURL.searchParams.set('f_models', Array.from(modelsSet).join('_'));
                    } else {
                        currentURL.searchParams.delete('f_models');
                    }
                }

                // f_instock
                if (_this.attr('id') === 'js-nalichChk') {
                    const instockSet = getParamSet(currentURL, 'f_instock');
                    if (isChecked) {
                        instockSet.add(value);
                    }
                    else {
                        instockSet.delete(value);
                    }

                    if (instockSet.size > 0) {
                        currentURL.searchParams.set('f_instock', Array.from(instockSet));
                    }
                    else {
                        currentURL.searchParams.delete('f_instock');
                    }
                }

                currentURL.searchParams.set('page', 1);

                window.history.pushState({}, '', currentURL);
                window.history.go(0);
                $('html, body').scrollTop(0);

                e.preventDefault();
                e.stopImmediatePropagation();
            }
        },
        'btn': {
            // филтър по наличнист на продукти
            'changeToList': function (e) {
                localStorage.setItem('isListView', 1);
            },
            'changeToGrid': function (e) {
                localStorage.removeItem("isListView");
            }
        }
    };
    filters.init( );
} )(jQuery, window, document);

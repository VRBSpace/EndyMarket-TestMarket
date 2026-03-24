/* global ajax_searchProducts, LANG__global, parseFloat, parseInt,  Lp */

var loadPlugins = loadPlugins || {};
loadPlugins = {
    'Lp': this,
// === инициализация на дървовидна струйтура ======================
    initTree: function (selector, attr = {}, depth, url = '', urlDelete = '', file = '' ) {

        var HTMLTree = $(selector).jstree({
            "core": {
                "multiple": false,
                "animation": true,
                "check_callback": true,
                "themes": {"stripes": true,
                    "dots": true}
            },
            "types": {
                "#": {
                    "max_children": 1,
                    "max_depth": attr.depth,
                    "valid_children": [ "root" ]
                },
                "root": {

                    "icon": "fa fa-sitemap",
                    "valid_children": [ "default" ]
                },
                "pcat": {
                    "icon": "fa fa-cubes",
                },
                "scat": {
                    "icon": "fa fa-genderless"
                },
                "scat2": {
                    "icon": ""
                },
                "folder": {
                    "icon": "fa"
                },

                "default": {
                    "icon": "fa",
                    "valid_children": [ "default", "folder", "pcat", "scat" ]
                }
            },
            "search": {
                "show_only_matches": true,
                "show_only_matches_children": true,

            },

            "plugins": [
                "contextmenu", "changed", "search", "html_data", "ui",
                "state1", "types", "wholerow1", "sort1", "unique"
            ],

        });

//        HTMLTree.on("search.jstree", function (nodes, str, res) {
//            console.log(str.nodes.length);
//            if (str.nodes.length === 0) {
//                $('#html').jstree(true).hide_all();
//            }else{
//                $('#html').jstree(true).show_all();
//            }
//        }).on("clear_search.jstree", function (e, data) {
//            if (data.nodes.length) {
//                $(this).find(".jstree-node").css("display", "").filter('.jstree-last').filter(function () {
//                    return this.nextSibling;
//                }).removeClass('jstree-last');
//            }
//        });

        HTMLTree.on('hover_node.jstree', function (e, data) {
            $('#' + data.node.id).find('.btnGroup:first').show()
        });

        HTMLTree.on('dehover_node.jstree', function (e, data) {
            $(selector).find('.btnGroup').hide();
        });

        HTMLTree.on('select_node.jstree', function (e, data) {

            data.instance.toggle_node(data.node);

            //$(selector).find('.btnGroup').hide();
            //$('.jstree-clicked').closest('li').find('.btnGroup:first').show();
            //console.log($('.jstree-clicked').closest('li'));
            //data.instance.toggle_node(".jstree-open");

        });

        HTMLTree.on("after_close.jstree", function (e, data) {
            //data.instance.deselect_node(data.node);
            //$(selector).find('a').removeClass('jstree-clicked');
            //$(selector).jstree('deselect_all',true);
            //$('#'+data.node.id).parents('.jstree-node').find('.btnGroup').hide();
            //$('.jstree-clicked').closest('li').find('.btnGroup:first').hide();
        });
        HTMLTree.on("click", function (e, data) {

            if (!e.isTrigger) {
                HTMLTree.on("after_open.jstree after_close.jstree", function (e, data) {
                    var shownArray = [ ];

                    $('.jstree-open').each(function () {
                        if ($(this).data('parentId')) {
                            shownArray.push({
                                'parent_id': $(this).data('parentId')
                            });
                        }
                        else {
                            shownArray.push({
                                'id': $(this).data('id')
                            });
                        }
                    });

                    localStorage.setItem('jsTree', JSON.stringify(shownArray));

                });
            }
        });

        HTMLTree.on("before_open.jstree", function (e, data) {
            var nodesToKeepOpen = [ ];

            // get all parent nodes to keep open
            $('#' + data.node.id).parents('.jstree-node').each(function () {
                //data.instance.deselect_node(this.id);
                nodesToKeepOpen.push(this.id);
            });

            // add current node to keep open
            nodesToKeepOpen.push(data.node.id);

            // close all other nodes
            $('.jstree-open').each(function () {
                if (nodesToKeepOpen.indexOf(this.id) === -1) {
                    $(selector).jstree().deselect_node(this.id);
                    //data.instance.deselect_node(this.id);
                    $(selector).jstree().close_node(this.id);
                }
            });
        });
    },
    'ajaxConfig': function () {
        $.ajaxSetup({
            //timeout: 20000,
            retries: 2,
            retryInterval: 1000,
            cache: false,
        }
        );

        $(document).ajaxSuccess(function (event, xhr, settings) {
            // Swal.hideLoading();
        });

        $(document).ajaxError(function (event, xhr, error) {
            Swal.close();

            if (xhr.status === 400) {
                this.retries = 0;
                //alert('Логин сесията изтече! Ще бъдете пренасочени към login страницата.');
                window.location.replace('/');
            }

            if (xhr.status === 500) {
                Swal.close();
                alert('статус код = ' + xhr.status + '\nгрешка: ' + xhr.responseJSON.message);
                this.retries = 0;
            }

            //When XHR Status code is 0 there is no connection with the server
            if (xhr.status === 0) {
                //alert("Връзката със сървъра е изгубена! Проверете дали имате интернет връзка.");
            }
        }
        );
    },

    'axios': function () {
        axios.defaults.timeout = 120000;
        axios.interceptors.request.use(
                async config => {

                    config.headers['Authorization'] = `Bearer ${localStorage.getItem('access_token')}`;
                    return config;
                },
                err => {
                    return Promise.reject(err);
                });

        axios.interceptors.response.use((response) => {
            return response;
        }, (err) => {

            if (err.response) {
                const status = err.response.status || 500;

                switch (status) {
                    // authentication (token related issues)
                    case 401:
                    {
                        return Promise.reject(err);
                    }

                    // forbidden (permission related issues)
                    case 403:
                    {
                        return Promise.reject(err);
                    }

                    // bad request
                    case 400:
                    {
                        return Promise.reject(err);
                    }

                    // not found
                    case 404:
                    {
                        return Promise.reject(err);
                    }

                    // conflict
                    case 409:
                    {
                        return Promise.reject(err);
                    }

                    // unprocessable
                    case 422:
                    {
                        return Promise.reject(err);
                    }
                    case 500:
                    {
                        //Swal.close();
                        alert('грешка тип 500: ' + err.message);
                        return Promise.reject(err);
                    }

                    // generic api error (server related) unexpected
                    default:
                    {
                        alert(err.message);
                        return Promise.reject(err);
                    }
                }
            }
            return Promise.reject(err);
        });
    },

    // fixed elements при скрол
    'sticky': function ( ) {
        $(window).scroll(function () {
            var currentURL = window.location.href; // Full URL
            var section3 = $(".section3");
            var section2 = $(".section2");
            var blockModels = $("main #block-prodModel");
            
            // Изчисляваме общата височина на header-а (section2 + section3)
            var headerTotalHeight = section2.outerHeight() + section3.outerHeight();
            var li = $(".sticky.li");

            if (li.length > 0) {
                var liPos = li.offset().top + ( li.height() - headerTotalHeight );
            }

            var window_top = $(window).scrollTop();
            var footer_top = $("footer").offset().top;
            var menu_height = $(".vertical-menu").height();
            var menu_width = $(".vertical-menu").width();

            // li
            if (window_top > liPos) {
                li.addClass('position-fixed col-wd-9gdot5').css({'background': '#fff', 'z-index': '2', 'top': section2.height() + 'px', 'width': '100%'});
            }
            if (window_top <= headerTotalHeight) {
                li.removeClass('position-fixed col-wd-9gdot5').removeAttr('style');
            }

            // section2 sticky логика - активира се когато целият header се скрие
            if (window_top >= headerTotalHeight) {
                blockModels.addClass("fixed-top");
                section2.addClass("fixed-top");

                if (ISMOBILE) {
                    section2.addClass("col fixed-top");
                    blockModels.addClass('hide');
                }

                if (HEADERFULL) {
                    blockModels
                            .css({'border': '2px solid blue', 'background': '#fff', 'padding': '0 0 0 5px', 'z-index': '100', 'top': section2.height() + 'px'})
                            .find('.js-open-brandModel-modal').addClass('w-50');
                }

                section3.find('#js-miniCartCloned').removeClass('hide');
            }
            else {
                section2.removeClass("fixed-top");
                section3.find('#js-miniCartCloned').addClass('hide');
                section2.removeClass("col fixed-top");

                if (ISMOBILE) {
                    section2.removeClass("col fixed-top");
                    blockModels.removeClass('hide');
                }

                if (HEADERFULL) {
                    blockModels.removeClass("fixed-top").removeAttr('style');
                    blockModels.find('.js-open-brandModel-modal').removeClass('w-50');
                }
            }

            // vertical menu
            if (window_top + menu_height > footer_top - 250) {
                $('.sticky.card').removeClass('position-fixed').removeAttr('style');
            }
            else if (section2.hasClass('fixed-top')) {
                $('.sticky.card').addClass('position-fixed').css({'width': menu_width + 'px', 'top': ( HEADERFULL ? ( blockModels.height() || 0 ) + section2.height() : section2.height() ) + 'px', 'z-index': 11});
            }
            else {
                $('.sticky.card').removeClass('position-fixed').removeAttr('style');
            }
        });
    },

    'rangeSlider': function () {
        $.HSCore.components.HSRangeSlider.init('.js-filter-range-cenova', {
            onFinish: function (arg) {
                $('#js-cenova-ul').find('li').hide().filter(function () {
                    var price = parseFloat($(this).find('.js-price').text( ).trim( ));
                    return  price >= arg.from && price <= arg.to;
                }).show();
            }
        });
    },

    'lazy': function () {
        $('.lazy').Lazy({
            // your configuration goes here

            scrollDirection: 'vertical',
            effect: 'fadeIn',
            visibleOnly: true,
            beforeLoad: function (element) {
                console.log('beforeLoad ');
            },
            afterLoad: function (element) {
                console.log('afterLoad ');
            },
            onError: function (element) {
                console.log('error loading ' + element.data('src'));
            }
        });
    },

    'sweetAlert': async function (settings = {}, callback = ( ) => {} ) {
        const defaults = {
            'confirmButtonColor': '#3085d6',
            'confirmButtonText': 'Ok',
            'cancelButtonColor': '#d33',
            'cancelButtonText': 'Отказ',
            'allowOutsideClick': true,
            'showConfirmButton': true,
            'allowEscapeKey': true,
            'position': 'center',
        };

        var arg = $.extend(true, defaults, settings); //this will override defaults

        const result = await  Swal.fire({
            'toast': arg.toast,
            'width': arg.width,
            'position': arg.position,
            'titleText': arg.titleText,
            'title': arg.title,
            'text': arg.text,
            'icon': arg.icon,
            'iconColor': arg.iconColor,
            'customClass': arg.customClass,
            'html': arg.html,
            'showCloseButton': arg.showCloseButton,
            'showConfirmButton': arg.showConfirmButton,
            'timer': arg.timer,
            'timerProgressBar': arg.timerProgressBar,
            'showCancelButton': arg.showCancelButton,
            'confirmButtonColor': arg.confirmButtonColor,
            'confirmButtonText': arg.confirmButtonText,
            'cancelButtonColor': arg.cancelButtonColor,
            'cancelButtonText': arg.cancelButtonText,
            'allowOutsideClick': arg.allowOutsideClick,
            'allowEscapeKey': arg.allowEscapeKey,
            'didOpen': arg.didOpen,
            'didClose': arg.didClose
        });

        return callback(result.isConfirmed);
    },

    'coockie': function ( ) {
        // // Проверяваме дали потребителят вече е съгласен с използването на бисквитки
        if (Cookies.get('cookie-consent') === 'true') {
            // Ако е, скриваме попъпа
            $('#cookie-banner').hide();
        }
        else {
            // Ако не е, показваме попъпа
            $('#cookie-banner').show();

            // Инициализираме бутона за съгласие
            $('#cookie-accept').click(function () {
                // Задаваме съгласието на потребителя
                // Cookies.set('cookie-consent', 'true', { expires: 365 });

                // Скриваме попъпа
                $('#cookie-banner').hide();
            });

            // Инициализираме бутона за отказ
            $('#cookie-decline').click(function () {
                // Не задаваме съгласието на потребителя
                //          // Оставяме попъпа видим
            });
        }

        // Cookies
       
    },

    // === добавяне на поле за редакция ===========
    'addClearBtn': function () {
        $('input:not([type=checkbox]):not([type=file]):not([type=hidden]):not([type=radio]):not([role="combobox"]):not(.notClear),textarea,select').addClear();
    },

    'zoomImg': function ( ) {
        $('.zoomImg').ezPlus({
            responsive: true,
            cursor: 'crosshair',
            tint: true,
            tintColour: '#F90',
            tintOpacity: 0.5,
            zoomWindowPosition: 10,
            zoomWindowHeight: 400,
            zoomWindowWidth: 450
        });
    },

    // изкач  popup  за title
    'popupTitle': function (arg = 'click' ) {

        $('[data-toggle="tooltip"]').jTippy({
            title: '',
            //string ('click', 'hover', 'focus', 'hoverfocus')
            trigger: arg,
            //string ('auto','top','bottom','left','right')
            position: 'auto',
            //string ('black', 'lt-gray', 'white', 'blue', 'green', 'red')
            theme: 'black',
            //string ('tiny', 'small', 'medium', 'large')
            size: 'small',
            //string|false ('black', 'white', 'blurred'): Only works with trigger: "click"
            backdrop: 'black',
            allowHTML: true,
            content: 'Loading...',
            //boolean: if true and trigger: 'click', when clicking outside the tooltip, it will be hidden
            close_on_outside_click: true
        });
    },

    'toFixed': function (num) {
        let regexPattern = /^-?[0-9]+$/;

        // check if the passed number is integer or float
        let result = regexPattern.test(num);


        if (result) {
            // num = parseInt(num).toFixed(SETTINGS_NUM_FIXED);
            return parseInt(num).toFixed(2);
        }
        else {
            //num = parseFloat(num).toFixed(SETTINGS_NUM_FIXED);
            return parseFloat(num).toFixed(2);
        }
    },

    'calculateCartTotals': function (arg) {
        var total = 0, totalQty = 0;
        var ddsPercent = DDS; // 20%
        var deliveryPrice = Number($(arg.table).find('tfoot tr#deliveryRow #deliveryPrice').val()) || 0;

        $(arg.table).find('tbody tr').each(function () {
            var tr = $(this);
            var zena_prodava = tr.find('.js-zenaProdava');
            var qty = tr.find('.js-result').val();
            var sum = parseFloat(zena_prodava.text()) * Number(qty);
            var euro0 = '<br>(' + Lp.toFixed(sum * EURORATE) + ' лв.)';

            total += sum;
            totalQty += Number(qty);

            // изчисляване на надценка
            tr.find('td.js-total, .js-total').html(Lp.toFixed(sum) + ' ' + VALUTASIGN + ( EURORATE != 1 ? euro0 : '' )); // тотал
        });

        var totalWithoutDds = Lp.toFixed(total);
        var totalDds = Lp.toFixed(total * ddsPercent);
        var totalWithDds = Lp.toFixed(total + ( total * ddsPercent ) + deliveryPrice);
        var euro1 = '<br>(' + Lp.toFixed(totalWithoutDds * EURORATE) + ' лв.' + ')';
        var euro2 = '<br>(' + Lp.toFixed(totalDds * EURORATE) + ' лв.' + ')';
        var euro3 = '<br>(' + Lp.toFixed(totalWithDds * EURORATE) + ' лв.' + ')';

        $(arg.table).find('tfoot tr#js-total_bez_dds').find('td.suma, .suma').html(totalWithoutDds + ' ' + VALUTASIGN + ( EURORATE != 1 ? euro1 : '' ));
        $(arg.table).find('tfoot tr#js-dds').find('td.suma,.suma').html(totalDds + ' ' + VALUTASIGN + ( EURORATE != 1 ? euro2 : '' ));
        $(arg.table).find('tfoot tr#js-total_s_dds').find('td.suma, .suma').html(totalWithDds + ' ' + VALUTASIGN + ( EURORATE != 1 ? euro3 : '' ));

        return {'totalQty': totalQty, 'totalPrice': totalWithoutDds};
    },

    // филтър в thead таблицата
    'tableRow_filter': function (options = {} ) {
        const {modal = '', input, table} = options;

        $(`${modal} ${input}`).multifilter({
            'target': $(table)
        });
    },

    // === добавяне на поле за редакция ===========
    'loadDoomEdit': function (arg = {} ) {

        $(arg.el).doomEdit({
            autoTrigger: true,
            editForm: {
                method: 'post',
                class: 'd-inline',
                action: arg.route
            },
            editFieldName: arg.name ? arg.name : 'doomEditElement',
            beforeFormSubmit: function (data, form, el) {
                var msg = '';

                msg = arg.action == 'edit' ? 'Сигурни ли сте, че искате да промените -' + el[0].textContent : 'Сигурни ли сте, че искате да запишете нов запис?';

                if (!confirm(msg)) {
                    return false;
                }
            },
            afterFormSubmit: function (data, form, el) {
                arg.btnGroup.show();
                el.text($('input', form).val());
            },
            onCancel: function (el) {
                arg.btnGroup.show();
            }});
    },

    // времезакъснение
    'debounce': function (func, wait, immediate) {

        var timeout;
        return function () {
            var context = this, args = arguments;
            var later = function () {
                timeout = null;
                if (!immediate)
                    func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow)
                func.apply(context, args);
        };
    },
};

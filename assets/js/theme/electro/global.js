/* global PRODUCTS_JSON, imageDir, axios, Promise, loadPlugins */

( function ($, window, document, undefined) {

    Lp = loadPlugins;

    Lp.ajaxConfig();
    Lp.axios();
    Lp.coockie();
    Lp.lazy();
    Lp.sticky();// fixed elements при скрол

    $('input').attr('autocomplete', 'off');

    $(document).on('click', '.treeview .node-plus', function () {
        const _this = $(this);
        let li = _this.closest('li');

        _this.toggleClass('node-plus node-minus');
        li.find('ul.child').toggle();
    });

    $(document).on('click', '.treeview .node-minus', function () {
        const _this = $(this);
        let li = _this.closest('li');

        li.find('ul').hide();
        _this.toggleClass('node-minus node-plus');
    });

    var global = global || {};

    global = {
        'init': function ( ) {
            $root = this;
            self = this;

            this.autoLoad( );
            this.setUpListeners( );
        },
        // авто зареждане на функции
        'autoLoad': function ( ) {
            addBlurToMain();

            function addBlurToMain () {

                const $sidebar = $('.sidebar');
                const $elementsToBlur = $('.section2 #mobileIcons, main, footer');

                if ($sidebar.length && $elementsToBlur.length) {

                    function updateBlur () {
                        // Ако поне един от .sidebar елементите има клас 'fadeInLeft'
                        const anyActive = $sidebar.filter('.fadeInLeft').length > 0;

                        if (anyActive) {
                            $elementsToBlur.addClass('blurred');
                        }
                        else {
                            $elementsToBlur.removeClass('blurred');
                        }
                    }

                    $sidebar.each(function () {
                        const observer = new MutationObserver(updateBlur);
                        observer.observe(this, {attributes: true, attributeFilter: [ 'class' ]});
                    });
                }
            }
        },

        'setUpListeners': function ( ) {

            // EVENTS
            $(document)
                    // --- header
                    .on('keyup', '#js-searchproduct-item', Lp.debounce(this.event.header.searchProduct, 500))
                    .on('keyup', '#filter-cenovаByName', this.event.header.filter_cenovaListaMenu);


            //BTN
            $(document)
                    // --- in leftSideBar
                    .on('click', '#headerSidebarList [data-toggle="collapse"]', this.btn.leftSideBar.collapseSidebar)
                    // --- header
                    .on('click', '#product-search-form button', this.btn.header.searchProductButton);
        },

        'event': {

            'header': {

                'filter_cenovaListaMenu': function (e) {
                    var value = $(this).val().toLowerCase();

                    $('#js-cenovaListaMenu').find('li').filter(function () {
                        $(this).toggle($(this).text().trim().toLowerCase().indexOf(value) > -1);
                    });
                },

                'searchProduct': function (e) {
                    var searchName = $(this).val();

                    if (e.which === 13) { // Ако натиснатия клавиш е "Enter" (с код 13)
                        e.preventDefault(); // Предотвратете стандартното действие на клавиша Enter
                    }

                    if (searchName.length == 0) {
                        $('#search1-result').hide();
                        return false;
                    }

                    if (searchName.length < 2) {
                        return false;
                    }

                    $.ajax({
                        method: 'POST',
                        url: $(this).data('route'),
                        data: {'searchName': searchName.trim()},
                        dataType: 'json',
                        success: function (response) {
                            $('#search1-result').css('max-height', $(window).height() - $('header').height()).show();
                            $('#search1-result').scrollTop(0).html(response.view);
                        }
                    });

                    $(document).on('click', function (event) {
                        if (!$(event.target).closest('#search1-result').length) {
                            $('#search1-result').hide();
                        }
                    });
                }
            }
        },

        'btn': {
            'header': {

                'searchProductButton': function (e) {
                    // Вземете родителската форма на бутона
                    var form = $(this).closest("form");

                    // Вземете стойността на инпут полето в съответната форма
                    var searchQuery = form.find("input[name='searchName']").val().trim();

                    // Проверка за дължината на търсения низ
                    if (searchQuery.length < 3) {
                        alert('Минималната дължина на търсения продукт трябва да е 3 символа.');
                        return;
                    }

                    window.location.href = "/shop?searchName=" + searchQuery;
                }
            },

            'leftSideBar': {
                'collapseSidebar': function (e) {
                    e.preventDefault();

                    var target = $(this).data('target');

                    if ($(this).attr('aria-expanded') === "true") {
                        $(target).collapse('hide');
                    }
                    else {
                        $(target).collapse('show');
                    }
                }

            },

            'table': {

            }
        }
    };

    global.init( );

} )(jQuery, window, document);

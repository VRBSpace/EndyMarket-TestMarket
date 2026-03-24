( function ($, window, document, undefined) {
    var popup_relationFilter = popup_relationFilter || {};
    let v = popup_relationFilter;
    let self = popup_relationFilter;

    popup_relationFilter = {
        '__var': ( function () {
            const $modal = $('#modal_relationFilter');
            return {
                $modal: $modal,
                $modalTitle: $modal.find('.modal-title'),
                $stepContainer: $modal.find('.m-modal__body'),
                $backBtn: $modal.find('#js-returnBack'),
                $refreshHtml: $('#refreshHtml')
            };
        } )(),
        'init': function ( ) {
            v = this.__var;
            self = this;
            this.setUpListeners( );
        },

        'setUpListeners': function ( ) {
            $(document).off('htmx:afterSwap').on('htmx:afterSwap', function (e) {
                const nextStep = e.detail.target.getAttribute('data-next-step');
                const level = e.detail.target.getAttribute('data-cat-level');

                if (e.detail.target.id !== 'refreshHtml')
                    return;

                if (nextStep === 'model') {
                    $('#js-popupOpen-model:first').trigger('click');
                }

                if (nextStep === 'cat') {
                    $('.js-popupOpen-cat').eq(level).trigger('click');

                    const hasOptions = $('select[data-level]').eq(level).find('option:not(:first)').length > 0;

                    // ако няма категории затваряме модала
                    if (!hasOptions) {
                        v.$modal.hide();
                    }
                }
            });

            // POPUP
            $(document)
                    .on('click', '#js-popupOpen-brand', this.modal.open_popup_brand)
                    .on('click', '#js-popupOpen-model', this.modal.open_popup_model)
                    .on('click', '.js-popupOpen-cat', this.modal.open_popup_cat);

            v.$modal.off()
                    .on('click', '#js-returnBack', this.btn.returnBack)
                    .on('click', '.m-close', this.btn.closeModal)
                    .on('click', '.js-filter-item', this.events.selectItem);
        },

        'modal': {
            'open_popup_brand': function (e) {
                const select = $('select[name="f_brandId"]');
                self.utils.renderSelectAsList(select, 'brand', 'Изберете марка');
            },

            'open_popup_model': function () {
                const select = $('select[name="f_models"]');
                self.utils.renderSelectAsList(select, 'model', 'Изберете модел');
            },

            'open_popup_cat': function () {
                const level = Number($(this).data('level')) || 1;
                const $select = $('select[name="form[category_id][]"]').eq(level - 1);

                self.utils.renderSelectAsList($select, 'cat', `Изберете подкатегория ${level}`, level);
            }
        },
         'events': {
              'selectItem': function ( ) {
                    const $el = $(this);
                    const dataUrl = $el.data('url');
                    const next = $el.data('next');
                    const level = $el.data('level') || 0;

                    self.utils.updateUrl(dataUrl, next, level);
                }
         },

        'btn': {
            // връщане назад
            'returnBack': function (e) {
                const curentFilter = v.$modal.attr('data-opened-filter');
                const currentLevel = Number(v.$modal.attr('data-current-cat-level')) || 0;

                if (curentFilter === 'cat' && currentLevel > 1) {
                    // Връщаме към предишна подкатегория
                    $(`.js-popupOpen-cat[data-level="${currentLevel - 1}"]`).trigger('click');
                }
                else if (curentFilter === 'cat' && currentLevel <= 1) {
                    // Връщаме към model
                    $('#js-popupOpen-model').trigger('click');
                }
                else if (curentFilter === 'model') {
                    // Връщаме към brand
                    $('#js-popupOpen-brand').trigger('click');
                }
                else {
                    // Ако вече сме на brand → скриваме модала
                    v.$modal.hide();
                }
            },
            'closeModal': function (e) {
                v.$modal.hide();
            },
        },

        'utils': {

            'updateUrl': function (dataUrl, nextStep = '', level = 0 ) {
                const target = '#' + v.$refreshHtml.attr('id');
                const values = {isRefresh: true};

                // Задаваме data-атрибут само ако е зададен nextStep
                if (nextStep) {
                    v.$refreshHtml.attr('data-next-step', nextStep).attr('data-cat-level', level);
                }
                else {
                    v.$refreshHtml.removeAttr('data-next-step data-cat-level');
                }

                history.pushState(null, '', dataUrl);

                // AJAX зареждане само на  контейнер #refreshHtml
                htmx.ajax('GET', dataUrl, {
                    target: target,
                    swap: 'innerHTML',
                    select: `${target} > *`,
                    values: values
                });
            },

            // рендиране на списък с елементи взети от sеlect box на филтъра
            'renderSelectAsList': function ($select, kind, title, level) {
                // kind: 'brand' | 'model' | 'cat'
                const $opts = $select.find('option').not(':first');
                const rows = [ ];

                kind !== 'brand' ? v.$backBtn.removeClass('hide') : v.$backBtn.addClass('hide');
                // Задаваме data-атрибут за отворен филтър
                v.$modal.attr('data-opened-filter', kind);

                if ($opts.length === 0) {
                    v.$stepContainer.html('<div class="text-red p-2">Няма налични опции.</div>');
                    v.$modalTitle.text(title);
                    v.$modal.show();
                    return;
                }

                $opts.each(function () {
                    const $el = $(this);
                    const val = $el.val();
                    const img = $el.data('img') || '';
                    const txt = ( $el.text() || '' ).trim();
                    const dataUrl = $el.data('url');
                    const next = $el.data('next');
                    let dataAttr = '';

                    if (kind === 'cat') {
                        dataAttr = ` data-category-id="${val}" data-level="${level || ''}"`;
                        v.$modal.attr('data-current-cat-level', level);
                    }

                    rows.push(`
                            <li class="js-filter-item list-group-item d-flex align-items-center border-bottom px-2 cursor-pointer" data-url="${dataUrl}" data-next="${next}" ${dataAttr} >
                                
                                 <div class="flex-grow-1">${txt}</div>
                                
                                 <picture class="flex-shrink-0" style="width: 50px;">
                                    ${img ? `<img class="img-fluid lazy-img w-100" src="${img}" alt="" loading="lazy" decoding="async" style="object-fit: contain;">` : ''}
                                 </picture>
                            </li>
                        `);
                });

                v.$stepContainer.html(`<ul class="list-group">${rows.join('')}</ul>`);
                v.$modalTitle.text(title);
                v.$modal.show();
            }
        }
    };

    popup_relationFilter.init( );
} )(jQuery, window, document);
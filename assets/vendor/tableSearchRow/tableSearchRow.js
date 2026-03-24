;
( function ($) {
    $.fn.multifilter = function (options) {
        var settings = $.extend({
            'target': $('table'),
            'method': 'thead' // This can be thead or class
        }, options);

        jQuery.expr[":"].Contains = function (a, i, m) {
            return ( a.textContent || a.innerText || "" ).toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
        };

        this.each(function () {

            var $this = $(this);
            var container = $this.closest('.table');
            var row_tag = 'tbody tr';
            var item_tag = '';
            var rows = container.find($(row_tag));

            if (container.find($($this))[0].offsetParent) {
                if(container.find($($this))[0].id){
                    item_tag = '.' + container.find($($this))[0].id;
                } 
            }

            if (settings.method === 'thead') {
                // Match the data-col attribute to the text in the thead
                var col = container.find('th:Contains(' + $this.data('col') + ')');
                var col_index = container.find($('thead th:visible')).index(col);

            }
            ;

            if (settings.method === 'class') {
                // Match the data-col attribute to the class on each column
                var col = rows.first().find('td.' + $this.data('col') + '');
                var col_index = rows.first().find('td').index(col);
            }
            ;

            $this.change(function () {
                var filter = $this.val() || this.value;
                var colspan = container.find('thead tr:eq(0) th:visible').length;

                rows.each(function () {
                    var row = $(this);
                    var cell = row.children(item_tag);
                    var cellText = item_tag === '.date' ? cell.data('date').toLowerCase().trim() : cell.text().toLowerCase().trim();

                    if (filter) {
                        var isFiltered = cellText.indexOf(filter.toLowerCase().trim()) !== -1;
                        cell.attr('data-filtered', isFiltered ? 'positive' : 'negative');
                    }
                    else {
                        cell.attr('data-filtered', 'positive');
                    }
                });

                // Show/hide rows based on filtering results
                var filteredRows = rows.filter(function () {
                    return $(this).find(item_tag + "[data-filtered=negative]").length > 0;
                });

                filteredRows.hide();
                rows.not(filteredRows).show();

                var visibleRows = container.find('tbody tr:visible:not(.emptyRow)');
                if (visibleRows.length === 0 && container.find('tbody tr.emptyRow').length === 0) {
                    container.find('tbody').append('<tr class="emptyRow"><td class="text-red" colspan="' + colspan + '">Няма намерени резултати от търсенето</td></tr>');
                }
                else if (visibleRows.length > 0) {
                    container.find('tbody tr.emptyRow').remove();
                }

                // keyup в кполето филтър
                // =======================
                container.find('thead').on('keyup', 'input[type=search]', function (e)
                {

                    var visible = Boolean($(this).val( ));
                    // показване на х за нулиране
                    $(this).parent().find('span').toggleClass('hide', !visible);
                });

                container.find('thead').on('click', '.clearFilter', function ( ) {
                    $(this).toggleClass('hide');
                    $(this).parent().find('input[type=search]').val('').trigger('keyup');
                });
                return false;
            }).keyup(function () {
                $this.change();
            });
        });
    };

} )(jQuery);

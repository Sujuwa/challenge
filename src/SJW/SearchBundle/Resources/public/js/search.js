;
(function ($) {
    $(function () {
        $("#search-input").autocomplete({
            source: 'api/autocomplete',
            minLength: 2,

            select: function (event, ui) {
                doSearch(ui.item.id);
            },

            html: true,

            // optional (if other layers overlap autocomplete list)
            open: function (event, ui) {
                $(".ui-autocomplete").css("z-index", 1000);
            }
        });

        $('.js-do-search').click(function (event) {
            event.preventDefault();

            var search = $('#search-input').val();
            if (search.length < 3) {
                return;
            }

            doSearch(search);
        });

        function doSearch(search) {
            $.ajax({
                url: 'api/search',
                data: {
                    q: search
                },
                type: 'POST',
                success: function (data) {
                    // Remove previous ones
                    var first = true;
                    $('.results').find('.table').find('tr').each(function (i, val) {
                        if (first) {
                            first = false;
                            return;
                        }
                        $(this).remove();
                    });

                    // Showing of data.
                    var rowCount = 0;
                    var previousRow = $('.results .table').find('tr');
                    $.each(data.numbers, function (i, val) {
                        var tr_obj = $('<tr>').insertAfter($(previousRow));
                        if (val.length > 3) {
                            tr_obj.attr('class', 'city_row matching');
                        } else {
                            if (++rowCount % 2 == 0) {
                                tr_obj.attr('class', 'city_row odd');
                            } else {
                                tr_obj.attr('class', 'city_row even');
                            }
                        }
                        var cell;
                        cell = $('<td>').append(val[0]);
                        tr_obj.append(cell);
                        cell = $('<td>').append(val[1]);
                        tr_obj.append(cell);
                        cell = $('<td>').append(val[2]);
                        cell.attr('class', 'amount');
                        tr_obj.append(cell);

                        previousRow = tr_obj;
                    });
                }
            });
        }

    });
})(window.jQuery);
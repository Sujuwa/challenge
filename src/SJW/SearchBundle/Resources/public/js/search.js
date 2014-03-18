;(function($) {
    $(function() {
        $('.js-do-search').click(function(event) {
            event.preventDefault();

            var search = $('#search-input').val();

            $.ajax({
                url: 'app_dev.php/api/search?q=' + search,
                type: 'GET',
                success: function(data) {
                    var table = document.getElementById("search_table");
                    document.getElementById("noResultsLabel").style.visibility = "hidden";

                    for(var i = table.rows.length - 1; i > 0; i--) {
                        table.deleteRow(i);
                    }

                    if (data.length == 0) {
                        document.getElementById("noResultsLabel").style.visibility = "visible";
                    }

                    var bcg_color = "";
                    var bold_flg = "";

                    $.each(data, function(i, item) {
                        if (i%2 == 0)
                            bcg_color = "Lavender";
                        else
                            bcg_color = "AliceBlue";

                        $.each(item, function(j, item2) {
                            if (item2[3] == 1)
                                bold_flg = " font-weight: bold;";
                            else
                                bold_flg = "";

                            var $tr = $('<tr style="background: '+bcg_color+';'+bold_flg+'">').append(
                            $('<td>').text(item2[0]),
                            $('<td>').text(item2[1]),
                            $('<td>').text(item2[2])
                            ).appendTo('#search_table');
                        });
                    });
                }
            });
        });
    });
})(window.jQuery);

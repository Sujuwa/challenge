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
                    document.getElementById("endTimeLabel").style.visibility = "hidden";

                    for(var i = table.rows.length - 1; i > 0; i--) {
                        table.deleteRow(i);
                    }

                    if (data.length == 0) {
                        document.getElementById("endTimeLabel").style.visibility = "visible";
                    }

                    $.each(data, function(i, item) {
                        if (i%2 == 0) {
                            $.each(item, function(j, item2) {
                                if (item2[3] == 1) {
                                        var $tr = $('<tr style="background: Lavender; font-weight: bold;">').append(
                                        $('<td>').text(item2[0]),
                                        $('<td>').text(item2[1]),
                                        $('<td>').text(item2[2])
                                        ).appendTo('#search_table');
                                } else {
                                        var $tr = $('<tr style="background: Lavender;">').append(
                                        $('<td>').text(item2[0]),
                                        $('<td>').text(item2[1]),
                                        $('<td>').text(item2[2])
                                        ).appendTo('#search_table');
                                }
                            });
                        } else {
                            $.each(item, function(j, item2) {
                                if (item2[3] == 1) {
                                        var $tr = $('<tr style="background: AliceBlue; font-weight: bold;">').append(
                                        $('<td>').text(item2[0]),
                                        $('<td>').text(item2[1]),
                                        $('<td>').text(item2[2])
                                        ).appendTo('#search_table');
                                } else {
                                        var $tr = $('<tr style="background: AliceBlue;">').append(
                                        $('<td>').text(item2[0]),
                                        $('<td>').text(item2[1]),
                                        $('<td>').text(item2[2])
                                        ).appendTo('#search_table');
                                }
                            });
                        }
                    });
                }
            });
        });
    });
})(window.jQuery);

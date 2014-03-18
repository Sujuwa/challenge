;(function($) {
    $(function() {
        $('.js-do-search').click(function(event) {
            event.preventDefault();

            var search = $('#search-input').val();

            $.ajax({
                url: 'app_dev.php/api/search?q=' + search,
                type: 'GET',
                success: function(data) {
                    // TODO: implement showing of data.
                    console.log(data);

                    $.each(data, function(i, item) {
                            $.each(item, function(j, item2) {
                                var $tr = $('<tr>').append(
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

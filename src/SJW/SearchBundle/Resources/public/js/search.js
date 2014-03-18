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
                    //console.log(data);

                    //response = $.parseJSON(data);
                    $.each(data, function(i, item) {
                        var $tr = $('<tr>').append(
                        $('<td>').text(item[0]),
                        $('<td>').text(item[1]),
                        $('<td>').text(item[2])
                        ).appendTo('#records_table');
                    });


                }
            });
        });
    });
})(window.jQuery);

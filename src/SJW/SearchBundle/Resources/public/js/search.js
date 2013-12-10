;(function($) {
    $(function() {
        $('.js-do-search').click(function(event) {
            event.preventDefault();

            var search = $('#search-input').val();

            $.ajax({
                url: 'api/search?q=' + search,
                type: 'GET',
                success: function(data) {
                    // TODO: implement showing of data.
                    console.log(data);
                    var cities = "";
                    $("p.no-results").remove();
                    if(data.length==0){
                        $(".main-container").append("<p class='no-results'>No results.</p>");
                    }else{
                        $.each(data, function( index, value ) {
                            if(value.correct=="true"){
                                cities += "<tr class='correct'><td>"+value.zip+"</td><td>"+value.name+"</td><td>"+value.population+"</td></tr>";
                            }else{
                                cities += "<tr><td>"+value.zip+"</td><td>"+value.name+"</td><td>"+value.population+"</td></tr>";
                            }
                        });
                    }

                    $(".table tbody").html(cities);

                }
            });
        });

        if((".flash-message").length>0){
            setTimeout(function() {
                $(".flash-message").fadeOut();
            }, 2500);
        }
    });
})(window.jQuery);
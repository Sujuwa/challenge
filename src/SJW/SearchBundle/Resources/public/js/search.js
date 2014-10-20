;(function($) {
    
    var req = false;
    
    $(function() {
        $('#search-form').submit(function(event) {
            event.preventDefault();

            var search = $('#search-input').val();

            $.ajax({
                url: 'api/search?q=' + search,
                type: 'GET',
                success: function(data) {
                    // TODO: implement showing of data.
                    $("#table-body").html(data);
                }
            });
        });
        
        $("#search-input").keyup(function(e){
            var fieldVal = $.trim($(e.target).val());
            if(req) req.abort();
            $(e.target).autocomplete({
                source: function(request, response){
                    req = $.ajax({
                        url: 'api/autocomplete?q=' + fieldVal,
                        type: 'GET',
                        success: function(data){
                            response( $.map( data, function( item ) {
                                return {
                                    label: item[1] + ' ' + item[0],
                                    value: item[1] + ' ' + item[0],
                                    zip: item[0],
                                    city: item[1],                                    
                                    population: item[2]
                                }
                            }));
                        }
                        
                    })
                },
                select: function(e, item){
                    
                    var cityEntry = {};
                    cityEntry.city = item.item.city;
                    cityEntry.zip = item.item.zip;
                    cityEntry.population = item.item.population;
                    cityEntry.numberOfCities = $("#number-of-cities").val();
                    
                    $.ajax({
                        url: 'api/results',
                        type: 'POST',                        
                        data: cityEntry,
                        success: function(data){
                            $("#table-body").html(data);
                        }
                    })
                }
            });
        });
    });
})(window.jQuery);
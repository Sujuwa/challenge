;(function($) {
    $(function() {
		$("#set-settings").click(function(event){
			event.preventDefault();
			
			$.ajax({
				url: document.URL + 'settings/settingsSet?q=' + $('#how-many-towns').val(),
				type: 'GET',
				success: function(data){//console.log(data);
				}
			});
		});
		
		var availableTags = new Array();//create global so that it can be used in jqueryui
		
		$.ajax({
                url: document.URL + '/api/autocomplete?q=' + $('#search-input').val(),
                type: 'GET',
                success: function(data) {
                    //console.log(data);
					data.forEach(function(d){
						availableTags.push(d[0] + ", " + d[1] + ", " + d[2]);
					});
                }
            });
		
		$( "#search-input" ).autocomplete({//create autocomplete to search input with a source
			source: availableTags
		});
		
        $('.js-do-search').click(function(event) {
            event.preventDefault();

            var search = $('#search-input').val();
			$('tbody').empty();//empty for new searches
            $.ajax({
                url: document.URL +  '/api/search?q=' + search,
                type: 'GET',
                success: function(data) {
                    // TODO: implement showing of data.
					data.forEach(function(d){
					if(d[3])//If the array has fourth element, it is the "chosen one"
						$("tbody").append("<tr><td><span style=\"font-weight:bold;\">" + d[0] + "</span></td>" +"<td><span style=\"font-weight:bold;\">" + d[1] + "</span></td>" +"<td><span style=\"font-weight:bold;\">" + d[2] + "</span></td></tr>");
					else//append normally
						$("tbody").append("<tr><td>" + d[0] + "</td>" +"<td>" + d[1] + "</td>" +"<td>" + d[2] + "</td></tr>");
					});
                }
            });
        });
    });
})(window.jQuery);

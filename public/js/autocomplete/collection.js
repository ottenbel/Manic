//Include autocomplete for parent collection
$(document).ready(function(){
	$( "#parent_id" ).autocomplete({
      source: function( request, response ) 
	  {
		  $.ajax({
				url: "/api/v1/collection/namesearch",
				type: "POST",
				data: {searchString: request.term },
				success: function (data)
				{
					console.log(data);
					response(data);
				},
				error: function()
				{
					console.log("Error retrieving collection list from API.");
				}
		});
	  },
      minLength: 2,
      focus: function() {
          // prevent value inserted on focus
          return false;
		}
    });
});


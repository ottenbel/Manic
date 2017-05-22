//Include autocomplete for primary character
$(document).ready(function(){
	if(($("#character_primary").length) && ($("#series_primary").length))
	{
		$( function() {
		function split( val ) {
		  return val.split( /,\s*/ );
		}
		function extractLast( term ) {
		  return split( term ).pop();
		}
	 
		$( "#character_primary" )
		  // don't navigate away from the field on tab when selecting an item
		  .on( "keydown", function( event ) {
			if ( event.keyCode === $.ui.keyCode.TAB &&
				$( this ).autocomplete( "instance" ).menu.active ) {
			  event.preventDefault();
			}
		  })
		  .autocomplete({
			source: function( request, response ) {
				$.ajax({
					url: "/api/v1/character/namesearch",
					type: "POST",
					data: {searchString: extractLast( request.term ), seriesString: $("#series_primary").val() + ' , ' + $("#series_secondary").val()},
					success: function (data)
					{
						console.log(data);
						response(data);
					},
					error: function()
					{
						console.log("Error retrieving character list from API.");
					}
				});
			},
			search: function() {
			  // custom minLength
			  var term = extractLast( this.value );
			  if ( term.length < 2 ) {
				return false;
			  }
			},
			focus: function() {
			  // prevent value inserted on focus
			  return false;
			},
			select: function( event, ui ) {
			  var terms = split( this.value );
			  // remove the current input
			  terms.pop();
			  // add the selected item
			  terms.push( ui.item.value );
			  // add placeholder to get the comma-and-space at the end
			  terms.push( "" );
			  this.value = terms.join( ", " );
			  return false;
			}
		  });
	  } );
	}	
});

//Include autocomplete for secondary character
$(document).ready(function(){
	if (($("#character_secondary").length) && ($("#series_secondary").length))
	{
		$( function() {
		function split( val ) {
		  return val.split( /,\s*/ );
		}
		function extractLast( term ) {
		  return split( term ).pop();
		}
	 
		$( "#character_secondary" )
		  // don't navigate away from the field on tab when selecting an item
		  .on( "keydown", function( event ) {
			if ( event.keyCode === $.ui.keyCode.TAB &&
				$( this ).autocomplete( "instance" ).menu.active ) {
			  event.preventDefault();
			}
		  })
		  .autocomplete({
			source: function( request, response ) {
				$.ajax({
					url: "/api/v1/character/namesearch",
					type: "POST",
					data: {searchString: extractLast( request.term ), seriesString: $("#series_primary").val() + ' , ' + $("#series_secondary").val()},
					success: function (data)
					{
						console.log(data);
						response(data);
					},
					error: function()
					{
						console.log("Error retrieving character list from API.");
					}
				});
			},
			search: function() {
			  // custom minLength
			  var term = extractLast( this.value );
			  if ( term.length < 2 ) {
				return false;
			  }
			},
			focus: function() {
			  // prevent value inserted on focus
			  return false;
			},
			select: function( event, ui ) {
			  var terms = split( this.value );
			  // remove the current input
			  terms.pop();
			  // add the selected item
			  terms.push( ui.item.value );
			  // add placeholder to get the comma-and-space at the end
			  terms.push( "" );
			  this.value = terms.join( ", " );
			  return false;
			}
		  });
	  } );
	}
});

//Include autocomplete for secondary character
$(document).ready(function(){
	if (($("#character_child").length) && ($("#parent_series").length))
	{
		$( function() {
		function split( val ) {
		  return val.split( /,\s*/ );
		}
		function extractLast( term ) {
		  return split( term ).pop();
		}
	 
		$( "#character_child" )
		  // don't navigate away from the field on tab when selecting an item
		  .on( "keydown", function( event ) {
			if ( event.keyCode === $.ui.keyCode.TAB &&
				$( this ).autocomplete( "instance" ).menu.active ) {
			  event.preventDefault();
			}
		  })
		  .autocomplete({
			source: function( request, response ) {
				$.ajax({
					url: "/api/v1/character/namesearch",
					type: "POST",
					data: {searchString: extractLast( request.term ), seriesString: $("#parent_series").val()},
					success: function (data)
					{
						console.log(data);
						response(data);
					},
					error: function()
					{
						console.log("Error retrieving character list from API.");
					}
				});
			},
			search: function() {
			  // custom minLength
			  var term = extractLast( this.value );
			  if ( term.length < 2 ) {
				return false;
			  }
			},
			focus: function() {
			  // prevent value inserted on focus
			  return false;
			},
			select: function( event, ui ) {
			  var terms = split( this.value );
			  // remove the current input
			  terms.pop();
			  // add the selected item
			  terms.push( ui.item.value );
			  // add placeholder to get the comma-and-space at the end
			  terms.push( "" );
			  this.value = terms.join( ", " );
			  return false;
			}
		  });
	  } );
	}
});
$(window).on('load', function() {
		ShowSelectedVolume();
		
		$('#volume_id').on('change',function()
		{
			ShowSelectedVolume();
		});
	});
	
	function ShowSelectedVolume()
	{
		//Hide all volumes
		for (var i = 0; i < volumes.length; i++)
		{
			var volume = volumes[i];	
			var currentButtonID = "#" + volume + "_button";
			var currentPanelID = "#" + volume + "_panel";
			
			//Collapse the accordion panel
			$(currentPanelID).attr('style','');
			
			//Change the accordion state to disabled
			$(currentButtonID).attr("class", "closedAccordion");
			
			$(currentButtonID).hide();
		}
		
		//Show selected volume
		var selectedVolume = $('#volume_id').val();
		var selectedButtonID = "#" + selectedVolume + "_button";
		$(selectedButtonID).show();
	}
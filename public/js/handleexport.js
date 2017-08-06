function ConfirmExport(param, event)
{
	if(!($(param).is('[disabled=disabled]')))
	{
		if(confirm("Are you sure you want to download?") == true)
		{
			$(param).attr("disabled", true);
			$(param).text('Processing . . .');
		}
		else
		{
			event.preventDefault();
		}
	}
	else
	{
		event.preventDefault();
	}
}
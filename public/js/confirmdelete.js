function ConfirmDelete()
{
	if(confirm("Are you sure you want to delete?") == true)
	{
		//Allow the event to submit as normal
	}
	else
	{
		event.preventDefault();
	}
}
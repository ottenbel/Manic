$(document).ready(function(){
	var acc = document.getElementsByClassName("closedAccordion");
	var i;

	for (i = 0; i < acc.length; i++) 
	{
		acc[i].onclick = function() 
		{
			this.classList.toggle("active");
			var panel = this.nextElementSibling;
			
			if (panel.style.maxHeight)
			{
				panel.style.maxHeight = null;
			} 
			else 
			{
			  panel.style.maxHeight = panel.scrollHeight + 'px';
			} 
		}
	}
	
	var acc = document.getElementsByClassName("openAccordion");
	var i;

	for (i = 0; i < acc.length; i++) {
	  var panel = acc[i].nextElementSibling;
	  panel.style.maxHeight = panel.scrollHeight + "px";
	  acc[i].onclick = function() {
		this.classList.toggle("active");
		var panel = this.nextElementSibling;
		if (panel.style.maxHeight) {
		  panel.style.maxHeight = null;
		} else {
		  panel.style.maxHeight = panel.scrollHeight + "px";
		}
	  }
	}
});
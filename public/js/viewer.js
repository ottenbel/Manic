function next_page()
{	
	var current_page_number = page_number;
	page_number = +page_number + 1;	
	if (page_number < pages.length)
	{	
		var image_url = "//" + window.location.hostname + ":" + window.location.port + "/" + pages[page_number];
		var updated_url = "/chapter/" + chapter_id + "/" + page_number;
		var number_of_pages = pages.length - 1;
		$("#viewer_current_page").attr("src", image_url);
		history.replaceState(null, '', updated_url);
		$('html, body').animate({scrollTop:$('#page_viewer_container').position().top}, 'fast');
		$('#page_count').html(page_number + " / " + number_of_pages);
		
		$('#jump_selected_page').val(page_number);
		
		var previous_page_url = "/chapter/" + chapter_id + "/" + current_page_number;
		$('#previous_page_link').attr("href", previous_page_url);
		$('#previous_page_link_container').show();
		
		if ((page_number + 1) >= pages.length)
		{
			$('#next_page_link_container').hide();
		}
		
		if((page_number + 1) <= pages.length)
		{
			var next_page_number = page_number + 1;
			var next_page_url = "/chapter/" + chapter_id + "/" + next_page_number;
			$('#next_page_link').attr("href", next_page_url);
		}
		else if(next_chapter_id != "")
		{
			var next_chapter_url = "/chapter/" + next_chapter_id + "/0";
			$('#next_page_link').attr("href", next_chapter_url);
		}
		else
		{
			$('#next_chapter_link_container').hide();
		}
	}
	else if (next_chapter_id != "")
	{
		var next_chapter_url = "/chapter/" + next_chapter_id + "/0";
		window.location.href = next_chapter_url;
	}
	else
	{
		var collection_url = "/collection/" + collection_id;
		window.location.href = collection_url;
	}
}

function previous_page()
{
	var current_page_number = page_number;
	page_number = +page_number - 1;
	if (page_number >= 0)
	{
		var image_url = "//" + window.location.hostname + ":" + window.location.port + "/" + pages[page_number];
		var updated_url = "/chapter/" + chapter_id + "/" + page_number;
		var number_of_pages = pages.length - 1;
		$("#viewer_current_page").attr("src", image_url); 
		history.replaceState(null, '', updated_url);
		$('html, body').animate({scrollTop:$('#page_viewer_container').position().top}, 'fast');
		$('#page_count').html(page_number + " / " + number_of_pages);
		
		$('#jump_selected_page').val(page_number);
		
		var next_page_url = "/chapter/" + chapter_id + "/" + current_page_number;
		$('#next_page_link').attr("href", next_page_url);
		$('#next_page_link_container').show();
		
		if((page_number - 1) > 0)
		{
			var previous_page_number = page_number - 1;
			var previous_page_url = "/chapter/" + chapter_id + "/" + previous_page_number;
			$('#previous_page_link').attr("href", previous_page_url);	
		}
		else if (previous_chapter_id != "")
		{
			var previous_chapter_url = "/chapter/" + previous_chapter_id + "/" + last_page_of_previous_chapter;
			$('#previous_page_link').attr("href", previous_chapter_url);
		}
		else
		{
			$('#previous_chapter_link_container').hide();
			
			if ((page_number - 1) < 0)
			{
				$('#previous_page_link_container').hide();
			}			
		}
	}
	else if (previous_chapter_id != "")
	{
		var previous_chapter_url = "/chapter/" + previous_chapter_id + "/" + last_page_of_previous_chapter;
		window.location.href = previous_chapter_url;
	}
	else
	{
		var collection_url = "/collection/" + collection_id;
		window.location.href = collection_url;
	}
}

function jump()
{
	var number_of_pages = pages.length - 1;
	page_number = $('#jump_selected_page').val();
	var jump_url = "/chapter/" + chapter_id + "/" + page_number;
	var image_url = "//" + window.location.hostname + ":" + window.location.port + "/" + pages[page_number];
	$("#viewer_current_page").attr("src", image_url);
	history.replaceState(null, '', jump_url);
	$('#page_count').html(page_number + " / " + number_of_pages);
	
	if (page_number == 0)
	{
		$('#previous_page_link').attr("href", "#");
		$('#previous_page_link_container').hide();
	}
	else if ((page_number + 1) == pages.length)
	{
		$('#next_page_link').attr("href", "#");
		$('#next_page_link_container').hide();
	}
	else
	{
		var previous_page_number = page_number - 1;
		var next_page_number = page_number + 1;
		
		var previous_page_url = "/chapter/" + chapter_id + "/" + previous_page_number;
		var next_page_url = "/chapter/" + chapter_id + "/" + next_page_number;
		
		
		$('#previous_page_link').attr("href", previous_page_url);
		$('#previous_page_link_container').show();
		
		$('#next_page_link').attr("href", next_page_url);
		$('#next_page_link_container').show();
	}
}

function preload(sources)
{
	jQuery.each(sources, function(i,source) 
	{
		var path = "//" + window.location.hostname + ":" + window.location.port + "/" + source 
		jQuery.get(path); 
	});
}

document.onkeydown = function(key_press)
{
	//Catch user pressing the B key
	if (+key_press.keyCode == 66)
	{
		previous_page();                 
	}                 
	
	//Catch user pressing N key                 
	if (+key_press.keyCode == 78)
	{
		next_page();
	}
}

window.onload = function () {
	$('#previous_page_link').click(function(){
		previous_page(); return false; 
	});
	
	$('#next_page_link').click(function(){
		next_page(); return false; 
	});
	
	$('#jump_button').click(function (){
		jump();
	});
}
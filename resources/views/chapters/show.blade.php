@extends('layouts.app')

@section('title')
	{{$collection->name}} - Chapter {{$chapter->number}} {{$chapter->name}}
@endsection

@section('head')
<script>
	var pages = {!!$pages_array!!};
	var page_number = {!!$page_number!!};
	var next_chapter_id = "{{$next_chapter_id}}";
	var previous_chapter_id = "{{$previous_chapter_id}}";
	var last_page_of_previous_chapter = "{{$last_page_of_previous_chapter}}";
	var chapter_id = "{{$chapter->id}}"
	var collection_id = "{{$collection->id}}";
	
	function next_page()
	{	
		page_number = page_number + 1;	
		if (page_number < pages.length)
		{	
			var image_url = "//" + window.location.hostname + ":" + window.location.port + "/" + pages[page_number];
			var updated_url = "/chapter/" + chapter_id + "/" + page_number;
			$("#viewer_current_page").attr("src", image_url);
			history.replaceState(null, '', updated_url);
			$('#page_count').innerHTML(page_number + " / " + pages.length);
			if((page_number + 1) < pages.length)
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
		page_number = page_number - 1;
		if (page_number >= 0)
		{
			var image_url = "//" + window.location.hostname + ":" + window.location.port + "/" + pages[page_number];
			var updated_url = "/chapter/" + chapter_id + "/" + page_number;
			$("#viewer_current_page").attr("src", image_url); 
			history.replaceState(null, '', updated_url);
			$('#page_count').innerHTML(page_number + " / " + pages.length);
			
			if((page_number - 1) > 0)
			{
				var next_page_number = page_number - 1;
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
</script>
@endsection

@section('content')
<div class="container">
	<div>
		@if($chapter->name)
			<div class="col-md-4">
				<b><a href="">{{{$collection->name}}}</a></b> - Ch {{$chapter->number}} - {{{$chapter->name}}}
			</div>
		@else
			<div class="col-md-4">
				<b><a href="">{{{$collection->name}}}</a></b> - Ch {{$chapter->number}}
			</div>
		@endif
		
		@if((count($chapter->primary_scanalators)) || (count($chapter->secondary_scanalators)))
			<div class="scanalator_holder col-md-5">			
			@foreach($chapter->primary_scanalators()->withCount('chapters')->orderBy('chapters_count', 'desc')->orderBy('name', 'asc')->get() as $scanalator)
					<span class="primary_scanalators"><a href="/scanalator/{{$scanalator->id}}">{{{$scanalator->name}}} <span class="scanalator_count"> ({{$scanalator->usage_count()}})</span></a></span>
				@endforeach
				
				@foreach($chapter->secondary_scanalators()->withCount('chapters')->orderBy('chapters_count', 'desc')->orderBy('name', 'asc')->get() as $scanalator)
					<span class="secondary_scanalators"><a href="/scanalator/{{$scanalator->id}}">{{{$scanalator->name}}} <span class="scanalator_count">({{$scanalator->usage_count()}})</span></a></span>
				@endforeach
			</div>
		@endif
		
		@if($chapter->source != null)
			<div class="col-md-1">
				<a href="{{$chapter->source}}" class="btn-link">Source</button>
			</div>
		@endif
	</div>
</div>
<br/>

@if (count($chapter->pages) > 0)
	<div class="container" align="center">
		@if(($page_number >= count($chapter->pages)) && ($page_number > 0))
			<img id="viewer_current_page" src="{{asset($chapter->pages[count($chapter->pages)-1]->name)}}" class="img-responsive" alt="Page {{$chapter->pages[count($chapter->pages)-1]->number}}">
		@elseif($page_number <= 0)
			<img id="viewer_current_page" src="{{asset($chapter->pages[0]->name)}}" class="img-responsive" alt="Page {{$chapter->pages[0]->number}}">
		@else
			<img id="viewer_current_page" src="{{asset($chapter->pages[$page_number]->name)}}" class="img-responsive" alt="Page {{$chapter->pages[$page_number]->number}}">
		@endif
	</div>
	<br/>

	<div id="page_count" class="container" align="center">
		{{$page_number}} / {{count($chapter->pages)}}
	</div>
	<br/>
	
	<div class="container" align="center">
		<ul class="pagination">
			@if(count($chapter->previous_chapter()->first()))
				<li id="previous_chapter_link_container">
					<a id="previous_chapter_link" href="/chapter/{{$chapter->previous_chapter()->first()->id}}"><i class="fa fa-chevron-left" aria-hidden="true"></i><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
				</li>
			@else
				<li id="previous_chapter_link_container">
					<a id="previous_chapter_link" href="/collection/{{$collection->id}}"><i class="fa fa-chevron-left" aria-hidden="true"></i><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
				</li>
			@endif
			  
			@if($page_number > 0)
				<li id="previous_page_link_container">
					<a id="previous_page_link" href="/chapter/{{$chapter->id}}/{{$page_number - 1}}"><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
				</li>
			@elseif(count($chapter->previous_chapter()->first()))
				<li id="previous_page_link_container">
					<a id="previous_page_link" href="/chapter/{{$chapter->previous_chapter()->first()->id}}/{{count($chapter->previous_chapter()->first()->pages) -1}}"><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
				</li>
			@endif
			
			@if(count($chapter->pages()) > $page_number)
				<li id="next_page_link_container">
					<a id="next_page_link" href="/chapter/{{$chapter->id}}/{{$page_number + 1}}"><i class="fa fa-chevron-right" aria-hidden="true"></i></a>
				</li>
			@elseif(count($chapter->next_chapter()->first()))
				<li id="next_page_link_container">
					<a id="next_page_link" href="/chapter/{{$chapter->previous_chapter()->first()->id}}/0"><i class="fa fa-chevron-right" aria-hidden="true"></i></a>
				</li>
			@endif
			
			@if(count($chapter->next_chapter()->first()))
				<li id="next_chapter_link_container">
					<a id="next_chapter_link" href="/chapter/{{$chapter->previous_chapter()->first()->id}}/0"><i class="fa fa-chevron-right" aria-hidden="true"></i><i class="fa fa-chevron-right" aria-hidden="true"></i></a>
				</li>
			@else
				<li id="next_chapter_link_container">
					<a id="next_chapter_link" href="/collection/{{$collection->id}}"><i class="fa fa-chevron-right" aria-hidden="true"></i><i class="fa fa-chevron-right" aria-hidden="true"></i></a>
				</li>
			@endif
			
			</ul>
	</div>
@else
	<div class="alert alert-warning">
		This chapter has no associated pages.  
		@if(Auth::user())
			You can add pages to the chapter <a href="/chapter/{{$chapter->id}}/edit" class="alert-link">here</a>.
		@endif
		<br>
		@if(count($chapter->previous_chapter()->first()))
			<a href="/chapter/{{$chapter->previous_chapter()->first()->id}}/0">Previous Chapter</a> 
		@endif
		
		@if(count($chapter->next_chapter()->first()))
			<a href="/chapter/{{$chapter->next_chapter->first()->id}}/0">Next Chapter</a> 
		@endif
	</div>
@endif

@endsection

@section('footer')

@endsection
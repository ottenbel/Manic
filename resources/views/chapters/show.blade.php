@extends('layouts.app')

@section('title')
	{{$collection->name}} - Chapter {{$chapter->chapter_number}} {{$chapter->name}}
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
</script>
<script src="/js/viewer.js"></script>
@endsection

@section('content')
<div class="container">
	<div>
		@if($chapter->name)
			<div class="col-md-5">
				<b><a href="{{route('show_collection', ['collection' => $collection])}}">{{{$collection->name}}}</a></b> - Ch {{$chapter->chapter_number}} - {{{$chapter->name}}}
			</div>
		@else
			<div class="col-md-5">
				<b><a href="{{route('show_collection', ['collection' => $collection])}}">{{{$collection->name}}}</a></b> - Ch {{$chapter->chapter_number}}
			</div>
		@endif
		
		@if((count($chapter->primary_scanalators)) || (count($chapter->secondary_scanalators)))
			<div class="scanalator_holder col-md-4">			
			@foreach($chapter->primary_scanalators()->withCount('chapters')->orderBy('chapters_count', 'desc')->orderBy('name', 'asc')->get() as $scanalator)
					<span class="primary_scanalators"><a href="{{route('show_scanalator', ['scanalator' => $scanalator])}}">{{{$scanalator->name}}} <span class="scanalator_count"> ({{$scanalator->usage_count()}})</span></a></span>
				@endforeach
				
				@foreach($chapter->secondary_scanalators()->withCount('chapters')->orderBy('chapters_count', 'desc')->orderBy('name', 'asc')->get() as $scanalator)
					<span class="secondary_scanalators"><a href="{{route('show_scanalator', ['scanalator' => $scanalator])}}">{{{$scanalator->name}}} <span class="scanalator_count">({{$scanalator->usage_count()}})</span></a></span>
				@endforeach
			</div>
		@endif
		
		@if($chapter->source != null)
			<div class="col-md-1">
				<span class="source_tag"><a href="{{$chapter->source}}">Source</a></source>
			</div>
		@endif
		
		<div class="col-md-2">
			{{Form::selectRange('jump_selected_page', 0, count($chapter->pages) - 1, $page_number, ['id' => 'jump_selected_page'])}}
			{{ Form::submit('Jump', array('id' => 'jump_button')) }}
		</div>
	</div>
</div>
<br/>

@if (count($chapter->pages) > 0)
	<div class="container" align="center" id='page_viewer_container'>
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
		{{$page_number}} / {{count($chapter->pages) - 1}}
	</div>
	<br/>
	
	<div class="container" align="center">
		<ul class="pagination">
			@if(count($chapter->previous_chapter()->first()))
				<li id="previous_chapter_link_container">
					<a id="previous_chapter_link" href="{{route('show_chapter', ['chapter' => $chapter->previous_chapter()->first()])}}"><i class="fa fa-chevron-left" aria-hidden="true"></i><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
				</li>
			@endif
			  
			@if($page_number > 0)
				<li id="previous_page_link_container">
					<a id="previous_page_link" href="{{route('show_chapter', ['chapter' => $chapter, 'page' => ($page_number - 1)])}}"><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
				</li>
			@elseif(count($chapter->previous_chapter()->first()))
				<li id="previous_page_link_container">
					<a id="previous_page_link" href="{{route('show_chapter', ['chapter' => $chapter->previous_chapter()->first(), 'page' => (count($chapter->previous_chapter()->first()->pages) -1)])}}"><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
				</li>
			@else
				<li id="previous_page_link_container" style="display: none;">
					<a id="previous_page_link" href="#"><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
				</li>
			@endif
			
			@if($page_number < (count($chapter->pages) - 1))
				<li id="next_page_link_container">
					<a id="next_page_link" href="{{route('show_chapter', ['chapter' => $chapter, 'page' => ($page_number + 1)])}}"><i class="fa fa-chevron-right" aria-hidden="true"></i></a>
				</li>
			@else
				<li id="next_page_link_container" style="display: none;">
					<a id="next_page_link" href="#"><i class="fa fa-chevron-right" aria-hidden="true"></i></a>
				</li>
			@endif
			
			@if(count($chapter->next_chapter()->first()))
				<li id="next_chapter_link_container">
					<a id="next_chapter_link" href="{{route('show_chapter', ['chapter' => $chapter->next_chapter()->first(), 'page' => 0])}}"><i class="fa fa-chevron-right" aria-hidden="true"></i><i class="fa fa-chevron-right" aria-hidden="true"></i></a>
				</li>
			@endif
			
			</ul>
	</div>
@else
	<div class="alert alert-warning">
		This chapter has no associated pages.  
		@if(Auth::user())
			You can add pages to the chapter <a href="{{route('edit_chapter', ['chapter' => $chapter])}}" class="alert-link">here</a>.
		@endif
		<br>
		@if(count($chapter->previous_chapter()->first()))
			<a href="{{route('show_chapter', ['chapter' => $chapter->previous_chapter()->first(), 'page' => 0])}}">Previous Chapter</a> 
		@endif
		
		@if(count($chapter->next_chapter()->first()))
			<a href="{{route('show_chapter', ['chapter' => $chapter->next_chapter->first(), page => 0])}}">Next Chapter</a> 
		@endif
	</div>
@endif

@endsection

@section('footer')

@endsection
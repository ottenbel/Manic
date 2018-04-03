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
<script src="/js/confirmdelete.js"></script>
<script src="/js/viewer.js"></script>
<script src="/js/handleexport.js"></script>
@endsection

@section('content')
<div class="container">
	<div>
		@if($chapter->name)
			<div class="col-md-10">
				<b><a href="{{route('show_collection', ['collection' => $collection])}}">{{{$collection->name}}}</a></b> - Ch {{$chapter->chapter_number}} - {{{$chapter->name}}}
			</div>
		@else
			<div class="col-md-9">
				<b><a href="{{route('show_collection', ['collection' => $collection])}}">{{{$collection->name}}}</a></b> - Ch {{$chapter->chapter_number}}
			</div>
		@endif
		
		<div class="col-md-2">
			{{Form::selectRange('jump_selected_page', 0, count($chapter->pages) - 1, $page_number, ['id' => 'jump_selected_page'])}}
			{{ Form::submit('Jump', array('id' => 'jump_button')) }}
		</div>
	</div>
	@if((count($chapter->primary_scanalators)) || (count($chapter->secondary_scanalators)) || ($chapter->source != null) || (Auth::check() && Auth::user()->can('export', $chapter)))
		<button class="closedAccordion">Additional Chapter Information:</button>
		<div class="volume_panel" id="additional_chapter_information">
			@if((count($chapter->primary_scanalators)) || (count($chapter->secondary_scanalators)))
				<div class="row">
					<div class="col-md-2">
						<strong>Scanalators:</strong> 
					</div>
					<div class="scanalator_holder col-md-10">			
					@foreach($chapter->primary_scanalators()->withCount('chapters')->orderBy('chapters_count', 'desc')->orderBy('name', 'asc')->get() as $scanalator)
							@include('partials.tagObjects.display.display-tag-search-object',
								['tagObject' => $scanalator,
									'tagObjectClass' => 'primary_scanalators',
									'tagObjectCountClass' => 'scanalator_count',
									'componentToken' => 'scanalator'])
						@endforeach
						
						@foreach($chapter->secondary_scanalators()->withCount('chapters')->orderBy('chapters_count', 'desc')->orderBy('name', 'asc')->get() as $scanalator)
							@include('partials.tagObjects.display.display-tag-search-object',
								['tagObject' => $scanalator,
									'tagObjectClass' => 'secondary_scanalators',
									'tagObjectCountClass' => 'scanalator_count',
									'componentToken' => 'scanalator'])
						@endforeach
					</div>
				</div>
			@endif
			
			@if($chapter->source != null)
				<div class="row">
					<div class="col-md-2">
						<strong>Source URL:</strong> 
					</div>
					<div class="col-md-2">
						<span class="source_tag"><a href="{{$chapter->source}}">Source</a></source>
					</div>
				</div>
			@endif
			
			<div class="row">
				@can('export', $chapter)
					<span style="float:left">
						<a class="btn btn-sm btn-success" id="export_chapter_button" href="{{route('export_chapter', $chapter)}}" role="button" onclick="ConfirmExport(this, event)"><i class="fa fa-download" aria-hidden="true"></i> Download Chapter</a>
					</span>
				@endcan
				
				@if(Auth::check() && (Auth::user()->can('delete', $chapter)) && (Auth::user()->cannot('update', $chapter)))
					<span style="foat:right">
						<form method="POST" action="{{route('delete_chapter', ['chapter' => $chapter])}}">
							{{ csrf_field() }}
							{{method_field('DELETE')}}
							
							{{ Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete Chapter', array('type' => 'submit', 'class' => 'btn btn-sm btn-danger', 'onclick' =>'ConfirmDelete(event)')) }}
						</form>
					</span>
				@endif
			</div>
		</div>
	@endif
</div>

@if (count($chapter->pages) > 0)
	<br/>
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
	<br/>
	<div class="alert alert-warning" style="text-align:center">
		This chapter has no associated pages.  
		@can('update', $chapter)
			<br/>
			<br/>
			You can add pages to the chapter <a href="{{route('edit_chapter', ['chapter' => $chapter])}}" class="alert-link">here</a>.
		@endcan
		
		@if(count($chapter->previous_chapter()->first()) || count($chapter->next_chapter()->first()))
			<br/>
			<div class="row">
				@if(count($chapter->previous_chapter()->first()))
					<div class="col-md-6">
						<a href="{{route('show_chapter', ['chapter' => $chapter->previous_chapter()->first(), 'page' => 0])}}">Previous Chapter</a> 
					</div>
				@endif
				
				@if(count($chapter->next_chapter()->first()))
					<div class="col-md-6">
					<a href="{{route('show_chapter', ['chapter' => $chapter->next_chapter->first(), 'page' => 0])}}">Next Chapter</a> 
					</div>
				@endif
			</div>
		@endif
	</div>
@endif

<script>
	preload(pages);
</script>

@endsection

@section('footer')

@endsection
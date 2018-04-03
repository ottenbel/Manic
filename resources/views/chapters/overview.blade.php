@extends('layouts.app')

@section('title')
	{{$collection->name}} - Chapter {{$chapter->chapter_number}} {{$chapter->name}}
@endsection

@section('head')

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
	
	<table class="table table-responsive">
		@foreach($chapter->pages()->orderBy('page_number', 'asc')->get() as $page)
			@if(($loop->index % 4) == 0)
				<tr>
			@endif
				<td>
					<div>
						<a href="{{route('show_chapter', ['chapter' => $chapter, 'page' => $page->pivot->page_number])}}"><img src="{{asset($page->thumbnail)}}" class="img-responsive img-rounded" alt="Page {{$chapter->page_number}}" height="150px" width="150px"></a>
					</div>
				</td>
			@if(($loop->index % 4) == 3)
				</tr>
			@endif			
		@endforeach
	</table>
	
	<div class="container" align="center">
		<ul class="pagination">
			@if($previous_chapter_id != null)
				<li id="previous_chapter_link_container">
					<a id="previous_chapter_link" href="{{route('overview_chapter', ['chapter' => $previous_chapter_id])}}"><i class="fa fa-chevron-left" aria-hidden="true"></i><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
				</li>
			@endif
			  
			@if($next_chapter_id != null)
				<li id="next_chapter_link_container">
					<a id="next_chapter_link" href="{{route('overview_chapter', ['chapter' => $next_chapter_id, 'page' => 0])}}"><i class="fa fa-chevron-right" aria-hidden="true"></i><i class="fa fa-chevron-right" aria-hidden="true"></i></a>
				</li>
			@endif
			
		</ul>
	</div>
	
</div>
@endsection

@section('footer')

@endsection
@if($hideVolumes == true)
	@if($openAccordion == true)
		<button id="{{$volume->id}}_button" class="openAccordion" type="button" style="display:none">
	@else
		<button id="{{$volume->id}}_button" class="closedAccordion" type="button" style="display:none">
	@endif
@else
	@if($openAccordion == true)
		<button id="{{$volume->id}}_button" class="openAccordion" type="button">
	@else
		<button id="{{$volume->id}}_button" class="closedAccordion" type="button">
	@endif
@endif
	@if($volume->name != null && $volume->name != "")
		Volume {{$volume->volume_number}} - {{{$volume->name}}}
	@else
		Volume {{$volume->volume_number}}
	@endif 
</button>
<div id="{{$volume->id}}_panel" class="volume_panel">
	@if(($volume->cover_image != null) && ($chapterOnly != true))
		<div id="cover" class="col-md-2">
			@if((Auth::check()) && (($editVolume && Auth::user()->can('Edit Volume')) || (Auth::user()->can('Edit Volume') && Auth::user()->cannot('Edit Collection'))))
				<a href="{{route('edit_volume', ['volume' => $volume])}}"><img src="{{asset($volume->cover_image->thumbnail)}}" class="img-responsive img-rounded" alt="Volume Cover" height="100%" width="100%"</a>
			@else
				<img src="{{asset($volume->cover_image->thumbnail)}}" class="img-responsive img-rounded" alt="Volume Cover" height="100%" width="100%">
			@endif
		</div>
		<div id="body" class="col-md-10">
	@else
		<div>
	@endif
	
		@if((Auth::check()) && (($editVolume && Auth::user()->can('update', $volume)) || (Auth::user()->can('update', $volume) && Auth::user()->cannot('Edit Collection'))))
			<a href="{{route('edit_volume', ['volume' => $volume])}}"><h4>
				@if($volume->name != null && $volume->name != "")
					Volume {{$volume->volume_number}} - {{{$volume->name}}}
				@else
					Volume {{$volume->volume_number}}
				@endif 
			</h4></a>
		@endif
		
		<div class="row">
			@if(Route::is('show_collection') && ($volume->chapters->count() > 1))
				@can('export', $volume)
					<span style="float:left">
						<a class="btn btn-sm btn-success" id="export_volume_button" href="{{route('export_volume', $volume)}}" role="button" onclick="ConfirmExport(this, event)"><i class="fa fa-download" aria-hidden="true"></i> Download Volume</a>
					</span>
				@endcan
			@endif
		
			@if((Auth::check()) && (Auth::user()->cannot('update', $volume) && Auth::user()->can('delete', $volume)))
				<span style="float:left">
					<form method="POST" action="{{route('delete_volume', ['volume' => $volume])}}">
						{{ csrf_field() }}
						{{method_field('DELETE')}}
						
						{{ Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete Volume', array('type' => 'submit', 'class' => 'btn btn-sm btn-danger', 'onclick' =>'ConfirmDelete(event)')) }}
					</form>
				</span>
			@endif
		</div>
		
		<table width="100%">
			@foreach($volume->chapters()->orderBy('chapter_number', 'asc')->get() as $chapter)
				<tr>
					@if($chapter->name != null && $chapter->name != "")
						<td>
							@if($editChapter && (Auth::check()) && (Auth::user()->can('Edit Chapter')))
								<a href="{{route('edit_chapter', ['chapter' => $chapter])}}">Chapter {{$chapter->chapter_number}}</a> - {{{$chapter->name}}}
							@else
								<a href="{{route('show_chapter', ['chapter' => $chapter])}}">Chapter {{$chapter->chapter_number}}</a> - {{{$chapter->name}}}
							@endif
							
						</td>
					@else
						<td>
							@if($editChapter && (Auth::check()) && (Auth::user()->can('Edit Chapter')))
								<a href="{{route('edit_chapter', ['chapter' => $chapter])}}">Chapter {{$chapter->chapter_number}}</a>
							@else
								<a href="{{route('show_chapter', ['chapter' => $chapter])}}">Chapter {{$chapter->chapter_number}}</a>
							@endif
						</td>
					@endif
					<td>
						@if(($chapter->primary_scanalators()) || ($chapter->secondary_scanalators()))
							<div class="scanalator_holder">			
							@foreach($chapter->primary_scanalators()->withCount('chapters')->orderBy('chapters_count', 'desc')->orderBy('name', 'asc')->get() as $scanalator)
									@if($scanalatorLinkRoute == 'index_collection')
										@include('partials.tagObjects.display.display-tag-search-object',
											['tagObject' => $scanalator,
												'tagObjectClass' => 'primary_scanalators',
												'tagObjectCountClass' => 'scanalator_count',
												'componentToken' => 'scanalator'])
									@else
										@if(($scanalatorLinkRoute == 'edit_scanalator') && (Auth::check()) && (Auth::user()->can('Edit Scanalator')))
											<span class="primary_scanalators"><a href="{{route('edit_scanalator', ['scanalator' => $scanalator])}}">{{{$scanalator->name}}} <span class="scanalator_count"> ({{$scanalator->usage_count()}})</span></a></span>
										@else
											<span class="primary_scanalators"><a href="{{route('show_scanalator', ['scanalator' => $scanalator])}}">{{{$scanalator->name}}} <span class="scanalator_count"> ({{$scanalator->usage_count()}})</span></a></span>
										@endif
										
									@endif
								@endforeach
								
								@foreach($chapter->secondary_scanalators()->withCount('chapters')->orderBy('chapters_count', 'desc')->orderBy('name', 'asc')->get() as $scanalator)
									@if($scanalatorLinkRoute == 'index_collection')
										@include('partials.tagObjects.display.display-tag-search-object',
											['tagObject' => $scanalator,
												'tagObjectClass' => 'secondary_scanalators',
												'tagObjectCountClass' => 'scanalator_count',
												'componentToken' => 'scanalator'])
									@else
										@if(($scanalatorLinkRoute == 'edit_scanalator') && (Auth::check()) && (Auth::user()->can('Edit Scanalator')))
											<span class="secondary_scanalators"><a href="{{route('edit_scanalator', ['scanalator' => $scanalator->name])}}">{{{$scanalator->name}}} <span class="scanalator_count">({{$scanalator->usage_count()}})</span></a></span>
										@else
											<span class="secondary_scanalators"><a href="{{route('show_scanalator', ['scanalator' => $scanalator->name])}}">{{{$scanalator->name}}} <span class="scanalator_count">({{$scanalator->usage_count()}})</span></a></span>
										@endif
										
									@endif
									
								@endforeach
							</div>
						@endif
					</td>
					@if($chapter->source != null)
						<td>
							<span class="source_tag"><a href="{{$chapter->source}}">Source</a></span>
						</td>
					@endif
				</tr>
			@endforeach
		</table>
	</div>
</div>
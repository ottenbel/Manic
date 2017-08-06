@if($hideVolumes == true)
	<button id="{{$volume->id}}_button" class="accordion" type="button" style="display:none">
@else
	<button id="{{$volume->id}}_button" class="accordion" type="button">
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
			@if($editVolume)
				<a href="{{route($editVolumeRoute, ['volume' => $volume])}}"><img src="{{asset($volume->cover_image->thumbnail)}}" class="img-responsive img-rounded" alt="Volume Cover" height="100%" width="100%"</a>
			@else
				<img src="{{asset($volume->cover_image->thumbnail)}}" class="img-responsive img-rounded" alt="Volume Cover" height="100%" width="100%">
			@endif
		</div>
		<div id="body" class="col-md-10">
	@else
		<div>
	@endif
	
		@if($editVolume)
			<a href="{{route($editVolumeRoute, ['volume' => $volume])}}"><h4>
				@if($volume->name != null && $volume->name != "")
					Volume {{$volume->volume_number}} - {{{$volume->name}}}
				@else
					Volume {{$volume->volume_number}}
				@endif 
			</h4></a>
		@endif
		
		@if(Route::is('show_collection') && ($volume->chapters->count() > 1))
			@can('export', $volume)
				<div class="text-right">
					<a class="btn btn-sm btn-success" id="export_chapter_button" href="{{route('export_volume', $volume)}}" role="button" onclick="ConfirmExport(this)"><i class="fa fa-download" aria-hidden="true"></i> Download Volume</a>
				</div>
			@endcan
		@endif
		
		<table width="100%">
			@foreach($volume->chapters()->orderBy('chapter_number', 'asc')->get() as $chapter)
				<tr>
					@if($chapter->name != null && $chapter->name != "")
						<td>
							<a href="{{route($chapterLinkRoute, ['chapter' => $chapter])}}">Chapter {{$chapter->chapter_number}}</a> - {{{$chapter->name}}}
						</td>
					@else
						<td>
							<a href="{{route($chapterLinkRoute, ['chapter' => $chapter])}}">Chapter {{$chapter->chapter_number}}</a>
						</td>
					@endif
					<td>
						@if(($chapter->primary_scanalators()) || ($chapter->secondary_scanalators()))
							<div class="scanalator_holder">			
							@foreach($chapter->primary_scanalators()->withCount('chapters')->orderBy('chapters_count', 'desc')->orderBy('name', 'asc')->get() as $scanalator)
									@if($scanalatorLinkRoute == 'index_collection')
										<span class="primary_scanalators"><a href="{{route($scanalatorLinkRoute, ['search' => 'scanalator:' . $scanalator->name])}}">{{{$scanalator->name}}} <span class="scanalator_count"> ({{$scanalator->usage_count()}})</span></a></span>
									@else
										<span class="primary_scanalators"><a href="{{route($scanalatorLinkRoute, ['scanalator' => $scanalator->name])}}">{{{$scanalator->name}}} <span class="scanalator_count"> ({{$scanalator->usage_count()}})</span></a></span>
									@endif
								@endforeach
								
								@foreach($chapter->secondary_scanalators()->withCount('chapters')->orderBy('chapters_count', 'desc')->orderBy('name', 'asc')->get() as $scanalator)
									@if($scanalatorLinkRoute == 'index_collection')
										<span class="secondary_scanalators"><a href="{{route($scanalatorLinkRoute, ['search' => 'scanalator:' . $scanalator->name])}}">{{{$scanalator->name}}} <span class="scanalator_count">({{$scanalator->usage_count()}})</span></a></span>
									@else
										<span class="secondary_scanalators"><a href="{{route($scanalatorLinkRoute, ['scanalator' => $scanalator->name])}}">{{{$scanalator->name}}} <span class="scanalator_count">({{$scanalator->usage_count()}})</span></a></span>
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
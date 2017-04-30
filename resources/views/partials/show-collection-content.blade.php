<div id = "volumes">
	@if($volumes->count() > 0)
		<br/>
		@foreach($volumes->orderBy('volume_number', 'asc')->get() as $volume)
			<button class="accordion" type="button">
				@if($volume->name != null && $volume->name != "")
					Volume {{$volume->volume_number}} - {{{$volume->name}}}
				@else
					Volume {{$volume->volume_number}}
				@endif 
			</button>
			<div class="volume_panel">
				@if($volume->cover_image != null)
					<div id="cover" class="col-md-2">
						@if($edit)
							<a href="{{route($editVolumeRoute, ['volume' => $volume])}}"><img src="{{asset($volume->cover_image->name)}}" class="img-responsive img-rounded" alt="Volume Cover" height="100%" width="100%"</a>
						@else
							<img src="{{asset($volume->cover_image->name)}}" class="img-responsive img-rounded" alt="Volume Cover" height="100%" width="100%">
						@endif
					</div>
					<div id="cover" class="col-md-10">
				@else
					<div>
				@endif
				
					@if($edit)
						<a href="{{route($editVolumeRoute, ['volume' => $volume])}}"><h4>
							@if($volume->name != null && $volume->name != "")
								Volume {{$volume->volume_number}} - {{{$volume->name}}}
							@else
								Volume {{$volume->volume_number}}
							@endif 
						</h4></a>
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
												<span class="primary_scanalators"><a href="{{route($scanalatorLinkRoute, ['scanalator' => $scanalator])}}">{{{$scanalator->name}}} <span class="scanalator_count"> ({{$scanalator->usage_count()}})</span></a></span>
											@endforeach
											
											@foreach($chapter->secondary_scanalators()->withCount('chapters')->orderBy('chapters_count', 'desc')->orderBy('name', 'asc')->get() as $scanalator)
												<span class="secondary_scanalators"><a href="{{route($scanalatorLinkRoute, ['scanalator' => $scanalator])}}">{{{$scanalator->name}}} <span class="scanalator_count">({{$scanalator->usage_count()}})</span></a></span>
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
		@endforeach
	@endif
</div>
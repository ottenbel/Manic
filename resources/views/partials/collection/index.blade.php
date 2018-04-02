@if($collections->count() == 0)
	@can('create', App\Models\Collection::class)
		<div class="text-center">
			No collections have been found in the database. Add a new collection <a href = "{{route('create_collection')}}">here.</a>
		</div>
	@endcan
	@cannot('create', App\Models\Collection::class)
		<div class="text-center">
			No collections have been found in the database.
		</div>
	@endcan
@else
	<table class="table table-striped">
		@foreach($collections as $collection)
			<tr>
				<td class="col-xs-2">
				@if($collection->cover_image != null)
					@if(Route::is('index_collection_blacklist'))
						<div><img src="{{asset($collection->cover_image->thumbnail)}}" class="img-responsive img-rounded"></div>
					@else
						<div><a href="{{route('show_collection', ['collection' => $collection])}}"><img src="{{asset($collection->cover_image->thumbnail)}}" class="img-responsive img-rounded"></a></div>
					@endif
				@endif
				@if(Route::is('index_collection_blacklist'))
					@if(Auth::check() && Auth::user()->hasPermissionTo('Delete Blacklisted Collection'))
						<form method="POST" action="{{route('delete_collection_blacklist', ['collection' => $collection])}}">
							{{ csrf_field() }}
							{{method_field('DELETE')}}
							
							{{ Form::button('<i class="fa fa-trash" aria-hidden="true"></i> Remove From Blacklist', array('type' => 'submit', 'class' => 'btn btn-sm btn-danger')) }}
						</form>
					@endif
				@endif
				</td>
				<td class="col-xs-10">
					@if(Route::is('index_collection_blacklist'))
						<div><h4>{{{$collection->name}}}</h4></div>
					@else
						<div><a href="{{route('show_collection', ['collection' => $collection])}}"><h4>{{{$collection->name}}}</h4></a></div>
					@endif
					
					@if(($collection->primary_artists->count()) || ($collection->secondary_artists->count()))
						<div class="row">
							<div class="tag_holder">
								<div class="col-md-2">
									<strong>Artists:</strong>
								</div>
								<div class="col-md-10">
									@foreach($collection->primary_artists as $artist)
										@include('partials.tagObjects.display.display-tag-search-object',
											['tagObject' => $artist,
												'tagObjectClass' => 'primary_artists',
												'tagObjectCountClass' => 'artist_count',
												'componentToken' => 'artist'])
									@endforeach
									@if(10 > $collection->primary_artists->count())
										@foreach($collection->secondary_artists->take(10 - $collection->primary_artists->count()) as $artist)
											@include('partials.tagObjects.display.display-tag-search-object',
											['tagObject' => $artist,
												'tagObjectClass' => 'secondary_artists',
												'tagObjectCountClass' => 'artist_count',
												'componentToken' => 'artist'])
										@endforeach
									@endif
								</div>
							</div>
						</div>
					@endif
					
					@if(($collection->primary_series->count()) || ($collection->secondary_series->count()))
						<div class="row">
							<div class="tag_holder">
								<div class="col-md-2">
									<strong>Series:</strong>
								</div>
								<div class="col-md-10">
									@foreach($collection->primary_series as $series)
										@include('partials.tagObjects.display.display-tag-search-object',
											['tagObject' => $series,
												'tagObjectClass' => 'primary_series',
												'tagObjectCountClass' => 'series_count',
												'componentToken' => 'series'])
									@endforeach
									@if(10 > $collection->primary_series->count())
										@foreach($collection->secondary_series->take(10 - $collection->primary_series->count()) as $series)
											@include('partials.tagObjects.display.display-tag-search-object',
											['tagObject' => $series,
												'tagObjectClass' => 'secondary_series',
												'tagObjectCountClass' => 'series_count',
												'componentToken' => 'series'])
										@endforeach
									@endif
								</div>
							</div>
						</div>
					@endif
					
					@if(($collection->primary_characters->count()) || $collection->secondary_characters->count())
						<div class="row">
							<div class="tag_holder">
								<div class="col-md-2">
									<strong>Characters:</strong>
								</div>
								<div class="col-md-10">
									@foreach($collection->primary_characters as $character)
										@include('partials.tagObjects.display.display-tag-search-object',
											['tagObject' => $character,
												'tagObjectClass' => 'primary_characters',
												'tagObjectCountClass' => 'character_count',
												'componentToken' => 'character'])
									@endforeach
									@if(10 > $collection->primary_characters->count())
										@foreach($collection->secondary_characters->take(10 - $collection->primary_characters()->count()) as $character)
											@include('partials.tagObjects.display.display-tag-search-object',
											['tagObject' => $character,
												'tagObjectClass' => 'secondary_characters',
												'tagObjectCountClass' => 'character_count',
												'componentToken' => 'character'])
										@endforeach
									@endif
								</div>
							</div>
						</div>
					@endif
					
					@if(($collection->primary_tags->count()) || ($collection->secondary_tags->count()))
						<div class="row">
							<div class="tag_holder">
								<div class="col-md-2">
									<strong>Tags:</strong>
								</div>
								<div class="col-md-10">
									@foreach($collection->primary_tags as $tag)
										@include('partials.tagObjects.display.display-tag-search-object',
											['tagObject' => $tag,
												'tagObjectClass' => 'primary_tags',
												'tagObjectCountClass' => 'tag_count',
												'componentToken' => 'tag'])
									@endforeach
									@if(10 > $collection->primary_tags->count())
										@foreach($collection->secondary_tags->take(10 - $collection->primary_tags->count()) as $tag)
											@include('partials.tagObjects.display.display-tag-search-object',
											['tagObject' => $tag,
												'tagObjectClass' => 'secondary_tags',
												'tagObjectCountClass' => 'tag_count',
												'componentToken' => 'tag'])
										@endforeach
									@endif
								</div>
							</div>
						</div>
					@endif
					</td>
					
					<td class="col-xs-10">
						@if($collection->rating != null)
								<strong>Rating:</strong><span class="rating_tag"><a href="{{route('index_collection', ['search' => 'rating:'. $collection->rating->name])}}">{{{$collection->rating->name}}}</a></span>
							@endif
							
							@if($collection->status != null)
								<strong>Status:</strong><span class="status_tag"><a href="{{route('index_collection', ['search' => 'status:'. $collection->status->name])}}">{{{$collection->status->name}}}</a></span>
							@endif
							
							@if($collection->language != null)
								<strong>Language:</strong><span class="language_tag"><a href="{{route('index_collection', ['search' => 'language:'. $collection->language->name])}}">{{{$collection->language->name}}}</a></span>
							@endif
					</td>
			</tr>
		@endforeach
	</table>
@endif
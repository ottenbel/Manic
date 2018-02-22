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
					<a href="{{route('show_collection', ['collection' => $collection])}}"><img src="{{asset($collection->cover_image->thumbnail)}}" class="img-responsive img-rounded"></a>
				@endif
				</td>
				<td class="col-xs-10">
					<div><a href="{{route('show_collection', ['collection' => $collection])}}"><h4>{{{$collection->name}}}</h4></a></div>
					
					@if(($collection->primary_artists()->count()) || ($collection->secondary_artists()->count()))
						<div class="row">
							<div class="tag_holder">
								<div class="col-md-2">
									<strong>Artists:</strong>
								</div>
								<div class="col-md-10">
									@foreach($collection->primary_artists()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->take(10)->get() as $artist)
										@include('partials.tagObjects.display.display-tag-search-object',
											['tagObject' => $artist,
												'tagObjectClass' => 'primary_artists',
												'tagObjectCountClass' => 'artist_count',
												'componentToken' => 'artist'])
									@endforeach
									@if(10 > $collection->primary_artists()->count())
										@foreach($collection->secondary_artists()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->take(10 - $collection->primary_artists()->count())->get() as $artist)
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
					
					@if(($collection->primary_series()->count()) || ($collection->secondary_series()->count()))
						<div class="row">
							<div class="tag_holder">
								<div class="col-md-2">
									<strong>Series:</strong>
								</div>
								<div class="col-md-10">
									@foreach($collection->primary_series()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->take(10)->get() as $series)
										@include('partials.tagObjects.display.display-tag-search-object',
											['tagObject' => $series,
												'tagObjectClass' => 'primary_series',
												'tagObjectCountClass' => 'series_count',
												'componentToken' => 'series'])
									@endforeach
									@if(10 > $collection->primary_series()->count())
										@foreach($collection->secondary_series()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->take(10 - $collection->primary_series()->count())->get() as $series)
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
					
					@if(($collection->primary_characters()->count()) 
						|| $collection->secondary_characters()->count())
						<div class="row">
							<div class="tag_holder">
								<div class="col-md-2">
									<strong>Characters:</strong>
								</div>
								<div class="col-md-10">
									@foreach($collection->primary_characters()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->take(10)->get() as $character)
										@include('partials.tagObjects.display.display-tag-search-object',
											['tagObject' => $character,
												'tagObjectClass' => 'primary_characters',
												'tagObjectCountClass' => 'character_count',
												'componentToken' => 'character'])
									@endforeach
									@if(10 > $collection->primary_characters()->count())
										@foreach($collection->secondary_characters()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->take(10 - $collection->primary_characters()->count())->get() as $character)
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
					
					@if(($collection->primary_tags()->count()) || ($collection->secondary_tags()->count()))
						<div class="row">
							<div class="tag_holder">
								<div class="col-md-2">
									<strong>Tags:</strong>
								</div>
								<div class="col-md-10">
									@foreach($collection->primary_tags()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->take(10)->get() as $tag)
										@include('partials.tagObjects.display.display-tag-search-object',
											['tagObject' => $tag,
												'tagObjectClass' => 'primary_tags',
												'tagObjectCountClass' => 'tag_count',
												'componentToken' => 'tag'])
									@endforeach
									@if(10 > $collection->primary_tags()->count())
										@foreach($collection->secondary_tags()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->take(10 - $collection->primary_tags->count())->get() as $tag)
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
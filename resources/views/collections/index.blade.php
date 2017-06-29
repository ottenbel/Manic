@extends('layouts.app')

@section('title')
Index - Page {{$collections->currentPage()}}
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	<div class="row">
		@if(($search_artists_array != null) || ($search_characters_array != null) || ($search_scanalators_array != null) 
			|| ($search_series_array != null) || ($search_tags_array != null) || ($search_languages_array != null) 
			|| ($search_ratings_array != null) || ($search_statues_array != null) || ($search_canonicity_array != null) 
			|| ($invalid_tokens_array != null))
			<!--Begin Pannel-->
			<button id="search_panel" class="accordion" type="button">
				Search Breakdown
			</button>
			<div id = "search_panel" class="volume_panel">
				@if(count($search_artists_array) > 0)
					<div class="row">
						<div class="tag_holder">
							<div class="col-md-2">
								<strong>Artists:</strong>
							</div>
							<div class="col-md-10">
								@foreach($search_artists_array as $artist)
									@if($artist['not'])
										@if ($artist['primary'])
											<span class="global_artist_alias"><a href="{{route('show_artist', ['artist' => $artist['artist']])}}">{{{$artist['artist']->name}}}</a></span>
										@elseif ($artist['secondary'])
											<span class="personal_artist_alias"><a href="{{route('show_artist', ['artist' => $artist['artist']])}}">{{{$artist['artist']->name}}}</a></span>
										@else
											<span class="not_combined_artists"><a href="{{route('show_artist', ['artist' => $artist['artist']])}}">{{{$artist['artist']->name}}}</a></span>
										@endif
									@else
										@if ($artist['primary'])
											<span class="primary_artists"><a href="{{route('show_artist', ['artist' => $artist['artist']])}}">{{{$artist['artist']->name}}}</a></span>
										@elseif ($artist['secondary'])
											<span class="secondary_artists"><a href="{{route('show_artist', ['artist' => $artist['artist']])}}">{{{$artist['artist']->name}}}</a></span>
										@else
											<span class="combined_artists"><a href="{{route('show_artist', ['artist' => $artist['artist']])}}">{{{$artist['artist']->name}}}</a></span>
										@endif
									@endif
								@endforeach
							</div>
						</div>
					</div>
				@endif
				
				@if(count($search_series_array) > 0)
					<div class="row">
						<div class="tag_holder">
							<div class="col-md-2">
								<strong>Series:</strong>
							</div>
							<div class="col-md-10">
								@foreach($search_series_array as $series)
									@if($series['not'])
										@if ($series['primary'])
											<span class="global_series_alias"><a href="{{route('show_series', ['series' => $series['series']])}}">{{{$series['series']->name}}}</a></span>
										@elseif ($series['secondary'])
											<span class="personal_series_alias"><a href="{{route('show_series', ['series' => $series['series']])}}">{{{$series['series']->name}}}</a></span>
										@else
											<span class="not_combined_series"><a href="{{route('show_series', ['series' => $series['series']])}}">{{{$series['series']->name}}}</a></span>
										@endif
									@else
										@if ($series['primary'])
											<span class="primary_series"><a href="{{route('show_series', ['series' => $series['series']])}}">{{{$series['series']->name}}}</a></span>
										@elseif ($series['secondary'])
											<span class="secondary_series"><a href="{{route('show_series', ['series' => $series['series']])}}">{{{$series['series']->name}}}</a></span>
										@else
											<span class="combined_series"><a href="{{route('show_series', ['series' => $series['series']])}}">{{{$series['series']->name}}}</a></span>
										@endif
									@endif
								@endforeach
							</div>
						</div>
					</div>
				@endif
				
				@if(count($search_characters_array) > 0)
					<div class="row">
						<div class="tag_holder">
							<div class="col-md-2">
								<strong>Characters:</strong>
							</div>
							<div class="col-md-10">
								@foreach($search_characters_array as $character)
									@if($character['not'])
										@if($character['primary'])
											<span class="global_character_alias"><a href="{{route('show_character', ['character' => $character['character']])}}">{{{$character['character']->name}}}</a></span>
										@elseif($character['secondary'])
											<span class="personal_character_alias"><a href="{{route('show_character', ['character' => $character['character']])}}">{{{$character['character']->name}}}</a></span>
										@else
											<span class="not_combined_characters"><a href="{{route('show_character', ['character' => $character['character']])}}">{{{$character['character']->name}}}</a></span>
										@endif
									@else
										@if ($character['primary'])
											<span class="primary_characters"><a href="{{route('show_character', ['character' => $character['character']])}}">{{{$character['character']->name}}}</a></span>
										@elseif ($character['secondary'])
											<span class="secondary_characters"><a href="{{route('show_character', ['character' => $character['character']])}}">{{{$character['character']->name}}}</a></span>
										@else
											<span class="combined_characters"><a href="{{route('show_character', ['character' => $character['character']])}}">{{{$character['character']->name}}}</a></span>
										@endif
									@endif
								@endforeach
							</div>
						</div>
					</div>
				@endif
				
				@if(count($search_tags_array) > 0)
					<div class="row">
						<div class="tag_holder">
							<div class="col-md-2">
								<strong>Tags:</strong>
							</div>
							<div class="col-md-10">
								@foreach($search_tags_array as $tag)
									@if($tag['not'])
										@if($tag['primary'])
											<span class="global_tag_alias"><a href="{{route('show_tag', ['tag' => $tag['tag']])}}">{{{$tag['tag']->name}}}</a></span>
										@elseif($tag['secondary'])
											<span class="personal_tag_alias"><a href="{{route('show_tag', ['tag' => $tag['tag']])}}">{{{$tag['tag']->name}}}</a></span>
										@else
											<span class="not_combined_tags"><a href="{{route('show_tag', ['tag' => $tag['tag']])}}">{{{$tag['tag']->name}}}</a></span>
										@endif
									@else
										@if ($tag['primary'])
											<span class="primary_tags"><a href="{{route('show_tag', ['tag' => $tag['tag']])}}">{{{$tag['tag']->name}}}</a></span>
										@elseif ($tag['secondary'])
											<span class="secondary_tags"><a href="{{route('show_tag', ['tag' => $tag['tag']])}}">{{{$tag['tag']->name}}}</a></span>
										@else
											<span class="combined_tags"><a href="{{route('show_tag', ['tag' => $tag['tag']])}}">{{{$tag['tag']->name}}}</a></span>
										@endif
									@endif
								@endforeach
							</div>
						</div>
					</div>
				@endif
				
				@if(count($search_scanalators_array) > 0)
					<div class="row">
						<div class="tag_holder">
							<div class="col-md-2">
								<strong>Scanalators:</strong>
							</div>
							<div class="col-md-10">
								@foreach($search_scanalators_array as $scanalator)
									@if($scanalator['not'])
										@if($scanalator['primary'])
											<span class="global_scanalator_alias"><a href="{{route('show_scanalator', ['scanalator' => $scanalator['scanalator']])}}">{{{$scanalator['scanalator']->name}}}</a></span>
										@elseif($scanalator['secondary'])
											<span class="personal_scanalator_alias"><a href="{{route('show_scanalator', ['scanalator' => $scanalator['scanalator']])}}">{{{$scanalator['scanalator']->name}}}</a></span>
										@else
											<span class="not_combined_scanalators"><a href="{{route('show_scanalator', ['scanalator' => $scanalator['scanalator']])}}">{{{$scanalator['scanalator']->name}}}</a></span>
										@endif
									@else
										@if ($scanalator['primary'])
											<span class="primary_scanalators"><a href="{{route('show_scanalator', ['scanalator' => $scanalator['scanalator']])}}">{{{$scanalator['scanalator']->name}}}</a></span>
										@elseif ($scanalator['secondary'])
											<span class="secondary_scanalators"><a href="{{route('show_scanalator', ['scanalator' => $scanalator['scanalator']])}}">{{{$scanalator['scanalator']->name}}}</a></span>
										@else
											<span class="combined_scanalators"><a href="{{route('show_scanalator', ['scanalator' => $scanalator['scanalator']])}}">{{{$scanalator['scanalator']->name}}}</a></span>
										@endif
									@endif
								@endforeach
							</div>
						</div>
					</div>
				@endif
				
				@if(count($search_languages_array) > 0)
					<div class="row">
						<div class="tag_holder">
							<div class="col-md-2">
								<strong>Languages (OR):</strong>
							</div>
							<div class="col-md-10">
								@foreach($search_languages_array as $language)
									@if($language['not'])
										<span class="not_language_tag"><a href="{{route('index_collection', ['search' => '-language:' . $language['language']->name])}}">{{{$language['language']->name}}}</a></span>
									@else
										<span class="language_tag"><a href="{{route('index_collection', ['search' => 'language:' . $language['language']->name])}}">{{{$language['language']->name}}}</a></span>
									@endif
								@endforeach
							</div>
						</div>
					</div>
				@endif
				
				@if(count($search_ratings_array) > 0)
					<div class="row">
						<div class="tag_holder">
							<div class="col-md-2">
								<strong>Ratings (OR):</strong>
							</div>
							<div class="col-md-10">
								@foreach($search_ratings_array as $rating)
									@if($rating['not'])
										<span class="not_rating_tag"><a href="{{route('index_collection', ['search' => '-rating:'.$rating['rating']->name])}}">{{{$rating['rating']->name}}}</a></span>
									@else
										<span class="rating_tag"><a href="{{route('index_collection', ['search' => 'rating:'.$rating['rating']->name])}}">{{{$rating['rating']->name}}}</a></span>
									@endif
								@endforeach
							</div>
						</div>
					</div>
				@endif
				
				@if(count($search_statues_array) > 0)
					<div class="row">
						<div class="tag_holder">
							<div class="col-md-2">
								<strong>Statuses (OR):</strong>
							</div>
							<div class="col-md-10">
								@foreach($search_statues_array as $status)
									@if($status['not'])
										<span class="not_status_tag"><a href="{{route('index_collection', ['search' => '-status:'.$status['status']->name])}}">{{{$status['status']->name}}}</a></span>
									@else
										<span class="status_tag"><a href="{{route('index_collection', ['search' => 'status:'.$status['status']->name])}}">{{{$status['status']->name}}}</a></span>
									@endif
								@endforeach
							</div>
						</div>
					</div>
				@endif
				
				@if(count($search_canonicity_array) > 0)
					<div class="row">
						<div class="tag_holder">
							<div class="col-md-2">
								<strong>Canonicity (OR):</strong>
							</div>
							<div class="col-md-10">
								@foreach($search_canonicity_array as $canonicity)
									@if($canonicity['canon'])
										@if($canonicity['not'])
											<span class="not_canonical_tag"><a href="{{route('index_collection', ['search' => '-canonical'])}}">Canonical</a></span>
										@else
											<span class="canonical_tag"><a href="{{route('index_collection', ['search' => 'canonical'])}}">Canonical</a></span>
										@endif
									@else
										@if($canonicity['not'])
											<span class="not_canonical_tag"><a href="{{route('index_collection', ['search' => '-non-canonical'])}}">Non-Canonical</a></span>
										@else
											<span class="canonical_tag"><a href="{{route('index_collection', ['search' => 'non-canonical'])}}">Non-Canonical</a></span>
										@endif
									@endif
								@endforeach
							</div>
						</div>
					</div>
				@endif
				
				@if(count($invalid_tokens_array) > 0)
					<div class="row">
						<div class="tag_holder">
							<div class="col-md-2">
								<strong>Invalid Tokens:</strong>
							</div>
							<div class="col-md-10">
								@foreach($invalid_tokens_array as $invalid_token)
									<span class="invalid_tag"><a href="{{route('index_collection', ['search' => $invalid_token])}}">{{{$invalid_token}}}</a></span>
								@endforeach
							</div>
						</div>
					</div>
				@endif
			</div>
			<!--end Pannel-->
		@endif
		
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
												<span class="primary_artists"><a href="{{route('index_collection', ['search' => 'artist:'. $artist->name])}}">{{{$artist->name}}} <span class="artist_count">({{$artist->usage_count()}})</span></a></span>
											@endforeach
											@if(10 > $collection->primary_artists()->count())
												@foreach($collection->secondary_artists()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->take(10 - $collection->primary_artists()->count())->get() as $artist)
													<span class="secondary_artists"><a href="{{route('index_collection', ['search' => 'artist:'. $artist->name])}}">{{{$artist->name}}} <span class="artist_count">({{$artist->usage_count()}})</span></a></span>
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
												<span class="primary_series"><a href="{{route('index_collection', ['search' => 'series:' . $series->name])}}">{{{$series->name}}} <span class="series_count">({{$series->usage_count()}})</span></a></span>
											@endforeach
											@if(10 > $collection->primary_series()->count())
												@foreach($collection->secondary_series()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->take(10 - $collection->primary_series()->count())->get() as $series)
													<span class="secondary_series"><a href="{{route('index_collection', ['search' => 'series:' . $series->name])}}">{{{$series->name}}} <span class="series_count">({{$series->usage_count()}})</span></a></span>
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
												<span class="primary_characters"><a href="{{route('index_collection', ['search' => 'character:' . $character->name])}}">{{{$character->name}}} <span class="character_count">({{$character->usage_count()}})</span></a></span>
											@endforeach
											@if(10 > $collection->primary_characters()->count())
												@foreach($collection->secondary_characters()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->take(10 - $collection->primary_characters()->count())->get() as $character)
													<span class="secondary_characters"><a href="{{route('index_collection', ['search' => 'character:' . $character->name])}}">{{{$character->name}}} <span class="character_count">({{$character->usage_count()}})</span></a></span>
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
												<span class="primary_tags"><a href="{{route('index_collection', ['search' => 'tag:' . $tag->name])}}">{{{$tag->name}}} <span class="tag_count"> ({{$tag->usage_count()}})</span></a></span>
											@endforeach
											@if(10 > $collection->primary_tags()->count())
												@foreach($collection->secondary_tags()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->take(10 - $collection->primary_tags->count())->get() as $tag)
													<span class="secondary_tags"><a href="{{route('index_collection', ['search' => 'tag:' . $tag->name])}}">{{{$tag->name}}} <span class="tag_count">({{$tag->usage_count()}})</span></a></span>
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
	</div>
	{{ $collections->links() }}
</div>
@endsection

@section('footer')

@endsection
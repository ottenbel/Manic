@if(($search_artists_array != null) || ($search_characters_array != null) || ($search_scanalators_array != null) 
			|| ($search_series_array != null) || ($search_tags_array != null) || ($search_languages_array != null) 
			|| ($search_ratings_array != null) || ($search_statues_array != null) || ($search_canonicity_array != null)
			|| ($search_favourites_array != null) || ($invalid_tokens_array != null))
			<!--Begin Pannel-->
			<button id="search_panel" class="openAccordion" type="button">
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
										@include('partials.searchDisplay.collection-display-search-tag-object-component', 
											['componentTagObject' => $artist['artist'], 
												'primary' => $artist['primary'],
												'secondary' => $artist['secondary'],
												'primaryComponentSpanClass' => 'global_artist_alias',
												'secondaryComponentSpanClass' => 'personal_artist_alias',
												'elseComponentSpanClass' => 'not_combined_artists',
												'componentRouteName' => 'show_artist',
												'componentObjectName' => 'artist'])
									@else
										@include('partials.searchDisplay.collection-display-search-tag-object-component', 
											['componentTagObject' => $artist['artist'], 
												'primary' => $artist['primary'],
												'secondary' => $artist['secondary'],
												'primaryComponentSpanClass' => 'primary_artists',
												'secondaryComponentSpanClass' => 'secondary_artists',
												'elseComponentSpanClass' => 'combined_artists',
												'componentRouteName' => 'show_artist',
												'componentObjectName' => 'artist'])
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
										@include('partials.searchDisplay.collection-display-search-tag-object-component', 
											['componentTagObject' => $series['series'], 
												'primary' => $series['primary'],
												'secondary' => $series['secondary'],
												'primaryComponentSpanClass' => 'global_series_alias',
												'secondaryComponentSpanClass' => 'personal_series_alias',
												'elseComponentSpanClass' => 'not_combined_series',
												'componentRouteName' => 'show_series',
												'componentObjectName' => 'series'])
									@else
										@include('partials.searchDisplay.collection-display-search-tag-object-component', 
											['componentTagObject' => $series['series'], 
												'primary' => $series['primary'],
												'secondary' => $series['secondary'],
												'primaryComponentSpanClass' => 'primary_series',
												'secondaryComponentSpanClass' => 'secondary_series',
												'elseComponentSpanClass' => 'combined_series',
												'componentRouteName' => 'show_series',
												'componentObjectName' => 'series'])
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
										@include('partials.searchDisplay.collection-display-search-tag-object-component',
											['componentTagObject' => $character['character'], 
												'primary' => $character['primary'],
												'secondary' => $character['secondary'],
												'primaryComponentSpanClass' => 'global_character_alias',
												'secondaryComponentSpanClass' => 'personal_character_alias',
												'elseComponentSpanClass' => 'not_combined_characters',
												'componentRouteName' => 'show_character',
												'componentObjectName' => 'character'])
									@else
										@include('partials.searchDisplay.collection-display-search-tag-object-component', 
											['componentTagObject' => $character['character'],
												'primary' => $character['primary'],
												'secondary' => $character['secondary'],
												'primaryComponentSpanClass' => 'primary_characters',
												'secondaryComponentSpanClass' => 'secondary_characters',
												'elseComponentSpanClass' => 'combined_characters',
												'componentRouteName' => 'show_character',
												'componentObjectName' => 'character'])
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
										@include('partials.searchDisplay.collection-display-search-tag-object-component', 
											['componentTagObject' => $tag['tag'], 
												'primary' => $tag['primary'],
												'secondary' => $tag['secondary'],
												'primaryComponentSpanClass' => 'global_tag_alias',
												'secondaryComponentSpanClass' => 'personal_tag_alias',
												'elseComponentSpanClass' => 'not_combined_tags',
												'componentRouteName' => 'show_tag',
												'componentObjectName' => 'tag'])
									@else
										@include('partials.searchDisplay.collection-display-search-tag-object-component', 
											['componentTagObject' => $tag['tag'], 
												'primary' => $tag['primary'],
												'secondary' => $tag['secondary'],
												'primaryComponentSpanClass' => 'primary_tags',
												'secondaryComponentSpanClass' => 'secondary_tags',
												'elseComponentSpanClass' => 'combined_tags',
												'componentRouteName' => 'show_tag',
												'componentObjectName' => 'tag'])
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
										@include('partials.searchDisplay.collection-display-search-tag-object-component', 
											['componentTagObject' => $scanalator['scanalator'], 
												'primary' => $scanalator['primary'],
												'secondary' => $scanalator['secondary'],
												'primaryComponentSpanClass' => 'global_scanalator_alias',
												'secondaryComponentSpanClass' => 'personal_scanalator_alias',
												'elseComponentSpanClass' => 'not_combined_scanalators',
												'componentRouteName' => 'show_scanalator',
												'componentObjectName' => 'scanalator'])
									@else
										@include('partials.searchDisplay.collection-display-search-tag-object-component', 
											['componentTagObject' => $scanalator['scanalator'], 
												'primary' => $scanalator['primary'],
												'secondary' => $scanalator['secondary'],
												'primaryComponentSpanClass' => 'primary_scanalators',
												'secondaryComponentSpanClass' => 'secondary_scanalators',
												'elseComponentSpanClass' => 'combined_scanalators',
												'componentRouteName' => 'show_scanalator',
												'componentObjectName' => 'scanalator'])
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
									@include('partials.searchDisplay.collection-display-search-generic-component', 
											['componentTagObject' => $language['language'],
												'componentNot' => $language['not'],
												'componentSpanClass' => 'language_tag',
												'notComponentSpanClass' => 'not_language_tag',
												'componentToken' => 'language',
												'componentRouteName' => 'show_language',
												'componentObjectName' => 'language'])
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
									@include('partials.searchDisplay.collection-display-search-generic-component', 
											['componentTagObject' => $rating['rating'],
												'componentNot' => $rating['not'],
												'componentSpanClass' => 'rating_tag',
												'notComponentSpanClass' => 'not_rating_tag',
												'componentToken' => 'rating'])
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
									@include('partials.searchDisplay.collection-display-search-generic-component', 
											['componentTagObject' => $status['status'],
												'componentNot' => $status['not'],
												'componentSpanClass' => 'status_tag',
												'notComponentSpanClass' => 'not_status_tag',
												'componentToken' => 'status'])
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
									@include('partials.searchDisplay.collection-display-search-canonical-component', 
										['componentNot' => $canonicity['not'],
											'componentSpanClass' => 'canonical_tag',
											'notComponentSpanClass' => 'not_canonical_tag',
											'componentToken' => 'canonical'])
								@endforeach
							</div>
						</div>
					</div>
				@endif
				
				@if(count($search_favourites_array) > 0)
					<div class="row">
						<div class="tag_holder">
							<div class="col-md-2">
								<strong>Favourites (IN):</strong>
							</div>
							<div class="col-md-10">
								@foreach($search_favourites_array as $favourite)
									@include('partials.searchDisplay.collection-display-search-favourites-component', 
										['componentNot' => $favourite['not'],
											'favouriteSpanClass' => 'favourite_tag',
											'notFavouriteSpanClass' => 'not_favourite_tag',
											'componentToken' => 'favourites'])
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
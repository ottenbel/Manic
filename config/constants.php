<?php
	return [
	'keys' => 
		[
				'pagination' => 
				[
					'collectionsPerPageIndex' => 'pagination_collections_per_page_index',
					'artistsPerPageIndex' => 'pagination_artists_per_page_index',
					'charactersPerPageIndex' => 'pagination_characters_per_page_index',
					'scanalatorsPerPageIndex' => 'pagination_scanalators_per_page_index',
					'seriesPerPageIndex' => 'pagination_series_per_page_index', 
					'tagsPerPageIndex' => 'pagination_tags_per_page_index',
					'artistAliasesPerPageIndex' => 'pagination_artist_aliases_per_page_index',
					'characterAliasesPerPageIndex' => 'pagination_character_aliases_per_page_index',
					'scanalatorAliasesPerPageIndex' => 'pagination_scanalator_aliases_per_page_index',
					'seriesAliasesPerPageIndex' => 'pagination_series_aliases_per_page_index',
					'tagAliasesPerPageIndex' => 'pagination_tag_aliases_per_page_index',
					'artistAliasesPerPageParent' => 'pagination_artist_aliases_per_page_parent',
					'characterAliasesPerPageParent' => 'pagination_character_aliases_per_page_parent',
					'scanalatorAliasesPerPageParent' => 'pagination_scanalator_aliases_per_page_parent',
					'seriesAliasesPerPageParent' => 'pagination_series_aliases_per_page_parent',
					'tagAliasesPerPageParent' => 'pagination_tag_aliases_per_page_parent',
					'charactersPerPageSeries' => 'pagination_characters_per_page_series'
				],
				'placeholders' =>
				[
					'artist' => 
					[
						'name' => 'artist_name',
						'shortDescription' => 'artist_short_description',
						'description' => 'artist_description',
						'source' => 'artist_source',
						'child' => 'artist_child'
					],
					'character' => 
					[
						'name' => 'character_name',
						'shortDescription' => 'character_short_description',
						'description' => 'character_description',
						'source' => 'character_source',
						'parent' => 'character_parent',
						'child' => 'character_child'
					],
					'scanalator' =>
					[
						'name' => 'scanalator_name',
						'shortDescription' => 'scanalator_short_description',
						'description' => 'scanalator_description',
						'source' => 'scanalator_source',
						'child' => 'scanalator_child'
					],
					'series' =>
					[
						'name' => 'series_name',
						'shortDescription' => 'series_short_description',
						'description' => 'series_description',
						'source' => 'series_source',
						'child' => 'series_child',
					],
					'tag' =>
					[
						'name' => 'tag_name',
						'shortDescription' => 'tag_short_description',
						'description' => 'tag_description',
						'source' => 'tag_source',
						'child' => 'tag_child',
					],
					'collection' =>
					[
						'cover' => 'collection_cover',
						'name' => 'collection_name',
						'description' => 'collection_description',
						'parent' => 'collection_parent',
						'primaryArtists' => 'collection_primary_artists',
						'secondaryArtists' => 'collection_secondary_artists',
						'primarySeries' => 'collection_primary_series',
						'secondarySeries' => 'collection_secondary_series',
						'primaryCharacters' => 'collection_primary_characters',
						'secondaryCharacters' => 'collection_secondary_characters',
						'primaryTags' => 'collection_primary_tags',
						'secondaryTags' => 'collection_secondary_tags',
						'canonical' => 'collection_canonical',
						'language' => 'collection_language',
						'rating' => 'collection_rating',
						'status' => 'collection_status'
					],
					'volume' =>
					[
						'cover' => 'volume_cover',
						'number' => 'volume_number',
						'name' => 'volume_name'
					],
					'chapter' => 
					[
						'volume' => 'chapter_volume',
						'number' => 'chapter_number',
						'name' => 'chapter_name',
						'scanalatorPrimary' => 'chapter_primary_scanalators',
						'scanalatorSecondary' => 'chapter_secondary_scanalators',
						'source' => 'chapter_source',
						'images' => 'chapter_images'
					]
				]
		],
	'sortingStringComparison' => [
									'aliasListType' => [
															'global' => 'global',
															'personal' => 'personal',
															'mixed' => 'mixed'
													   ],
									'listOrder' => [
														'ascending' => 'asc',
														'descending' => 'desc'
													],
									'tagListType' => [
														'usage' => 'usage',
														'alphabetic' => 'alphabetic'
													 ]
								 ],
	 'placeholders' => [
							'aliases' => [
											'personal' => 'Personal Alias',
											'global' => 'Global Alias'
										 ]
					   ],
		'roles' => [
						'user' => 'ddce79e6-8f67-4f18-89d2-af160f1552c5',
						'editor' => 'd0ff589b-b2a7-4402-88ae-a8b2d1e868bf',
						'administrator' => '8a5cdf00-75e9-406c-902c-594030ed0a2f',
						'owner' => '75f5c752-9e8d-4969-bb4b-0e0bac28d4b5'
				   ]
	];
?>
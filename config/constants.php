<?php
	return [
	'pagination' => [
						'collectionsPerPageIndex' => 10,
						'tagObjectsPerPageIndex' => 30,
						'tagAliasesPerPageIndex' => 30,
						'tagAliasesPerPageParent' => 10,
						'charactersPerPageSeries' => 12,
						
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
							'tagObjects' => [
												'artist' => [
																'name' => 'Artist Name',
																'shortDescription' => 'A short description of the artist.',
																'description' => 'A full description of the artist.',
																'source' => 'https://en.wikipedia.org/wiki/Artist',
																'child' => 'Child Artist 1, Child Artist 2',
																'nameHelp' => 'The name of the artist (required).',
																'shortDescriptionHelp' => 'A short description of the artist, used to populate the tooltip on the mouseover event (optional).',
																'descriptionHelp' => 'A full description of the artist (optional).',
																'sourceHelp' => 'A link for additional information about the artist (optional).',
																'childHelp' => 'Child artists of the artist (comma delimited/optional).'
															],
												'character' => [
																	'name' => 'Character Name',
																	'shortDescription' => 'A short description of the character.',
																	'description' => 'A full description of the character.',
																	'source' => 'https://en.wikipedia.org/wiki/Character_(arts)',
																	'parentSeries' => 'Parent Series',
																	'child' => 'Child Character 1, Child Character 2',
																	'nameHelp' => 'The name of the character (required).',
																	'shortDescriptionHelp' => 'A short description of the character, used to populate the tooltip on the mouseover event (optional).',
																	'descriptionHelp' => 'A full description of the character (optional).',
																	'sourceHelp' => 'A link for additional information about the character (optional).',
																	'childHelp' => 'Child characters of the character (comma delimited/optional).',
																	'parentSeriesHelp' => 'The name of the series that the character will be bound to (required).'
															   ],
												'scanalator' => [
																	'name' => 'Scanalator Name',
																	'shortDescription' => 'A short description of the scanalator.',
																	'description' => 'A full description of the scanalator.',
																	'source' => 'https://en.wikipedia.org/wiki/Translation',
																	'child' => 'Child Scanalator 1, Child Scanalator 2',
																	'nameHelp' => 'The name of the scanalator (required).',
																	'shortDescriptionHelp' => 'A short description of the scanalator, used to populate the tooltip on the mouseover event (optional).',
																	'descriptionHelp' => 'A full description of the scanalator (optional).',
																	'sourceHelp' => 'A link for additional information about the scanalator (optional).',
																	'childHelp' => 'Child scanalators of the scanalator (comma delimited/optional).'
																],
												'series' => [
																'name' => 'Series Name',
																'shortDescription' => 'A short description of the series.',
																'description' => 'A full description of the series.',
																'source' => 'https://en.wikipedia.org/wiki/Series',
																'child' => 'Child Series 1, Child Series 2',
																'nameHelp' => 'The name of the series (required).',
																'shortDescriptionHelp' => 'A short description of the series, used to populate the tooltip on the mouseover event (optional).',
																'descriptionHelp' => 'A full description of the series (optional).',
																'sourceHelp' => 'A link for additional information about the series (optional).',
																'childHelp' => 'Child series of the series (comma delimited/optional).'
															],
												'tag' => [
															'name' => 'Tag Name',
															'shortDescription' => 'A short description of the tag.',
															'description' => 'A full description of the tag.',
															'source' => 'https://en.wikipedia.org/wiki/Tag_(metadata)',
															'child' => 'Child Tag 1, Child Tag 2',
															'nameHelp' => 'The name of the tag (required).',
															'shortDescriptionHelp' => 'A short description of the tag, used to populate the tooltip on the mouseover event (optional).',
															'descriptionHelp' => 'A full description of the tag (optional).',
															'sourceHelp' => 'A link for additional information about the tag (optional).',
															'childHelp' => 'Child tags of the tag (comma delimited/optional).'
														 ]
											],
							'aliases' => [
											'personal' => 'Personal Alias',
											'global' => 'Global Alias'
										 ],
							'chapters' => [
												'number' => '1',
												'name' => 'Chapter Name',
												'primaryScanalators' => 'SuperScans, Just Another Scanalation Group, Travelling Scanalators',
												'secondaryScanalators' => 'Various Scans, Some More Scans',
												'source' => 'https://www.wikipedia.org'
										  ],
							'collections' => [
												'name' => 'Collection Name',
												'description' => 'Some text describing the collection.',
												'primaryArtists' => 'Franklin Carmichael,  Lawren Harris, A. Y. Jackson ,  Frank Johnston',
												'secondaryArtists' => 'Arthur Lismer, J. E. H. MacDonald,  Frederick Varley',
												'primarySeries' => 'Dresden Files, Harry Potter, Codex Alera',
												'secondarySeries' => 'Nexus Trilogy, Lord of the Rings, ',
												'primaryCharacters' => 'Harry Dresden, Harry Potter, Tavi',
												'secondaryCharacters' => 'Crowl, Tom Riddle, The Vord',
												'primaryTags' => 'Magic, Urban Fantasy, Politics',
												'secondaryTags' => 'Mystery, Alien Invasion, Boarding School'
											 ],
							'volumes' => [
											'number' => '1',
											'name' => 'Volume Name'
										 ],
					   
							'help' => [
								'chapters' => [
												'volume_number' => 'The volume that the chapter will be associated with (required).',
												'number' => 'The number of the chapter in the collection (required/unique to collection).',
												'name' => 'The name of the chapter (optional).',
												'scanalator_primary' => 'The main scanalation groups that worked on the chapter (comma delimited/optional).',
												'scanalator_secondary' => 'Other scanalation groups that worked on the chapter (comma delimited/optional).',
												'url' => 'The release location where the chapter was obtained (optional).',
												'images' => 'The files to be uploaded into the chapter as images or a zipped folder (required).'
											  ],
								'volumes' => [
												'cover' => 'The cover image to be displayed for the volume (optional).',
												'number' => 'The number of the volume in the collection (required/unique to collection)',
												'name' => 'The name of the volume (optional).'
											 ],
								'collections' => [
													'cover' => 'The cover image to be displayed for the collection (optional).',
													'name' => 'The name of the collection (required/unique).',
													'description' => 'A summary describing the contents of the collection (optional).',
													'parent_id' => 'Create a link between the collection to a parent collection using the unique identifier of the parent collection (optional).',
													'artist_primary' => 'The main artists that worked on the collection (comma delimited/optional).',
													'artist_secondary' => 'Secondary artists that worked on the collection (comma delimited/optional).',
													'series_primary' => "The main series associated with the collection (comma delimited/optional).",
													'series_secondary' => "Secondary series associated with the collection (comma delimited/optional).",
													'character_primary' => "The main characters associated with the collection. Characters must already exist on a series before being used and the series must exist on the collection (comma delimited/optional).",
													'character_secondary' => "Secondary characters associated with the collection. Characters must already exist on a series before being used and the series must exist on the collection (comma delimited/optional).",
													'tag_primary' => "The main tags associated with the collection (comma delimited/optional).",
													'tag_secondary' => "Secondary tags associated with the collection  (comma delimited/optional).",
													'canonical' => "Whether or not the collection is canonical or not (required).",
													'language' => "The language of the collection contents (required).",
													'ratings' => "The collection rating (required).",
													'statuses' => "The collection status (required)."
												 ]
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
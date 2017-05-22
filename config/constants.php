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
																'description' => 'A description of the artist.',
																'source' => 'https://en.wikipedia.org/wiki/Artist',
																'child' => 'Child Artist 1, Child Artist 2'
															],
												'character' => [
																	'name' => 'Character Name',
																	'description' => 'A description of the character.',
																	'source' => 'https://en.wikipedia.org/wiki/Character_(arts)',
																	'parentSeries' => 'Parent Series',
																	'child' => 'Child Character 1, Child Character 2'
															   ],
												'scanalator' => [
																	'name' => 'Scanalator Name',
																	'description' => 'A description of the scanalator.',
																	'source' => 'https://en.wikipedia.org/wiki/Translation',
																	'child' => 'Child Scanalator 1, Child Scanalator 2'
																],
												'series' => [
																'name' => 'Series Name',
																'description' => 'A description of the series.',
																'source' => 'https://en.wikipedia.org/wiki/Series',
																'child' => 'Child Series 1, Child Series 2'
															],
												'tag' => [
															'name' => 'Tag Name',
															'description' => 'A description of the tag.',
															'source' => 'https://en.wikipedia.org/wiki/Tag_(metadata)',
															'child' => 'Child Tag 1, Child Tag 2'
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
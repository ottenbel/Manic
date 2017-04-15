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
												'name' => 'Tag Object Name',
												'description' => 'Some text describing the tag object.',
												'source' => 'https://www.wikipedia.org',
												'parentSeries' => 'Dresden Files'
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
					   ]
	];
?>
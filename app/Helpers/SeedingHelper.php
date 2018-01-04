<?php

namespace App\Helpers;

use App\Models\Configuration\ConfigurationPagination;
use App\Models\Configuration\ConfigurationPlaceholder;
use App\Models\Configuration\ConfigurationRatingRestriction;
use App\Models\User;
use App\Models\Rating;

class SeedingHelper
{
	/*
	 * The function used by the database seeder to seed the pagination table for a given user.
	 */
	public static function SeedPaginationTable($seedUser = null)
	{
		$user = null;
		if ($seedUser == null)
		{  
			$user = User::first();
		}
		else
		{
			$user = $seedUser;
		}
		
		self::SeedPaginationRow($user, $seedUser, "pagination_collections_per_page_index", 10, "The number of collections to be displayed per index page.", 0);
		self::SeedPaginationRow($user, $seedUser, "pagination_artists_per_page_index", 30, "The number of artists to be displayed per index page.", 1);
		self::SeedPaginationRow($user, $seedUser, "pagination_characters_per_page_index", 30, "The number of characters to be displayed per index page.", 2);
		self::SeedPaginationRow($user, $seedUser, "pagination_scanalators_per_page_index", 30, "The number of scanalators to be displayed per index page.", 3);
		self::SeedPaginationRow($user, $seedUser, "pagination_series_per_page_index", 30, "The number of series to be displayed per index page.", 4);
		self::SeedPaginationRow($user, $seedUser, "pagination_tags_per_page_index", 30, "The number of tags to be displayed per index page.", 5);
		self::SeedPaginationRow($user, $seedUser, "pagination_artist_aliases_per_page_index", 30, "The number of artist aliases to be displayed per index page.", 6);
		self::SeedPaginationRow($user, $seedUser, "pagination_character_aliases_per_page_index", 30, "The number of character aliases to be displayed per index page.", 7);
		self::SeedPaginationRow($user, $seedUser, "pagination_scanalator_aliases_per_page_index", 30, "The number of scanalator aliases to be displayed per index page.", 8);
		self::SeedPaginationRow($user, $seedUser, "pagination_series_aliases_per_page_index", 30, "The number of series aliases to be displayed per index page.", 9);
		self::SeedPaginationRow($user, $seedUser, "pagination_tag_aliases_per_page_index", 30, "The number of tag aliases to be displayed per index page.", 10);
		self::SeedPaginationRow($user, $seedUser, "pagination_artist_aliases_per_page_parent", 10, "The number of artist aliases to be displayed per parent page.", 11);
		self::SeedPaginationRow($user, $seedUser, "pagination_character_aliases_per_page_parent", 10, "The number of character aliases to be displayed per parent page.", 12);
		self::SeedPaginationRow($user, $seedUser, "pagination_scanalator_aliases_per_page_parent", 10, "The number of scanalator aliases to be displayed per parent page.", 13);
		self::SeedPaginationRow($user, $seedUser, "pagination_series_aliases_per_page_parent", 10, "The number of series aliases to be displayed per parent page.", 14);
		self::SeedPaginationRow($user, $seedUser, "pagination_tag_aliases_per_page_parent", 10, "The number of tag aliases to be displayed per parent page.", 15);
		self::SeedPaginationRow($user, $seedUser, "pagination_characters_per_page_series", 12, "The number of tag aliases to be displayed per parent series page.", 16);
		self::SeedPaginationRow($user, $seedUser, "pagination_permissions_per_page_index", 30, "The number of permisisons to be displayed per index page.", 17);
		self::SeedPaginationRow($user, $seedUser, "pagination_roles_per_page_index", 30, "The number of permisisons to be displayed per index page.", 18);
	}
	
	/*
	 * The function used by the database seeder to seed the placeholder table for a given user.
	 */
	public static function SeedPlaceholderTable($seedUser = null)
	{
		$user = null;
		
		if ($seedUser == null)
		{  
			$user = User::first();
		}
		else
		{
			$user = $seedUser;
		}
		
		//Artists
		self::SeedPlaceholderRow($user, $seedUser, "artist_name", "Artist Name", "The name of the artist (required).", 0);
		self::SeedPlaceholderRow($user, $seedUser, "artist_short_description", "A short description of the artist.", "A short description of the artist, used to populate the tooltip on the mouseover event (optional).", 1);
		self::SeedPlaceholderRow($user, $seedUser, "artist_description", "A full description of the artist.", "A full description of the artist (optional).", 2);
		self::SeedPlaceholderRow($user, $seedUser, "artist_source", "https://en.wikipedia.org/wiki/Artist", "A link for additional information about the artist (optional).", 3);
		self::SeedPlaceholderRow($user, $seedUser, "artist_child", "Child Artist 1, Child Artist 2", "Child artists of the artist (comma delimited/optional).", 4);
		
		//Characters
		self::SeedPlaceholderRow($user, $seedUser, "character_name", "Character Name", "The name of the character (required).", 0);
		self::SeedPlaceholderRow($user, $seedUser, "character_short_description", "A short description of the character.", "A short description of the character, used to populate the tooltip on the mouseover event (optional).", 1);
		self::SeedPlaceholderRow($user, $seedUser, "character_description", "A full description of the character.", "A full description of the character (optional).", 2);
		self::SeedPlaceholderRow($user, $seedUser, "character_source", "https://en.wikipedia.org/wiki/Character_(arts)", "A link for additional information about the character (optional).", 3);
		self::SeedPlaceholderRow($user, $seedUser, "character_parent", "Parent Series", "The name of the series that the character will be bound to (required).", 4);
		self::SeedPlaceholderRow($user, $seedUser, "character_child", "Child Character 1, Child Character 2", "Child characters of the character (comma delimited/optional).", 5);
		
		//Scanalators
		self::SeedPlaceholderRow($user, $seedUser, "scanalator_name", "Scanalator Name", "The name of the scanalator (required).", 0);
		self::SeedPlaceholderRow($user, $seedUser, "scanalator_short_description", "A short description of the scanalator.", "A short description of the scanalator, used to populate the tooltip on the mouseover event (optional).", 1);
		self::SeedPlaceholderRow($user, $seedUser, "scanalator_description", "A full description of the scanalator.", "A full description of the scanalator (optional).", 2);
		self::SeedPlaceholderRow($user, $seedUser, "scanalator_source", "https://en.wikipedia.org/wiki/Translation", "A link for additional information about the scanalator (optional).", 3);
		self::SeedPlaceholderRow($user, $seedUser, "scanalator_child", "Child Scanalator 1, Child Scanalator 2", "Child scanalators of the scanalator (comma delimited/optional).", 4);
		
		//Series
		self::SeedPlaceholderRow($user, $seedUser, "series_name", "Series Name", "The name of the series (required).", 0);
		self::SeedPlaceholderRow($user, $seedUser, "series_short_description", "A short description of the series.", "A short description of the series, used to populate the tooltip on the mouseover event (optional).", 1);
		self::SeedPlaceholderRow($user, $seedUser, "series_description", "A full description of the series.", "A full description of the series (optional).", 2);
		self::SeedPlaceholderRow($user, $seedUser, "series_source", "https://en.wikipedia.org/wiki/Series", "A link for additional information about the series (optional).", 3);
		self::SeedPlaceholderRow($user, $seedUser, "series_child", "Child Series 1, Child Series 2", "Child series of the series (comma delimited/optional).", 4);
		
		//Tag
		self::SeedPlaceholderRow($user, $seedUser, "tag_name", "Tag Name", "The name of the tag (required).", 0);
		self::SeedPlaceholderRow($user, $seedUser, "tag_short_description", "A short description of the tag.", "A short description of the tag, used to populate the tooltip on the mouseover event (optional).", 1);
		self::SeedPlaceholderRow($user, $seedUser, "tag_description", "A full description of the tag.", "A full description of the tag (optional).", 2);
		self::SeedPlaceholderRow($user, $seedUser, "tag_source", "https://en.wikipedia.org/wiki/Tag_(metadata)", "A link for additional information about the tag (optional).", 3);
		self::SeedPlaceholderRow($user, $seedUser, "tag_child", "Child Tag 1, Child Tag 2", "Child tags of the tag (comma delimited/optional).", 4);
		
		//Collections
		self::SeedPlaceholderRow($user, $seedUser, "collection_cover", "", "The cover image to be displayed for the collection (optional).", 0);
		self::SeedPlaceholderRow($user, $seedUser, "collection_name", "Collection Name", "The name of the collection (required/unique).", 1);
		self::SeedPlaceholderRow($user, $seedUser, "collection_description", "Some text describing the collection.", "A summary describing the contents of the collection (optional).", 2);
		self::SeedPlaceholderRow($user, $seedUser, "collection_parent", "", "Create a link between the collection to a parent collection using the unique identifier of the parent collection (optional).", 3);
		self::SeedPlaceholderRow($user, $seedUser, "collection_primary_artists", "Franklin Carmichael,  Lawren Harris, A. Y. Jackson ,  Frank Johnston", "The main artists that worked on the collection (comma delimited/optional).", 4);
		self::SeedPlaceholderRow($user, $seedUser, "collection_secondary_artists", "Arthur Lismer, J. E. H. MacDonald,  Frederick Varley", "Secondary artists that worked on the collection (comma delimited/optional).", 5);
		self::SeedPlaceholderRow($user, $seedUser, "collection_primary_series", "Dresden Files, Harry Potter, Codex Alera", "The main series associated with the collection (comma delimited/optional).", 6);
		self::SeedPlaceholderRow($user, $seedUser, "collection_secondary_series", "Nexus Trilogy, Lord of the Rings", "Secondary series associated with the collection (comma delimited/optional).", 7);
		self::SeedPlaceholderRow($user, $seedUser, "collection_primary_characters", "Harry Dresden, Harry Potter, Tavi", "The main characters associated with the collection. Characters must already exist on a series before being used and the series must exist on the collection (comma delimited/optional).", 8);
		self::SeedPlaceholderRow($user, $seedUser, "collection_secondary_characters", "Crowl, Tom Riddle, The Vord", "Secondary characters associated with the collection. Characters must already exist on a series before being used and the series must exist on the collection (comma delimited/optional).", 9);
		self::SeedPlaceholderRow($user, $seedUser, "collection_primary_tags", "Magic, Urban Fantasy, Politics", "The main tags associated with the collection (comma delimited/optional).", 10);
		self::SeedPlaceholderRow($user, $seedUser, "collection_secondary_tags", "Mystery, Alien Invasion, Boarding School", "Secondary tags associated with the collection  (comma delimited/optional).", 11);
		self::SeedPlaceholderRow($user, $seedUser, "collection_canonical", "", "A flag denothing whether or not the collection is canonical or not (required).", 12);
		self::SeedPlaceholderRow($user, $seedUser, "collection_language", "", "The language of the collection contents (required).", 13);
		self::SeedPlaceholderRow($user, $seedUser, "collection_rating", "", "The collection rating (required).", 14);
		self::SeedPlaceholderRow($user, $seedUser, "collection_status", "", "The collection status (required).", 15);
		
		//Volume
		self::SeedPlaceholderRow($user, $seedUser, "volume_cover", "", "The cover image to be displayed for the volume (optional).", 0);
		self::SeedPlaceholderRow($user, $seedUser, "volume_number", "", "The number of the volume in the collection (required/unique to collection)", 1);
		self::SeedPlaceholderRow($user, $seedUser, "volume_name", "Volume Name", "The name of the volume (optional).", 2);
		
		//Chapter
		self::SeedPlaceholderRow($user, $seedUser, "chapter_volume", "", "The volume that the chapter will be associated with (required).", 0);
		self::SeedPlaceholderRow($user, $seedUser, "chapter_number", "1", "The number of the chapter in the collection (required/unique to collection).", 1);
		self::SeedPlaceholderRow($user, $seedUser, "chapter_name", "Chapter Name", "The name of the chapter (optional).", 2);
		self::SeedPlaceholderRow($user, $seedUser, "chapter_primary_scanalators", "SuperScans, Just Another Scanalation Group, Travelling Scanalators", "The main scanalation groups that worked on the chapter (comma delimited/optional).", 3);
		self::SeedPlaceholderRow($user, $seedUser, "chapter_secondary_scanalators", "Various Scans, Some More Scans", "Other scanalation groups that worked on the chapter (comma delimited/optional).", 4);
		self::SeedPlaceholderRow($user, $seedUser, "chapter_source", "https://www.wikipedia.org", "The release location where the chapter was obtained (optional).", 5);
		self::SeedPlaceholderRow($user, $seedUser, "chapter_images", "", "The files to be uploaded into the chapter as images or a zipped folder (required).", 6);
		
		//Permission
		self::SeedPlaceholderRow($user, $seedUser, "permission_name", "Permission Name", "The name of the permission to be created", 0);
		
		//Role
		self::SeedPlaceholderRow($user, $seedUser, "role_name", "Role Name", "The name of the role to be created", 0);
	}
	
	/*
	 * The function used by the database seeder to seed the placeholder table for a given user.
	 */
	public static function SeedRatingRestrictionTable($seedUser = null)
	{
		$user = null;
		$ratings = Rating::all();
		
		if ($seedUser == null)
		{  
			$user = User::first();
		}
		else
		{
			$user = $seedUser;
		}
		
		foreach ($ratings as $rating)
		{
			self::SeedRatingRestrictionRow($user, $seedUser, $rating->id, true, $rating->priority);
		}
	}
	
	/*
	 * The private function used to seed a pagination row for a given user.
	 */
	private static function SeedPaginationRow($user, $seedUser, $key, $value, $description, $priority)
	{
		$userID = null;
		if (!is_null($seedUser))
		{
			$userID = $seedUser->id;
		}
		
		$paginationConfiguration = ConfigurationPagination::where('user_id', '=', $userID)->where('key', '=', $key)->first();
		
		if ($paginationConfiguration == null)
		{
			$paginationConfiguration = new ConfigurationPagination();
			$paginationConfiguration->user_id = $userID;
			$paginationConfiguration->key = $key;
			
			$paginationConfiguration->fill(['value' => $value, 'description' => $description, 'priority' => $priority]);
			
			$paginationConfiguration->created_by = $user->id;
			$paginationConfiguration->updated_by = $user->id;
			
			$paginationConfiguration->save();
		}
	}
	
	/*
	 * The private function used to seed a placeholder row for a given user.
	 */
	private static function SeedPlaceholderRow($user, $seedUser, $key, $value, $description, $priority)
	{
		$userID = null;
		if (!is_null($seedUser))
		{
			$userID = $seedUser->id;
		}
		
		$placeholderConfiguration = ConfigurationPlaceholder::where('user_id', '=', $userID)->where('key', '=', $key)->first();
		if ($placeholderConfiguration == null)
		{
			$placeholderConfiguration = new ConfigurationPlaceholder();
			$placeholderConfiguration->user_id = $userID;
			$placeholderConfiguration->key = $key;
			
			$placeholderConfiguration->fill(['value' => $value, 'description' => $description, 'priority' => $priority]);
			
			$placeholderConfiguration->created_by = $user->id;
			$placeholderConfiguration->updated_by = $user->id;
			$placeholderConfiguration->save();
		}
	}
	
	/*
	 * The private function used to seed a rating restriction row for a given user.
	 */
	 private static function SeedRatingRestrictionRow($user, $seedUser, $rating_id, $display, $priority)
	 {
		$userID = null;
		if (!is_null($seedUser))
		{
			$userID = $seedUser->id;
		}
		
		$ratingRestrictionConfiguration = ConfigurationRatingRestriction::where('user_id', '=', $userID)->where('rating_id', '=', $rating_id)->first();
		
		if ($ratingRestrictionConfiguration == null)
		{
			$ratingRestrictionConfiguration = new ConfigurationRatingRestriction();
			$ratingRestrictionConfiguration->user_id = $userID;
			$ratingRestrictionConfiguration->rating_id = $rating_id;
			
			$ratingRestrictionConfiguration->fill(['display' => $display, 'priority' => $priority]);

			$ratingRestrictionConfiguration->created_by = $user->id;
			$ratingRestrictionConfiguration->updated_by = $user->id;
			$ratingRestrictionConfiguration->save();
		}
	 }
}

?>

<?php

namespace App\Helpers;

use App\Models\Configuration\ConfigurationPagination;
use App\Models\User;

class SeedingHelper
{
	public static function SeedPaginationTable($seedUser=null)
	{
		$user = null;
		$existingConfigurationSettings = null;
		if ($seedUser == null)
		{  
			$user = User::first();
		}
		else
		{
			$user = $seedUser;
			$existingConfigurationSettings = ConfigurationPagination::where('user_id','=', null);
		}
		
		self::SeedPaginationRow($user, $seedUser, $existingConfigurationSettings, "pagination_collections_per_page_index", 10, "The number of collections to be displayed per index page.", 0);
		self::SeedPaginationRow($user, $seedUser, $existingConfigurationSettings, "pagination_artists_per_page_index", 30, "The number of artists to be displayed per index page.", 1);
		self::SeedPaginationRow($user, $seedUser, $existingConfigurationSettings, "pagination_characters_per_page_index", 30, "The number of characters to be displayed per index page.", 2);
		self::SeedPaginationRow($user, $seedUser, $existingConfigurationSettings, "pagination_scanalators_per_page_index", 30, "The number of scanalators to be displayed per index page.", 3);
		self::SeedPaginationRow($user, $seedUser, $existingConfigurationSettings, "pagination_series_per_page_index", 30, "The number of series to be displayed per index page.", 4);
		self::SeedPaginationRow($user, $seedUser, $existingConfigurationSettings, "pagination_tags_per_page_index", 30, "The number of tags to be displayed per index page.", 5);
		self::SeedPaginationRow($user, $seedUser, $existingConfigurationSettings, "pagination_artist_aliases_per_page_index", 30, "The number of artist aliases to be displayed per index page.", 6);
		self::SeedPaginationRow($user, $seedUser, $existingConfigurationSettings, "pagination_character_aliases_per_page_index", 30, "The number of character aliases to be displayed per index page.", 7);
		self::SeedPaginationRow($user, $seedUser, $existingConfigurationSettings, "pagination_scanalator_aliases_per_page_index", 30, "The number of scanalator aliases to be displayed per index page.", 8);
		self::SeedPaginationRow($user, $seedUser, $existingConfigurationSettings, "pagination_series_aliases_per_page_index", 30, "The number of series aliases to be displayed per index page.", 9);
		self::SeedPaginationRow($user, $seedUser, $existingConfigurationSettings, "pagination_tag_aliases_per_page_index", 30, "The number of tag aliases to be displayed per index page.", 10);
		self::SeedPaginationRow($user, $seedUser, $existingConfigurationSettings, "pagination_artist_aliases_per_page_parent", 10, "The number of artist aliases to be displayed per parent page.", 11);
		self::SeedPaginationRow($user, $seedUser, $existingConfigurationSettings, "pagination_character_aliases_per_page_parent", 10, "The number of character aliases to be displayed per parent page.", 12);
		self::SeedPaginationRow($user, $seedUser, $existingConfigurationSettings, "pagination_scanalator_aliases_per_page_parent", 10, "The number of scanalator aliases to be displayed per parent page.", 13);
		self::SeedPaginationRow($user, $seedUser, $existingConfigurationSettings, "pagination_series_aliases_per_page_parent", 10, "The number of series aliases to be displayed per parent page.", 14);
		self::SeedPaginationRow($user, $seedUser, $existingConfigurationSettings, "pagination_tag_aliases_per_page_parent", 10, "The number of tag aliases to be displayed per parent page.", 15);
		self::SeedPaginationRow($user, $seedUser, $existingConfigurationSettings, "pagination_characters_per_page_series", 12, "The number of tag aliases to be displayed per parent series page.", 16);
	}
	
	private static function SeedPaginationRow($user, $seedUser, $existingConfigurationSettings, $key, $value, $description, $priority)
	{
		$paginationConfiguration = new ConfigurationPagination;
		if (!is_null($seedUser))
		{
			$paginationConfiguration->user_id = $seedUser->id;
		}
		else
		{
			$paginationConfiguration->user_id = null;
		}

		$paginationConfiguration->key = $key;
		
		if ((!is_null($existingConfigurationSettings))
			&& ($existingConfigurationSettings->where('key', '=', $key)->count() > 0))
		{
			$setting = $existingConfigurationSettings->where('key', '=', $key)->first();
			$paginationConfiguration->value = $setting->value;
			$paginationConfiguration->description = $setting->description;
			$paginationConfiguration->priority = $setting->priority;
		}
		else
		{
			$paginationConfiguration->value = $value;
			$paginationConfiguration->description = $description;
			$paginationConfiguration->priority = $priority;

		}
		$paginationConfiguration->created_by = $user->id;
		$paginationConfiguration->updated_by = $user->id;
		$paginationConfiguration->save();
	}
}

?>

<?php

namespace App\Helpers\Search;

use App\Models\Collection;
use App\Models\TagObjects\Artist\Artist;
use App\Models\TagObjects\Artist\ArtistAlias;
use App\Models\TagObjects\Character\Character;
use App\Models\TagObjects\Character\CharacterAlias;
use App\Models\TagObjects\Series\Series;
use App\Models\TagObjects\Series\SeriesAlias;
use App\Models\TagObjects\Tag\Tag;
use App\Models\TagObjects\Tag\TagAlias;
use App\Models\TagObjects\Scanalator\Scanalator;
use App\Models\TagObjects\Scanalator\ScanalatorAlias;
use App\Models\Language;
use App\Models\Rating;
use App\Models\Status;
use Auth;
use Config;
use LookupHelper;
use ConfigurationLookupHelper;

class SearchParseHelper
{
	/*
	 * Return the collection that matches the search string.
	 */
	public static function Search($search_string, &$collections, &$searchArtists, &$searchCharacters, &$searchScanalators, &$searchSeries, &$searchTags, &$searchLanguages, &$searchRatings, &$searchStatuses, &$searchCanonicity, &$invalid_tokens)
	{
		$searchArtists = $searchCharacters = $searchScanalators = $searchSeries = $searchTags = $searchLanguages = $searchRatings = $searchStatuses = $searchCanonicity = $invalid_tokens = array();
		
		//Break out search into tokens
		$search_tokens = array_map('trim', explode(',', $search_string));
		
		//Process and organize the tokens
		foreach ($search_tokens as $search_token)
		{
			if ($search_token == "")
			{
				continue;
			}
			
			$searchClassifier = "";
			$not = $primary = $secondary = false;
			
			//Check for - (not) flags
			if ($search_token[0] == "-")
			{
				$not = true;
				$search_token = trim(substr($search_token, 1));
			}
			
			//Check for primary: and secondary: flags
			$flag_marker = strpos($search_token, ':');
			if ($flag_marker !== false)
			{
				//Get the contents before the ":" to check for primary or secondary
				$flag = strtolower(substr($search_token, 0, $flag_marker));
				//check if the contents are primary or secondary
				if ($flag == "primary")
				{
					$primary = true;
					$search_token = trim(substr($search_token, $flag_marker + 1));
				}
				else if ($flag == "secondary")
				{
					$secondary = true;
					$search_token = trim(substr($search_token, $flag_marker + 1));
				}
			}
			
			//check for search clarifiers artist:, character:, scanalator:, series:, tag:, language:, rating:, status:, etc
			$flag_marker = strpos($search_token, ':');
			if ($flag_marker !== false)
			{
				//Get the contents before the ":" to check for search clarifiers
				$searchClassifier = strtolower(substr($search_token, 0, $flag_marker));
				$search_token = trim(substr($search_token, $flag_marker + 1));
			}
			
			//Pass the variable in by reference
			$ref = true;
			if ($searchClassifier == "artist")
			{
				self::ParseSearchTokenArtist($searchArtists, $invalid_tokens, $search_token, $primary, $secondary, $not, true, $ref);
			}
			else if ($searchClassifier == "character")
			{
				self::ParseSearchTokenCharacter($searchCharacters, $invalid_tokens, $search_token, $primary, $secondary, $not, true, $ref);
			}
			else if ($searchClassifier == "scanalator")
			{
				self::ParseSearchTokenScanalator($searchScanalators, $invalid_tokens, $search_token, $primary, $secondary, $not, true, $ref);
			}
			else if ($searchClassifier == "series")
			{
				self::ParseSearchTokenSeries($searchSeries, $invalid_tokens, $search_token, $primary, $secondary, $not, true, $ref);
			}
			else if ($searchClassifier == "tag")
			{
				self::ParseSearchTokenTag($searchTags, $invalid_tokens, $search_token, $primary, $secondary, $not, true, $ref);
			}
			else if ($searchClassifier == "language")
			{
				self::ParseSearchTokenLanguage($searchLanguages, $invalid_tokens, $search_token, $not, true, $ref);
			}
			else if ($searchClassifier == "rating")
			{
				self::ParseSearchTokenRating($searchRatings, $invalid_tokens, $search_token, $not, true, $ref);
			}
			else if ($searchClassifier == "status")
			{
				self::ParseSearchTokenStatus($searchStatuses, $invalid_tokens, $search_token, $not, true, $ref);
			}
			else
			{
				$found = false;
				
				//Search classifier not used, search for each type 
				self::ParseSearchTokenArtist($searchArtists, $invalid_tokens, $search_token, $primary, $secondary, $not, false, $found);
				self::ParseSearchTokenCharacter($searchCharacters, $invalid_tokens, $search_token, $primary, $secondary, $not, false, $found);
				self::ParseSearchTokenScanalator($searchScanalators, $invalid_tokens, $search_token, $primary, $secondary, $not, false, $found);
				self::ParseSearchTokenSeries($searchSeries, $invalid_tokens, $search_token, $primary, $secondary, $not, false, $found);
				self::ParseSearchTokenTag($searchTags, $invalid_tokens, $search_token, $primary, $secondary, $not, false, $found);
				self::ParseSearchTokenLanguage($searchLanguages, $invalid_tokens, $search_token, $not, false, $found);
				self::ParseSearchTokenRating($searchRatings, $invalid_tokens, $search_token, $not, false, $found);
				self::ParseSearchTokenStatus($searchStatuses, $invalid_tokens, $search_token, $not, false, $found);
				self::ParseSearchCanonicity($searchCanonicity, $search_token, $not, $found);
				
				if (!($found))
				{
					array_push($invalid_tokens, $search_token);
				}
			}
		}
		
		//Build the search query
		$query = $collections;
		
		$i = 0;
		foreach($searchCanonicity as $canonicity)
		{
			if ($i == 0)
			{
				if ($canonicity['not'])
				{
					$query = $query->where('canonical', '!=', $canonicity['canon']);
				}
				else
				{
					$query = $query->where('canonical', '=', $canonicity['canon']);
				}
			}
			else
			{
				if ($canonicity['not'])
				{
					$query = $query->orWhere('canonical', '!=', $canonicity['canon']);
				}
				else
				{
					$query = $query->orWhere('canonical', '=', $canonicity['canon']);
				}
			}
			$i++;
		}
		
		$i = 0;
		foreach($searchStatuses as $status)
		{
			if ($i == 0)
			{
				if ($status['not'])
				{
					$query = $query->where('status_id', '!=', $status['status']->id);
				}
				else
				{
					$query = $query->where('status_id', '=', $status['status']->id);
				}
			}
			else
			{
				if ($status['not'])
				{
					$query = $query->orWhere('status_id', '!=', $status['status']->id);
				}
				else
				{
					$query = $query->orWhere('status_id', '=', $status['status']->id);
				}
			}
			$i++;
		}
		
		$i = 0;
		foreach($searchRatings as $rating)
		{
			if ($i == 0)
			{
				if ($rating['not'])
				{
					$query = $query->where('rating_id', '!=', $rating['rating']->id);
				}
				else
				{
					$query = $query->where('rating_id', '=', $rating['rating']->id);
				}
			}
			else
			{
				if ($rating['not'])
				{
					$query = $query->orWhere('rating_id', '!=', $rating['rating']->id);
				}
				else
				{
					$query = $query->orWhere('rating_id', '=', $rating['rating']->id);
				}
			}
		}
		
		$i = 0;
		foreach($searchLanguages as $language)
		{
			if ($i == 0)
			{
				if ($language['not'])
				{
					$query = $query->where('language_id', '!=', $language['language']->id);
				}
				else
				{
					$query = $query->where('language_id', '=', $language['language']->id);
				}
			}
			else
			{
				if ($language['not'])
				{
					$query = $query->orWhere('language_id', '!=', $language['language']->id);
				}
				else
				{
					$query = $query->orWhere('language_id', '=', $language['language']->id);
				}
			}
			$i++;
		}
		
		//Check for collections that get all artists
		foreach($searchArtists as $artist)
		{
			$artistObject = $artist['artist'];
			$not = $artist['not'];
			$primary = $artist['primary'];
			$secondary = $artist['secondary'];
			$allArtists = $artistObject->descendants()->pluck('id');
			
			$allArtists->push($artistObject->id);
			
			if ($not)
			{
				$query = $query->whereDoesntHave('artists', function($query) use($allArtists, $primary, $secondary){
					if ($primary)
					{
						$query->whereIn('artists.id', $allArtists)->where('artist_collection.primary', '=', 1);
					}
					else if ($secondary)
					{
						$query->whereIn('artists.id', $allArtists)->where('artist_collection.primary', '=', 0);
					}
					else
					{
						$query->whereIn('artists.id', $allArtists);
					}
				});
			}
			else
			{
				$query = $query->whereHas('artists', function($query) use($allArtists, $primary, $secondary){
					if ($primary)
					{
						$query->whereIn('artists.id', $allArtists)->where('artist_collection.primary', '=', 1);
					}
					else if ($secondary)
					{
						$query->whereIn('artists.id', $allArtists)->where('artist_collection.primary', '=', 0);
					}
					else
					{
						$query->whereIn('artists.id', $allArtists);
					}
				});
			}
		}
		
		//Check for collections that get all characters
		foreach($searchCharacters as $character)
		{
			$characterObject = $character['character'];
			$not = $character['not'];
			$primary = $character['primary'];
			$secondary = $character['secondary'];
			$allCharacters = $characterObject->descendants()->pluck('id');
			
			$allCharacters->push($characterObject->id);
			
			if ($not)
			{
				$query = $query->whereDoesntHave('characters', function($query) use($allCharacters, $primary, $secondary){
					if ($primary)
					{
						$query->whereIn('characters.id', $allCharacters)->where('character_collection.primary', '=', 1);
					}
					else if ($secondary)
					{
						$query->whereIn('characters.id', $allCharacters)->where('character_collection.primary', '=', 0);
					}
					else 
					{
						$query->whereIn('characters.id', $allCharacters);
					}			
				});
			}
			else
			{
				$query = $query->whereHas('characters', function($query) use($allCharacters, $primary, $secondary){
					if ($primary)
					{
						$query->whereIn('characters.id', $allCharacters)->where('character_collection.primary', '=', 1);
					}
					else if ($secondary)
					{
						$query->whereIn('characters.id', $allCharacters)->where('character_collection.primary', '=', 0);
					}
					else 
					{
						$query->whereIn('characters.id', $allCharacters);
					}
				});
			}
		}
		
		//Check for collections that get all series
		foreach($searchSeries as $series)
		{
			$seriesObject = $series['series'];
			$not = $series['not'];
			$primary = $series['primary'];
			$secondary = $series['secondary'];
			$allSeries = $seriesObject->descendants()->pluck('id');
			
			$allSeries->push($seriesObject->id);
			
			if ($not)
			{
				$query = $query->whereDoesntHave('series', function($query) use($allSeries, $primary, $secondary){
					if ($primary)
					{
						$query->whereIn('series.id', $allSeries)->where('collection_series.primary', '=', 1);
					}
					else if ($secondary)
					{
						$query->whereIn('series.id', $allSeries)->where('collection_series.primary', '=', 0);
					}
					else 
					{
						$query->whereIn('series.id', $allSeries);
					}
				});
			}
			else
			{
				$query = $query->whereHas('series', function($query) use($allSeries, $primary, $secondary){
					if ($primary)
					{
						$query->whereIn('series.id', $allSeries)->where('collection_series.primary', '=', 1);
					}
					else if ($secondary)
					{
						$query->whereIn('series.id', $allSeries)->where('collection_series.primary', '=', 0);
					}
					else 
					{
						$query->whereIn('series.id', $allSeries);
					}
				});
			}
		}
		
		//Check for collections that get all tags
		foreach($searchTags as $tag)
		{
			$tagObject = $tag['tag'];
			$not = $tag['not'];
			$primary = $tag['primary'];
			$secondary = $tag['secondary'];
			$allTags = $tagObject->descendants()->pluck('id');
			
			$allTags->push($tagObject->id);
			
			if ($not)
			{
				$query = $query->whereDoesntHave('tags', function($query) use($allTags, $primary, $secondary){
					if ($primary)
					{
						$query->whereIn('tags.id', $allTags)->where('collection_tag.primary', '=', 1);
					}
					else if ($secondary)
					{
						$query->whereIn('tags.id', $allTags)->where('collection_tag.primary', '=', 0);
					}
					else 
					{
						$query->whereIn('tags.id', $allTags);
					}
				});
			}
			else
			{
				$query = $query->whereHas('tags', function($query) use($allTags, $primary, $secondary){
					if ($primary)
					{
						$query->whereIn('tags.id', $allTags)->where('collection_tag.primary', '=', 1);
					}
					else if ($secondary)
					{
						$query->whereIn('tags.id', $allTags)->where('collection_tag.primary', '=', 0);
					}
					else 
					{
						$query->whereIn('tags.id', $allTags);
					}
				});
			}
		}
		
		//Check for collections that get all scanalators
		foreach($searchScanalators as $scanalator)
		{
			$scanalatorObject = $scanalator['scanalator'];
			$not = $scanalator['not'];
			$primary = $scanalator['primary'];
			$secondary = $scanalator['secondary'];
			$allScanalators = $scanalatorObject->descendants()->pluck('id');
			
			$allScanalators->push($scanalatorObject->id);
			
			if ($not)
			{
				$query = $query->whereDoesntHave('chapters.scanalators', function($query) use($allScanalators, $primary, $secondary){
					if ($primary)
					{
						$query->whereIn('scanalators.id', $allScanalators)->where('chapter_scanalator.primary', '=', 1);
					}
					else if ($secondary)
					{
						$query->whereIn('scanalators.id', $allScanalators)->where('chapter_scanalator.primary', '=', 0);
					}
					else
					{
						$query->whereIn('scanalators.id', $allScanalators);
					}
				});
			}
			else
			{
				$query = $query->whereHas('chapters.scanalators', function($query) use($allScanalators, $primary, $secondary){
					if ($primary)
					{
						$query->whereIn('scanalators.id', $allScanalators)->where('chapter_scanalator.primary', '=', 1);
					}
					else if ($secondary)
					{
						$query->whereIn('scanalators.id', $allScanalators)->where('chapter_scanalator.primary', '=', 0);
					}
					else 
					{
						$query->whereIn('scanalators.id', $allScanalators);
					}
				});
			}
		}
		
		$lookupKey = Config::get('constants.keys.pagination.collectionsPerPageIndex');
		$paginationCollectionsPerPageIndexCount = ConfigurationLookupHelper::LookupPaginationConfiguration($lookupKey)->value;
		
		$collections = $query->orderBy('updated_at', 'desc')->paginate($paginationCollectionsPerPageIndexCount);
	}
	
	private static function ParseSearchTokenArtist(&$searchArtists, &$invalid_tokens, $search_token, $primary, $secondary, $not, $addToInvalid, &$found)
	{
		$artist = LookupHelper::GetArtistByNameOrAlias($search_token);
		if ($artist != null)
		{
			array_push($searchArtists, array('artist' => $artist, 'not' => $not, 'primary' => $primary, 'secondary' => $secondary));
			if (!($found))
			{
				$found = true;
			}
		}
		else if ($addToInvalid)
		{
			array_push($invalid_tokens, $search_token);
		}
	}
	
	private static function ParseSearchTokenCharacter(&$searchCharacters, &$invalid_tokens, $search_token, $primary, $secondary, $not, $addToInvalid, &$found)
	{
		$character = LookupHelper::GetCharacterByNameOrAlias($search_token);
		if ($character != null)
		{
			array_push($searchCharacters, array('character' => $character, 'not' => $not, 'primary' => $primary, 'secondary' => $secondary));
			if (!($found))
			{
				$found = true;
			}
		}
		else if ($addToInvalid)
		{
			array_push($invalid_tokens, $search_token);
		}
	}
	
	private static function ParseSearchTokenScanalator(&$searchScanalators, &$invalid_tokens, $search_token, $primary, $secondary, $not, $addToInvalid, &$found)
	{
		$scanalator = LookupHelper::GetScanalatorByNameOrAlias($search_token);
		if ($scanalator != null)
		{
			array_push($searchScanalators, array('scanalator' => $scanalator, 'not' => $not, 'primary' => $primary, 'secondary' => $secondary));
			if (!($found))
			{
				$found = true;
			}
		}
		else if ($addToInvalid)
		{
			array_push($invalid_tokens, $search_token);
		}
	}
	
	private static function ParseSearchTokenSeries(&$searchSeries, &$invalid_tokens, $search_token, $primary, $secondary, $not, $addToInvalid, &$found)
	{
		$series = LookupHelper::GetSeriesByNameOrAlias($search_token);
		if ($series != null)
		{
			array_push($searchSeries, array('series' => $series, 'not' => $not, 'primary' => $primary, 'secondary' => $secondary));
			if (!($found))
			{
				$found = true;
			}
		}
		else if ($addToInvalid)
		{
			array_push($invalid_tokens, $search_token);
		}
	}
	
	private static function ParseSearchTokenTag(&$searchTags, &$invalid_tokens, $search_token, $primary, $secondary, $not, $addToInvalid, &$found)
	{
		$tag = LookupHelper::GetTagByNameOrAlias($search_token);
		if ($tag != null)
		{
			array_push($searchTags, array('tag' => $tag, 'not' => $not, 'primary' => $primary, 'secondary' => $secondary));
			if (!($found))
			{
				$found = true;
			}
		}
		else if ($addToInvalid)
		{
			array_push($invalid_tokens, $search_token);
		}
	}
	
	private static function ParseSearchTokenLanguage(&$searchLanguages, &$invalid_tokens, $search_token, $not, $addToInvalid, &$found)
	{
		$language = Language::where('name', '=', $search_token)->first();
		
		if ($language != null)
		{
			array_push($searchLanguages, array('language' => $language, 'not' => $not));
			if (!($found))
			{
				$found = true;
			}
		}
		else if ($addToInvalid)
		{
			array_push($invalid_tokens, $search_token);
		}
	}
	
	private static function ParseSearchTokenRating(&$searchRatings, &$invalid_tokens, $search_token, $not, $addToInvalid, &$found)
	{
		$rating = Rating::where('name', '=', $search_token)->first();
				
		if ($rating != null)
		{
			array_push($searchRatings, array('rating' => $rating, 'not' => $not));
			if (!($found))
			{
				$found = true;
			}
		}
		else
		{
			if ($addToInvalid)
			{
				array_push($invalid_tokens, $search_token);
				if (!($found))
				{
					$found = true;
				}
			}
		}
	}
	
	private static function ParseSearchTokenStatus(&$searchStatuses, &$invalid_tokens, $search_token, $not, $addToInvalid, &$found)
	{
		$status = Status::where('name', '=', $search_token)->first();
				
		if ($status != null)
		{
			array_push($searchStatuses, array('status' => $status, 'not' => $not));
			if (!($found))
			{
				$found = true;
			}
		}
		else
		{
			if ($addToInvalid)
			{
				array_push($invalid_tokens, $search_token);
			}
		}
	}
	
	private static function ParseSearchCanonicity(&$searchCanonicity, $search_token, $not, &$found)
	{
		if (strtolower($search_token) == "canonical")
		{
			array_push($searchCanonicity, array('canon' => true, 'not' => $not));
			if (!($found))
			{
				$found = true;
			}
		}
		else if (strtolower($search_token) == "non-canonical")
		{
			array_push($searchCanonicity, array('canon' => false, 'not' => $not));
			if (!($found))
			{
				$found = true;
			}
		}
	}
}
?>
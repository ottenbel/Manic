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
	public static function Search($searchString, &$collections, &$searchArtists, &$searchCharacters, &$searchScanalators, &$searchSeries, &$searchTags, &$searchLanguages, &$searchRatings, &$searchStatuses, &$searchCanonicity, &$invalidTokens)
	{
		$searchArtists = $searchCharacters = $searchScanalators = $searchSeries = $searchTags = $searchLanguages = $searchRatings = $searchStatuses = $searchCanonicity = $invalidTokens = array();
		
		//Break out search into tokens
		$searchTokens = array_map('trim', explode(',', $searchString));
		
		//Process and organize the tokens
		foreach ($searchTokens as $searchToken)
		{
			if ($searchToken == "")
			{
				continue;
			}
			
			$searchClassifier = "";
			$not = $primary = $secondary = false;
			
			//Check for - (not) flags
			if ($searchToken[0] == "-")
			{
				$not = true;
				$searchToken = trim(substr($searchToken, 1));
			}
			
			//Check for primary: and secondary: flags
			$flagMarker = strpos($searchToken, ':');
			if ($flagMarker !== false)
			{
				//Get the contents before the ":" to check for primary or secondary
				$flag = strtolower(substr($searchToken, 0, $flagMarker));
				//check if the contents are primary or secondary
				if ($flag == "primary")
				{
					$primary = true;
					$searchToken = trim(substr($searchToken, $flagMarker + 1));
				}
				else if ($flag == "secondary")
				{
					$secondary = true;
					$searchToken = trim(substr($searchToken, $flagMarker + 1));
				}
			}
			
			//check for search clarifiers artist:, character:, scanalator:, series:, tag:, language:, rating:, status:, etc
			$flagMarker = strpos($searchToken, ':');
			if ($flagMarker !== false)
			{
				//Get the contents before the ":" to check for search clarifiers
				$searchClassifier = strtolower(substr($searchToken, 0, $flagMarker));
				$searchToken = trim(substr($searchToken, $flagMarker + 1));
			}
			
			//Pass the variable in by reference
			$ref = true;
			if ($searchClassifier == "artist")
			{
				self::ParseSearchTokenArtist($searchArtists, $invalidTokens, $searchToken, $primary, $secondary, $not, true, $ref);
			}
			else if ($searchClassifier == "character")
			{
				self::ParseSearchTokenCharacter($searchCharacters, $invalidTokens, $searchToken, $primary, $secondary, $not, true, $ref);
			}
			else if ($searchClassifier == "scanalator")
			{
				self::ParseSearchTokenScanalator($searchScanalators, $invalidTokens, $searchToken, $primary, $secondary, $not, true, $ref);
			}
			else if ($searchClassifier == "series")
			{
				self::ParseSearchTokenSeries($searchSeries, $invalidTokens, $searchToken, $primary, $secondary, $not, true, $ref);
			}
			else if ($searchClassifier == "tag")
			{
				self::ParseSearchTokenTag($searchTags, $invalidTokens, $searchToken, $primary, $secondary, $not, true, $ref);
			}
			else if ($searchClassifier == "language")
			{
				self::ParseSearchTokenLanguage($searchLanguages, $invalidTokens, $searchToken, $not, true, $ref);
			}
			else if ($searchClassifier == "rating")
			{
				self::ParseSearchTokenRating($searchRatings, $invalidTokens, $searchToken, $not, true, $ref);
			}
			else if ($searchClassifier == "status")
			{
				self::ParseSearchTokenStatus($searchStatuses, $invalidTokens, $searchToken, $not, true, $ref);
			}
			else
			{
				$found = false;
				
				//Search classifier not used, search for each type 
				self::ParseSearchTokenArtist($searchArtists, $invalidTokens, $searchToken, $primary, $secondary, $not, false, $found);
				self::ParseSearchTokenCharacter($searchCharacters, $invalidTokens, $searchToken, $primary, $secondary, $not, false, $found);
				self::ParseSearchTokenScanalator($searchScanalators, $invalidTokens, $searchToken, $primary, $secondary, $not, false, $found);
				self::ParseSearchTokenSeries($searchSeries, $invalidTokens, $searchToken, $primary, $secondary, $not, false, $found);
				self::ParseSearchTokenTag($searchTags, $invalidTokens, $searchToken, $primary, $secondary, $not, false, $found);
				self::ParseSearchTokenLanguage($searchLanguages, $invalidTokens, $searchToken, $not, false, $found);
				self::ParseSearchTokenRating($searchRatings, $invalidTokens, $searchToken, $not, false, $found);
				self::ParseSearchTokenStatus($searchStatuses, $invalidTokens, $searchToken, $not, false, $found);
				self::ParseSearchCanonicity($searchCanonicity, $searchToken, $not, $found);
				
				if (!($found))
				{
					array_push($invalidTokens, $searchToken);
				}
			}
		}
		
		//Build the search query
		$query = $collections;
		
		$i = 0;
		foreach($searchCanonicity as $canonicity)
		{
			$compareBy = '';
			if ($canonicity['not']) {$compareBy = '!=';}
			else {$compareBy = '=';}
			
			if ($i == 0) { $query = $query->where('canonical', $compareBy, $canonicity['canon']); }
			else { $query = $query->orWhere('canonical', $compareBy, $canonicity['canon']); }
			$i++;
		}
		
		self::AppendSeperatePropertyToQuery($query, $searchStatuses, 'status_id', 'status');
		self::AppendSeperatePropertyToQuery($query, $searchRatings, 'rating_id', 'rating');
		self::AppendSeperatePropertyToQuery($query, $searchLanguages, 'language_id', 'language');
		
		self::AppendTagObjectToQuery($query, $searchArtists, 'artist', 'artists', 'artists', 'artist_collection');
		self::AppendTagObjectToQuery($query, $searchCharacters, 'character', 'characters', 'characters', 'character_collection');
		self::AppendTagObjectToQuery($query, $searchSeries, 'series', 'series', 'series', 'collection_series');
		self::AppendTagObjectToQuery($query, $searchTags, 'tag', 'tags', 'tags', 'collection_tag');
		self::AppendTagObjectToQuery($query, $searchScanalators, 'scanalator', 'chapters.scanalators', 'scanalators', 'chapter_scanalator');
		
		$lookupKey = Config::get('constants.keys.pagination.collectionsPerPageIndex');
		$paginationCollectionsPerPageIndexCount = ConfigurationLookupHelper::LookupPaginationConfiguration($lookupKey)->value;
		
		$collections = $query->orderBy('updated_at', 'desc')->paginate($paginationCollectionsPerPageIndexCount);
	}
	
	private static function ParseSearchTokenArtist(&$searchArtists, &$invalidTokens, $searchToken, $primary, $secondary, $not, $addToInvalid, &$found)
	{
		$artist = LookupHelper::GetArtistByNameOrAlias($searchToken);
		self::ParseSearchTagObjectToken($searchArtists, $invalidTokens, $searchToken, $primary, $secondary, $not, $addToInvalid, $found, $artist, 'artist');
	}
	
	private static function ParseSearchTokenCharacter(&$searchCharacters, &$invalidTokens, $searchToken, $primary, $secondary, $not, $addToInvalid, &$found)
	{
		$character = LookupHelper::GetCharacterByNameOrAlias($searchToken);
		self::ParseSearchTagObjectToken($searchCharacters, $invalidTokens, $searchToken, $primary, $secondary, $not, $addToInvalid, $found, $character, 'character');
	}
	
	private static function ParseSearchTokenScanalator(&$searchScanalators, &$invalidTokens, $searchToken, $primary, $secondary, $not, $addToInvalid, &$found)
	{
		$scanalator = LookupHelper::GetScanalatorByNameOrAlias($searchToken);
		self::ParseSearchTagObjectToken($searchScanalators, $invalidTokens, $searchToken, $primary, $secondary, $not, $addToInvalid, $found, $scanalator, 'scanalator');
	}
	
	private static function ParseSearchTokenSeries(&$searchSeries, &$invalidTokens, $searchToken, $primary, $secondary, $not, $addToInvalid, &$found)
	{
		$series = LookupHelper::GetSeriesByNameOrAlias($searchToken);
		self::ParseSearchTagObjectToken($searchSeries, $invalidTokens, $searchToken, $primary, $secondary, $not, $addToInvalid, $found, $series, 'series');
	}
	
	private static function ParseSearchTokenTag(&$searchTags, &$invalidTokens, $searchToken, $primary, $secondary, $not, $addToInvalid, &$found)
	{
		$tag = LookupHelper::GetTagByNameOrAlias($searchToken);
		self::ParseSearchTagObjectToken($searchTags, $invalidTokens, $searchToken, $primary, $secondary, $not, $addToInvalid, $found, $tag, 'tag');
	}
	
	private static function ParseSearchTokenLanguage(&$searchLanguages, &$invalidTokens, $searchToken, $not, $addToInvalid, &$found)
	{
		$language = Language::where('name', '=', $searchToken)->first();
		self::ParseSearchPropertyToken($searchLanguages, $invalidTokens, $searchToken, $not, $addToInvalid, $found, $language, 'language');
	}
	
	private static function ParseSearchTokenRating(&$searchRatings, &$invalidTokens, $searchToken, $not, $addToInvalid, &$found)
	{
		$rating = Rating::where('name', '=', $searchToken)->first();
		self::ParseSearchPropertyToken($searchRatings, $invalidTokens, $searchToken, $not, $addToInvalid, $found, $rating, 'rating');
	}
	
	private static function ParseSearchTokenStatus(&$searchStatuses, &$invalidTokens, $searchToken, $not, $addToInvalid, &$found)
	{
		$status = Status::where('name', '=', $searchToken)->first();
		self::ParseSearchPropertyToken($searchStatuses, $invalidTokens, $searchToken, $not, $addToInvalid, $found, $status, 'status');
	}
	
	private static function ParseSearchPropertyToken(&$properties, &$invalidTokens, $searchToken, $not, $addToInvalid, &$found, $property, $propertyName)
	{
		if ($property != null)
		{
			array_push($properties, array($propertyName => $property, 'not' => $not));
			if (!($found)) { $found = true; }
		}
		else if ($addToInvalid) { array_push($invalidTokens, $searchToken); }
	}
	
	private static function ParseSearchTagObjectToken(&$searchTags, &$invalidTokens, $searchToken, $primary, $secondary, $not, $addToInvalid, &$found, $property, $propertyName)
	{
		if ($property != null)
		{
			array_push($searchTags, array($propertyName => $property, 'not' => $not, 'primary' => $primary, 'secondary' => $secondary));
			if (!($found)) { $found = true; }
		}
		else if ($addToInvalid) { array_push($invalidTokens, $searchToken); }
	}
	
	private static function ParseSearchCanonicity(&$searchCanonicity, $searchToken, $not, &$found)
	{
		$searchToken = strtolower($searchToken);
		if ($searchToken == "canonical")
		{
			array_push($searchCanonicity, array('canon' => true, 'not' => $not));
			if (!($found)) { $found = true; }
		}
		else if ($searchToken == "non-canonical")
		{
			array_push($searchCanonicity, array('canon' => false, 'not' => $not));
			if (!($found)) { $found = true; }
		}
	}
	
	private static function AppendSeperatePropertyToQuery(&$query, $properties, $id, $arrayField)
	{
		$i = 0;
		foreach($properties as $property)
		{
			$compareBy = '';
			if ($property['not']) { $compareBy = '!='; }
			else { $compareBy = '='; }
			
			if ($i == 0) { $query = $query->where($id, $compareBy, $property[$arrayField]->id); }
			else { $query = $query->orWhere($id, $compareBy, $property[$arrayField]->id); }
			$i++;
		}
	}
	
	private static function AppendTagObjectToQuery(&$query, $tagObjects, $tagName, $tableNameOuter, $tableNameInner, $pivotTableName)
	{
		foreach($tagObjects as $tag)
		{
			$tagObject = $tag[$tagName];
			$not = $tag['not'];
			$primary = $tag['primary'];
			$secondary = $tag['secondary'];
			$allObjects = $tagObject->descendants()->pluck('id');
			
			$allObjects->push($tagObject->id);
			
			if ($not)
			{
				$query = $query->whereDoesntHave($tableNameOuter, function($query) use($allObjects, $primary, $secondary, $tableNameInner, $pivotTableName){
					self::AppendTagObjectToQueryInner($query, $allObjects, $primary, $secondary, $tableNameInner,  $pivotTableName);
				});
			}
			else
			{
				$query = $query->whereHas($tableNameOuter, function($query) use($allObjects, $primary, $secondary, $tableNameInner, $pivotTableName){
					self::AppendTagObjectToQueryInner($query, $allObjects, $primary, $secondary, $tableNameInner, $pivotTableName);
				});
			}
		}
	}
	
	private static function AppendTagObjectToQueryInner(&$query, $allObjects, $primary, $secondary, $tableName, $pivotTableName)
	{
		if ($primary)
			{ $query->whereIn($tableName.'.id', $allObjects)->where($pivotTableName.'.primary', '=', 1); }
		else if ($secondary)
			{ $query->whereIn($tableName.'.id', $allObjects)->where($pivotTableName.'.primary', '=', 0); }
		else
			{ $query->whereIn($tableName.'.id', $allObjects); }
	}
}
?>
<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\TagObjects\Artist\Artist;
use App\Models\TagObjects\Artist\ArtistAlias;
use App\Models\TagObjects\Character\Character;
use App\Models\TagObjects\Character\CharacterAlias;
use App\Models\TagObjects\Series\Series;
use App\Models\TagObjects\Series\SeriesAlias;
use App\Models\TagObjects\Scanalator\Scanalator;
use App\Models\TagObjects\Scanalator\ScanalatorAlias;
use App\Models\TagObjects\Tag\Tag;
use App\Models\TagObjects\Tag\TagAlias;
use App\Models\Language;
use App\Models\Status;
use App\Models\Rating;
use Illuminate\Http\Request;
use DB;
use Input;
use SearchLookupHelper;

class GenericSearchAPIController extends Controller
{
	/*
	 * Internal search API (find artists by name).
	 */
    public function SearchByName(Request $request)
	{
		$searchString = trim(Input::get('searchString'));
		$negative = false;
		$primary = false;
		$secondary = false;
		$searchClassifier = null;
		
		//Pull the negative out
		if ($searchString[0] == "-")
		{
			$negative = true;
			$searchString = trim(substr($searchString, 1));
		}
		
		//Pull primary and secondary out
		$flag_marker = strpos($searchString, ':');
		if ($flag_marker !== false)
		{
			$flag = strtolower(substr($searchString, 0, $flag_marker));
			
			if ($flag == "primary")
			{
				$primary = true;
				$searchString = trim(substr($searchString, $flag_marker + 1));
			}
			else if ($flag == "secondary")
			{
				$secondary = true;
				$searchString = trim(substr($searchString, $flag_marker + 1));
			}
		}
			
		//Pull targeting out
		$flag_marker = strpos($searchString, ':');
		if ($flag_marker !== false)
		{
			//Get the contents before the ":" to check for search clarifiers
			$searchClassifier = strtolower(substr($searchString, 0, $flag_marker));
			$searchString = trim(substr($searchString, $flag_marker + 1));
		}
		
		$values = array();
		if ($searchClassifier == "artist")
		{
			$values = SearchLookupHelper::ArtistLookupHelper($searchString);
			foreach ($values as $value)
			{
				$returnString = self::BuildTypeAheadString($value['value'], "artist:", $primary, $secondary, $negative);
				
				$value['value'] = $returnString;
				$value['label'] = $returnString;
			}
		}
		else if ($searchClassifier == "character")
		{
			$values = SearchLookupHelper::CharacterLookupHelper($searchString);
			foreach ($values as $value)
			{
				$returnString = self::BuildTypeAheadString($value['value'], "character:", $primary, $secondary, $negative);
				
				$value['value'] = $returnString;
				$value['label'] = $returnString;
			}
		}
		else if ($searchClassifier == "scanalator")
		{
			$values = SearchLookupHelper::ScanalatorLookupHelper($searchString);
			foreach ($values as $value)
			{
				$returnString = self::BuildTypeAheadString($value['value'], "scanalator:", $primary, $secondary, $negative);
				
				$value['value'] = $returnString;
				$value['label'] = $returnString;
			}
		}
		else if ($searchClassifier == "series")
		{
			$values = SearchLookupHelper::SeriesLookupHelper($searchString);
			foreach ($values as $value)
			{
				$returnString = self::BuildTypeAheadString($value['value'], "series:", $primary, $secondary, $negative);
				
				$value['value'] = $returnString;
				$value['label'] = $returnString;
			}
		}
		else if ($searchClassifier == "tag")
		{
			$values = SearchLookupHelper::TagLookupHelper($searchString);
			foreach ($values as $value)
			{
				$returnString = self::BuildTypeAheadString($value['value'], "tag:", $primary, $secondary, $negative);
				
				$value['value'] = $returnString;
				$value['label'] = $returnString;
			}
		}
		else if ($searchClassifier == "language")
		{
			$languages = Language::where('languages.name', 'like', '%' . $searchString . '%')->leftjoin('collections', 'languages.id', '=', 'collections.language_id')->select('languages.*', DB::raw('count(*) as total'))->groupBy('id')->orderBy('total', 'desc')->take(5)->pluck('name');
			
			foreach ($languages as $language)
			{
				$returnString = self::BuildTypeAheadString($language, "language:", false, false, $negative);
				array_push($values, ['value' => $returnString, 'label' => $returnString]);
			}
		}
		else if ($searchClassifier == "rating")
		{
			$ratings = Rating::where('ratings.name', 'like', '%' . $searchString . '%')->leftjoin('collections', 'ratings.id', '=', 'collections.rating_id')->select('ratings.*', DB::raw('count(*) as total'))->groupBy('id')->orderBy('total', 'desc')->take(5)->pluck('name');
			
			foreach ($ratings as $rating)
			{
				$returnString = self::BuildTypeAheadString($rating, "rating:", false, false, $negative);
				array_push($values, ['value' => $returnString, 'label' => $returnString]);
			}
		}
		else if ($searchClassifier == "status")
		{
			$statuses = Status::where('statuses.name', 'like', '%' . $searchString . '%')->leftjoin('collections', 'statuses.id', '=', 'collections.status_id')->select('statuses.*', DB::raw('count(*) as total'))->groupBy('id')->orderBy('total', 'desc')->take(5)->pluck('name');
			
			foreach ($statuses as $status)
			{
				$returnString = self::BuildTypeAheadString($status, "status:", false, false, $negative);
				array_push($values, ['value' => $returnString, 'label' => $returnString]);
			}
		}
		else 
		{	
			//Get artists with total
			$artists = Artist::where('artists.name', 'like', '%' . $searchString . '%')->leftjoin('artist_collection', 'artists.id', '=', 'artist_collection.artist_id')->select('artists.*', DB::raw('count(*) as total'))->groupBy('name')->orderBy('total', 'desc')->take(5)->get();
			
			$artists = $artists->map(function ($item) use ($primary, $secondary, $negative){
				$buildString = self::BuildTypeAheadString($item->name, "artist:", $primary, $secondary, $negative);
				return ['name' => $buildString, 'total' => $item->total];
			});
			
			//Get global artist aliases with total
			$global_artist_aliases = ArtistAlias::where('user_id', '=', null)->where('alias', 'like', '%' . $searchString . '%')->leftjoin('artists', 'artists.id', '=', 'artist_alias.artist_id')->leftjoin('artist_collection', 'artists.id', '=', 'artist_collection.artist_id')->select('artist_alias.*', DB::raw('count(*) as total'))->groupBy('id')->orderBy('total', 'desc')->take(5)->get();
			
			$global_artist_aliases = $global_artist_aliases->map(function ($item) use ($primary, $secondary, $negative){
				$buildString = self::BuildTypeAheadString($item->alias, "artist:", $primary, $secondary, $negative);
				return ['name' => $buildString, 'total' => $item->total];
			});
			
			//Get characters with total
			$characters = Character::where('characters.name', 'like', '%' . $searchString . '%')->leftjoin('character_collection', 'characters.id', '=', 'character_collection.character_id')->select('characters.*', DB::raw('count(*) as total'))->groupBy('name')->orderBy('total', 'desc')->take(5)->get();
			
			$characters = $characters->map(function ($item) use ($primary, $secondary, $negative){
				$buildString = self::BuildTypeAheadString($item->name, "character:", $primary, $secondary, $negative);
				return ['name' => $buildString, 'total' => $item->total];
			});
		
			//Get global character aliases with total
			$global_character_aliases = CharacterAlias::where('user_id', '=', null)->where('alias', 'like', '%' . $searchString . '%')->leftjoin('characters', 'characters.id', '=', 'character_alias.character_id')->leftjoin('character_collection', 'characters.id', '=', 'character_collection.character_id')->select('character_alias.*', DB::raw('count(*) as total'))->groupBy('id')->orderBy('total', 'desc')->take(5)->get();
		
			$global_character_aliases = $global_character_aliases->map(function ($item) use ($primary, $secondary, $negative){
				$buildString = self::BuildTypeAheadString($item->alias, "character:", $primary, $secondary, $negative);
				return ['name' => $buildString, 'total' => $item->total];
			});
		
			//Get scanalators with total
			$scanalators = Scanalator::where('scanalators.name', 'like', '%' . $searchString . '%')->leftjoin('chapter_scanalator', 'scanalators.id', '=', 'chapter_scanalator.scanalator_id')->select('scanalators.*', DB::raw('count(*) as total'))->groupBy('name')->orderBy('total', 'desc')->take(5)->get();
		
			$scanalators = $scanalators->map(function ($item) use ($primary, $secondary, $negative){
				$buildString = self::BuildTypeAheadString($item->name, "scanalator:", $primary, $secondary, $negative);
				return ['name' => $buildString, 'total' => $item->total];
			});
			
			//Get global scanalator aliases with total
			$global_scanalator_aliases = ScanalatorAlias::where('user_id', '=', null)->where('alias', 'like', '%' . $searchString . '%')->leftjoin('scanalators', 'scanalators.id', '=', 'scanalator_alias.scanalator_id')->leftjoin('chapter_scanalator', 'scanalators.id', '=', 'chapter_scanalator.scanalator_id')->select('scanalator_alias.*', DB::raw('count(*) as total'))->groupBy('id')->orderBy('total', 'desc')->take(5)->get();
			
			$global_scanalator_aliases = $global_scanalator_aliases->map(function ($item) use ($primary, $secondary, $negative){
				$buildString = self::BuildTypeAheadString($item->alias, "scanalator:", $primary, $secondary, $negative);
				return ['name' => $buildString, 'total' => $item->total];
			});
			
			//Get series with total
			$series = Series::where('series.name', 'like', '%' . $searchString . '%')->leftjoin('collection_series', 'series.id', '=', 'collection_series.series_id')->select('series.*', DB::raw('count(*) as total'))->groupBy('name')->orderBy('total', 'desc')->take(5)->get();
		
			$series = $series->map(function ($item) use ($primary, $secondary, $negative){
				$buildString = self::BuildTypeAheadString($item->name, "series:", $primary, $secondary, $negative);
				return ['name' => $buildString, 'total' => $item->total];
			});
			
			//Get global series aliases with total
			$global_series_aliases = SeriesAlias::where('user_id', '=', null)->where('alias', 'like', '%' . $searchString . '%')->leftjoin('series', 'series.id', '=', 'series_alias.series_id')->leftjoin('collection_series', 'series.id', '=', 'collection_series.series_id')->select('series_alias.*', DB::raw('count(*) as total'))->groupBy('id')->orderBy('total', 'desc')->take(5)->get();
			
			$global_series_aliases = $global_series_aliases->map(function ($item) use ($primary, $secondary, $negative){
				$buildString = self::BuildTypeAheadString($item->alias, "series:", $primary, $secondary, $negative);
				return ['name' => $buildString, 'total' => $item->total];
			});

			//Get global tags with total
			$tags = Tag::where('tags.name', 'like', '%' . $searchString . '%')->leftjoin('collection_tag', 'tags.id', '=', 'collection_tag.tag_id')->select('tags.*', DB::raw('count(*) as total'))->groupBy('name')->orderBy('total', 'desc')->take(5)->get();
		
			$tags = $tags->map(function ($item) use ($primary, $secondary, $negative){
				$buildString = self::BuildTypeAheadString($item->name, "tag:", $primary, $secondary, $negative);
				return ['name' => $buildString, 'total' => $item->total];
			});
			
			//Get global tag aliases with total
			$global_tag_aliases = TagAlias::where('user_id', '=', null)->where('alias', 'like', '%' . $searchString . '%')->leftjoin('tags', 'tags.id', '=', 'tag_alias.tag_id')->leftjoin('collection_tag', 'tags.id', '=', 'collection_tag.tag_id')->select('tag_alias.*', DB::raw('count(*) as total'))->groupBy('id')->orderBy('total', 'desc')->take(5)->get();
			
			$global_tag_aliases = $global_tag_aliases->map(function ($item) use ($primary, $secondary, $negative){
				$buildString = self::BuildTypeAheadString($item->alias, "tag:", $primary, $secondary, $negative);
				return ['name' => $buildString, 'total' => $item->total];
			});
			
			$languages = Language::where('languages.name', 'like', '%' . $searchString . '%')->leftjoin('collections', 'languages.id', '=', 'collections.language_id')->select('languages.*', DB::raw('count(*) as total'))->groupBy('id')->orderBy('total', 'desc')->take(5)->get();
			
			$languages = $languages->map(function ($item) use ($negative){
				$buildString = self::BuildTypeAheadString($item->name, "language:", false, false, $negative);
				return ['name' => $buildString, 'total' => $item->total];
			});
			
			$ratings = Rating::where('ratings.name', 'like', '%' . $searchString . '%')->leftjoin('collections', 'ratings.id', '=', 'collections.rating_id')->select('ratings.*', DB::raw('count(*) as total'))->groupBy('id')->orderBy('total', 'desc')->take(5)->get();
			
			$ratings = $ratings->map(function ($item) use ($negative){
				$buildString = self::BuildTypeAheadString($item->name, "rating:", false, false, $negative);
				return ['name' => $buildString, 'total' => $item->total];
			});
			
			$statuses = Status::where('statuses.name', 'like', '%' . $searchString . '%')->leftjoin('collections', 'statuses.id', '=', 'collections.status_id')->select('statuses.*', DB::raw('count(*) as total'))->groupBy('id')->orderBy('total', 'desc')->take(5)->get();
			
			$statuses = $statuses->map(function ($item) use ($negative){
				$buildString = self::BuildTypeAheadString($item->name, "status:", false, false, $negative);
				return ['name' => $buildString, 'total' => $item->total];
			});
			
			$matches = collect();
			$matches->push($artists);
			$matches->push($global_artist_aliases);
			$matches->push($characters);
			$matches->push($global_character_aliases);
			$matches->push($scanalators);
			$matches->push($global_scanalator_aliases);
			$matches->push($series);
			$matches->push($global_series_aliases);
			$matches->push($tags);
			$matches->push($global_tag_aliases);
			$matches->push($languages);
			$matches->push($ratings);
			$matches->push($statuses);			
		
			$matches = $matches->flatten(1);
			$matches = $matches->sortByDesc('total');
			$typeAheadCollection = $matches->take(5)->pluck('name');
		
			$typeAheadCollection = $typeAheadCollection->sort();
			
			foreach ($typeAheadCollection as $typeAhead)
			{
				array_push($values, ['value' => $typeAhead, 'label' => $typeAhead]);
			}	 
		}
		
		return $values;
	}
	
	private static function BuildTypeAheadString($value, $type, $primary, $secondary, $negative)
	{
		$returnString = $type . $value;
		if ($primary)
		{
			$returnString = "primary:" . $returnString;
		}
		else if ($secondary)
		{
			$returnString = "secondary:" . $returnString;
		}
		if ($negative)
		{
			$returnString = "-" . $returnString;
		}
		
		return $returnString;
	}
}

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
use DB;

class SearchLookupHelper
{
	public static function ArtistLookupHelper($searchString)
	{	
		//Get artists with total
		$artists = Artist::where('name', 'like', '%' . $searchString . '%')->leftjoin('artist_collection', 'artists.id', '=', 'artist_collection.artist_id')->select('artists.*', DB::raw('count(*) as total'))->groupBy('name')->orderBy('total', 'desc')->take(5)->get();
		
		$artists = $artists->map(function ($item){
			return ['name' => $item->name, 'total' => $item->total];
		});
	
		//Add personal aliases to pluck list if tags don't exit ('figure out how this works for an API call)
	
		//Get global aliases with total
		$global_aliases = ArtistAlias::where('user_id', '=', null)->where('alias', 'like', '%' . $searchString . '%')->leftjoin('artists', 'artists.id', '=', 'artist_alias.artist_id')->leftjoin('artist_collection', 'artists.id', '=', 'artist_collection.artist_id')->select('artist_alias.*', DB::raw('count(*) as total'))->groupBy('id')->orderBy('total', 'desc')->take(5)->get();
		
		$global_aliases = $global_aliases->map(function ($item){
			return ['name' => $item->alias, 'total' => $item->total];
		});
		
		//Combine and return based on usage
		$matches = collect();
		$matches->push($artists);
		$matches->push($global_aliases);
		$matches = $matches->flatten(1);
		$matches = $matches->sortByDesc('total');
		$artists = $matches->take(5)->pluck('name');
		
		$artists = $artists->sort();
		
		$artistList = array();
		foreach ($artists as $artist)
		{
			array_push($artistList, ['value' => $artist, 'label' => $artist]);
		}
		
		return $artistList;
	}
	
	public static function CharacterLookupHelper($searchString)
	{
		$characters = Character::where('name', 'like', '%' . $searchString . '%')->leftjoin('character_collection', 'characters.id', '=', 'character_collection.character_id')->select('characters.*', DB::raw('count(*) as total'))->groupBy('name')->orderBy('total', 'desc')->take(5)->get();
		
		$characters = $characters->map(function ($item){
			return ['name' => $item->name, 'total' => $item->total];
		});
		
		//Add personal aliases to pluck list if tags don't exit ('figure out how this works for an API call)
		
		//Get global aliases with total
		$global_aliases = CharacterAlias::where('user_id', '=', null)->where('alias', 'like', '%' . $searchString . '%')->leftjoin('characters', 'characters.id', '=', 'character_alias.character_id')->leftjoin('character_collection', 'characters.id', '=', 'character_collection.character_id')->select('character_alias.*', DB::raw('count(*) as total'))->groupBy('id')->orderBy('total', 'desc')->take(5)->get();
		
		$global_aliases = $global_aliases->map(function ($item){
			return ['name' => $item->alias, 'total' => $item->total];
		});
		
		//Combine and return based on usage
		$matches = collect();
		$matches->push($characters);
		$matches->push($global_aliases);
		$matches = $matches->flatten(1);
		$matches = $matches->sortByDesc('total');
		$characters = $matches->take(5)->pluck('name');
		
		$characters = $characters->sort();
		
		$characterList = array();
		foreach ($characters as $character)
		{
			array_push($characterList, ['value' => $character, 'label' => $character]);
		}
		
		return $characterList;
	}
	
	public static function ScanalatorLookupHelper($searchString)
	{
		$scanalators = Scanalator::where('name', 'like', '%' . $searchString . '%')->leftjoin('chapter_scanalator', 'scanalators.id', '=', 'chapter_scanalator.scanalator_id')->select('scanalators.*', DB::raw('count(*) as total'))->groupBy('name')->orderBy('total', 'desc')->take(5)->get();
		
		$scanalators = $scanalators->map(function ($item){
			return ['name' => $item->name, 'total' => $item->total];
		});
		
		//Add personal aliases to pluck list if tags don't exit ('figure out how this works for an API call)
		
		//Get global aliases with total
		$global_aliases = ScanalatorAlias::where('user_id', '=', null)->where('alias', 'like', '%' . $searchString . '%')->leftjoin('scanalators', 'scanalators.id', '=', 'scanalator_alias.scanalator_id')->leftjoin('chapter_scanalator', 'scanalators.id', '=', 'chapter_scanalator.scanalator_id')->select('scanalator_alias.*', DB::raw('count(*) as total'))->groupBy('id')->orderBy('total', 'desc')->take(5)->get();
		
		$global_aliases = $global_aliases->map(function ($item){
			return ['name' => $item->alias, 'total' => $item->total];
		});
		
		//Combine and return based on usage
		$matches = collect();
		$matches->push($scanalators);
		$matches->push($global_aliases);
		$matches = $matches->flatten(1);
		$matches = $matches->sortByDesc('total');
		$scanalators = $matches->take(5)->pluck('name');
		
		$scanalators = $scanalators->sort();
		
		$scanalatorList = array();
		foreach ($scanalators as $scanalator)
		{
			array_push($scanalatorList, ['value' => $scanalator, 'label' => $scanalator]);
		}
		
		return $scanalatorList;
	}
	
	public static function SeriesLookupHelper($searchString)
	{
		$series = Series::where('name', 'like', '%' . $searchString . '%')->leftjoin('collection_series', 'series.id', '=', 'collection_series.series_id')->select('series.*', DB::raw('count(*) as total'))->groupBy('name')->orderBy('total', 'desc')->take(5)->get();
		
		$series = $series->map(function ($item){
			return ['name' => $item->name, 'total' => $item->total];
		});
		
		//Add personal aliases to pluck list if tags don't exit ('figure out how this works for an API call)
		
		//Get global aliases with total
		$global_aliases = SeriesAlias::where('user_id', '=', null)->where('alias', 'like', '%' . $searchString . '%')->leftjoin('series', 'series.id', '=', 'series_alias.series_id')->leftjoin('collection_series', 'series.id', '=', 'collection_series.series_id')->select('series_alias.*', DB::raw('count(*) as total'))->groupBy('id')->orderBy('total', 'desc')->take(5)->get();
		
		$global_aliases = $global_aliases->map(function ($item){
			return ['name' => $item->alias, 'total' => $item->total];
		});
		
		//Combine and return based on usage
		$matches = collect();
		$matches->push($series);
		$matches->push($global_aliases);
		$matches = $matches->flatten(1);
		$matches = $matches->sortByDesc('total');
		$series = $matches->take(5)->pluck('name');
		
		$series = $series->sort();
		
		$seriesList = array();
		foreach ($series as $ser)
		{
			array_push($seriesList, ['value' => $ser, 'label' => $ser]);
		}
		
		return $seriesList;
	}
	
	public static function TagLookupHelper($searchString)
	{
		$tags = Tag::where('name', 'like', '%' . $searchString . '%')->leftjoin('collection_tag', 'tags.id', '=', 'collection_tag.tag_id')->select('tags.*', DB::raw('count(*) as total'))->groupBy('name')->orderBy('total', 'desc')->take(5)->get();
		
		$tags = $tags->map(function ($item){
			return ['name' => $item->name, 'total' => $item->total];
		});
		
		//Add personal aliases to pluck list if tags don't exit ('figure out how this works for an API call)
		
		//Get global aliases with total
		$global_aliases = TagAlias::where('user_id', '=', null)->where('alias', 'like', '%' . $searchString . '%')->leftjoin('tags', 'tags.id', '=', 'tag_alias.tag_id')->leftjoin('collection_tag', 'tags.id', '=', 'collection_tag.tag_id')->select('tag_alias.*', DB::raw('count(*) as total'))->groupBy('id')->orderBy('total', 'desc')->take(5)->get();
		
		$global_aliases = $global_aliases->map(function ($item){
			return ['name' => $item->alias, 'total' => $item->total];
		});
		
		//Combine and return based on usage
		$matches = collect();
		$matches->push($tags);
		$matches->push($global_aliases);
		$matches = $matches->flatten(1);
		$matches = $matches->sortByDesc('total');
		$tags = $matches->take(5)->pluck('name');
		
		$tags = $tags->sort();
		
		$tagsList = array();
		foreach ($tags as $tag)
		{
			array_push($tagsList, ['value' => $tag, 'label' => $tag]);
		}
		
		return $tagsList;
	}
}
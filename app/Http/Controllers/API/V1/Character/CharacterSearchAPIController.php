<?php

namespace App\Http\Controllers\API\V1\Character;

use App\Http\Controllers\Controller;
use App\Models\TagObjects\Character\Character;
use App\Models\TagObjects\Character\CharacterAlias;
use App\Models\TagObjects\Series\Series;
use App\Models\TagObjects\Series\SeriesAlias;
use Illuminate\Http\Request;
use DB;
use Input;

class CharacterSearchAPIController extends Controller
{
	/*
	 * Internal search API (find characters by name).
	 */
    public function SearchByName(Request $request)
	{
		$searchString = trim(Input::get('searchString'));
		$seriesString = Input::get('seriesString');
		
		//Explode series string into a list of series
		$seriesArray = array_map('trim', explode(',', $seriesString));
		$seriesCollection = Collect();
		
		//Get the existing series objects for each series provided
		foreach($seriesArray as $seriesName)
		{
			$seriesLookup = Series::where('name', '=', $seriesName)->first();
			if($seriesLookup != null)
			{
				$seriesCollection->push($seriesLookup);
			}
			else
			{
				$seriesLookup = SeriesAlias::where('user_id', '=', null)->where('alias', '=', $seriesName)->first();
				if ($seriesLookup != null)
				{
					$seriesCollection->push($seriesLookup->series()->first());
				}
			}
		}
		
		$fullInheritedSeriesCollection = $seriesCollection;
		
		//Iterate through all series provided AND the full lineage of those series to retrieve the all possible characters that can be used
		for($i = 0; $i < $fullInheritedSeriesCollection->count(); $i++)
		{
			$currentSeries = $fullInheritedSeriesCollection[$i];
			
			foreach($currentSeries->parents()->get() as $parent)
			{
				$fullInheritedSeriesCollection->push($parent);
			}
		}
		
		if ($fullInheritedSeriesCollection->count() == 0)
		{
			return Collect();
		}
		
		$query = new Character();
		
		$query = $query->where(function($query) use ($fullInheritedSeriesCollection){
			$first = true;

			foreach($fullInheritedSeriesCollection as $series)
			{
				if ($first)
				{
					$first = false;
					$query = $query->where('series_id', '=', $series->id);
				}
				else
				{
					$query = $query->orWhere('series_id', '=', $series->id);
				}
			}
		});
		
		//Build list of characters out based on series string
		$characters = $query->where('name', 'like', '%' . $searchString . '%')->leftjoin('character_collection', 'characters.id', '=', 'character_collection.character_id')->select('characters.*', DB::raw('count(*) as total'))->groupBy('name')->orderBy('total', 'desc')->take(5)->pluck('name');
		
		//Add personal aliases to pluck list if tags don't exit ('figure out how this works for an API call)
		
		//Add global series to pluck list if tags don't exist
		if (count($characters) < 5)
		{
			$query = new CharacterAlias();
			$query = $query->leftjoin('characters', 'character_alias.character_id', '=', 'characters.id');
			
			$query = $query->where(function($query) use ($fullInheritedSeriesCollection){
				$first = true;
				//Look up each series (ignore non-valid one)
				foreach($fullInheritedSeriesCollection as $series)
				{
					if ($first)
					{
						$first = false;
						$query = $query->where('characters.series_id', '=', $series->id);
					}
					else
					{
						$query = $query->orWhere('characters.series_id', '=', $series->id);
					}
				}				
			});
			
			//Build list of characters out based on series string
			$global_aliases = $query->where('character_alias.user_id', '=', null)->where('character_alias.alias', 'like', '%' . $searchString . '%')->orderBy('character_alias.alias', 'asc')->take(5 - count($characters))->pluck('character_alias.alias');
			
			foreach ($global_aliases as $alias)
			{
				$characters->put('name', $alias);
			}
		}
		
		$characters = $characters->sortBy('name');
		
		$characterList = array();
		foreach ($characters as $character)
		{
			array_push($characterList, ['value' => $character, 'label' => $character]);
		}
		
		return $characterList;
	}
	
	/*
	 * Internal search API (find eligible child characters by name).
	 */
    public function SearchEligibleChildByName(Request $request)
	{
		$searchString = trim(Input::get('searchString'));
		$seriesString = Input::get('seriesString');
		
		//Explode series string into a list of series
		$seriesArray = array_map('trim', explode(',', $seriesString));
		$seriesCollection = Collect();
		
		//Get the existing series objects for each series provided
		foreach($seriesArray as $seriesName)
		{
			$seriesLookup = Series::where('name', '=', $seriesName)->first();
			if($seriesLookup != null)
			{
				$seriesCollection->push($seriesLookup);
			}
			else
			{
				$seriesLookup = SeriesAlias::where('user_id', '=', null)->where('alias', '=', $seriesName)->first();
				if ($seriesLookup != null)
				{
					$seriesCollection->push($seriesLookup->series()->first());
				}
			}
		}
		
		$fullInheritedSeriesCollection = $seriesCollection;
		
		//Iterate through all series provided AND the full lineage of those series to retrieve the all possible characters that can be used
		for($i = 0; $i < $fullInheritedSeriesCollection->count(); $i++)
		{
			$currentSeries = $fullInheritedSeriesCollection[$i];
			
			foreach($currentSeries->children()->get() as $child)
			{
				$fullInheritedSeriesCollection->push($child);
			}
		}
		
		if ($fullInheritedSeriesCollection->count() == 0)
		{
			return Collect();
		}
		
		$query = new Character();
		
		$query = $query->where(function($query) use ($fullInheritedSeriesCollection){
			$first = true;

			foreach($fullInheritedSeriesCollection as $series)
			{
				if ($first)
				{
					$first = false;
					$query = $query->where('series_id', '=', $series->id);
				}
				else
				{
					$query = $query->orWhere('series_id', '=', $series->id);
				}
			}
		});
		
		//Build list of characters out based on series string
		$characters = $query->where('name', 'like', '%' . $searchString . '%')->leftjoin('character_collection', 'characters.id', '=', 'character_collection.character_id')->select('characters.*', DB::raw('count(*) as total'))->groupBy('name')->orderBy('total', 'desc')->take(5)->pluck('name');
		
		//Add personal aliases to pluck list if tags don't exit ('figure out how this works for an API call)
		
		//Add global series to pluck list if tags don't exist
		if (count($characters) < 5)
		{
			$query = new CharacterAlias();
			$query = $query->leftjoin('characters', 'character_alias.character_id', '=', 'characters.id');
			
			$query = $query->where(function($query) use ($fullInheritedSeriesCollection){
				$first = true;
				//Look up each series (ignore non-valid one)
				foreach($fullInheritedSeriesCollection as $series)
				{
					if ($first)
					{
						$first = false;
						$query = $query->where('characters.series_id', '=', $series->id);
					}
					else
					{
						$query = $query->orWhere('characters.series_id', '=', $series->id);
					}
				}				
			});
			
			//Build list of characters out based on series string
			$global_aliases = $query->where('character_alias.user_id', '=', null)->where('character_alias.alias', 'like', '%' . $searchString . '%')->orderBy('character_alias.alias', 'asc')->take(5 - count($characters))->pluck('character_alias.alias');
			
			foreach ($global_aliases as $alias)
			{
				$characters->put('name', $alias);
			}
		}
		
		$characters = $characters->sortBy('name');
		
		$characterList = array();
		foreach ($characters as $character)
		{
			array_push($characterList, ['value' => $character, 'label' => $character]);
		}
		
		return $characterList;
	}
}

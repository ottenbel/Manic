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
		
		$query = new Character();
		
		$query->where(function($query) use ($seriesArray){
			$first = true;
			//Look up each series (ignore non-valid one)
			foreach($seriesArray as $seriesName)
			{
				$seriesLookup = Series::where('name', '=', $seriesName)->first();
				if ($seriesLookup != null)
				{
					if ($first)
					{
						$first = false;
						$query->where('series_id', '=', $seriesLookup->id);
					}
					else
					{
						$query->orWhere('series_id', '=', $seriesLookup->id);
					}
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
			
			$query->where(function($query) use ($seriesArray){
				$first = true;
				//Look up each series (ignore non-valid one)
				foreach($seriesArray as $seriesName)
				{
					$seriesLookup = Series::where('name', '=', $seriesName)->first();
					if ($seriesLookup != null)
					{
						if ($first)
						{
							$first = false;
							$query->where('characters.series_id', '=', $seriesLookup->id);
						}
						else
						{
							$query->orWhere('characters.series_id', '=', $seriesLookup->id);
						}
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

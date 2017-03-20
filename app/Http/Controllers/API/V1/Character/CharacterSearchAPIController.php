<?php

namespace App\Http\Controllers\API\V1\Character;

use App\Http\Controllers\Controller;
use App\Character;
use App\Series;
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
		$primarySeriesString = Input::get('primarySeriesString');
		$secondarySeriesString = Input::get('secondarySeriesString');
		
		//Explode series string into a list of series
		$seriesPrimaryArray = array_map('trim', explode(',', $primarySeriesString));
		$seriesSecondaryArray = array_map('trim', explode(',', $primarySeriesString));
		
		//Add the two arrays together and clean up duplicates
		$seriesArray = array_unique(array_merge($seriesPrimaryArray, $seriesSecondaryArray));
		
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
				
		$characters = $characters->sortBy('name');
		
		$characterList = array();
		foreach ($characters as $character)
		{
			array_push($characterList, ['value' => $character, 'label' => $character]);
		}
		
		return $characterList;
	}
}

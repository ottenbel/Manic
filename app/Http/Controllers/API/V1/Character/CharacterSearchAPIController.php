<?php

namespace App\Http\Controllers\API\V1\Character;

use App\Http\Controllers\Controller;
use App\Character;
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
		
		//Look up each series (discard each non-valid one)
		
		//Build list of characters out based on series string
		
		/*$artists = Artist::where('name', 'like', '%' . $searchString . '%')->leftjoin('artist_collection', 'artists.id', '=', 'artist_collection.artist_id')->select('artists.*', DB::raw('count(*) as total'))->groupBy('name')->orderBy('total', 'desc')->take(5)->pluck('name'); */
		
		$characters = $characters->sortBy('name');
		
		$characterList = array();
		foreach ($characters as $character)
		{
			array_push($characterList, ['value' => $character, 'label' => $character]);
		}
		
		return $characterList;
	}
}

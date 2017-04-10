<?php

namespace App\Http\Controllers\API\V1\Artist;

use App\Http\Controllers\Controller;
use App\Models\TagObjects\Artist\Artist;
use App\Models\TagObjects\Artist\ArtistAlias;
use Illuminate\Http\Request;
use DB;
use Input;

class ArtistSearchAPIController extends Controller
{
	/*
	 * Internal search API (find artists by name).
	 */
    public function SearchByName(Request $request)
	{
		$searchString = trim(Input::get('searchString'));
		$artists = Artist::where('name', 'like', '%' . $searchString . '%')->leftjoin('artist_collection', 'artists.id', '=', 'artist_collection.artist_id')->select('artists.*', DB::raw('count(*) as total'))->groupBy('name')->orderBy('total', 'desc')->take(5)->pluck('name');
		
		//Add personal aliases to pluck list if tags don't exit ('figure out how this works for an API call)
		
		//Add global aliases to pluck list if tags don't exist
		if (count($artists) < 5)
		{
			$global_aliases = ArtistAlias::where('user_id', '=', null)->where('alias', 'like', '%' . $searchString . '%')->orderBy('alias', 'asc')->take(5 - count($artists))->pluck('alias');
			
			foreach ($global_aliases as $alias)
			{
				$artists->put('name', $alias);
			}
		}
		
		$artists = $artists->sortBy('name');
		
		$artistList = array();
		foreach ($artists as $artist)
		{
			array_push($artistList, ['value' => $artist, 'label' => $artist]);
		}
		
		return $artistList;
	}
}

<?php

namespace App\Http\Controllers\API\V1\Artist;

use App\Http\Controllers\Controller;
use App\Artist;
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
		
		$artists = $artists->sort();
		
		return $artists;
	}
}
<?php

namespace App\Http\Controllers\API\V1\Artist;

use App\Http\Controllers\Controller;
use App\Models\TagObjects\Artist\Artist;
use App\Models\TagObjects\Artist\ArtistAlias;
use Illuminate\Http\Request;
use DB;
use Input;
use SearchLookupHelper;

class ArtistSearchAPIController extends Controller
{
	/*
	 * Internal search API (find artists by name).
	 */
    public function SearchByName(Request $request)
	{
		$searchString = trim(Input::get('searchString'));
		return SearchLookupHelper::ArtistLookupHelper($searchString);
	}
}

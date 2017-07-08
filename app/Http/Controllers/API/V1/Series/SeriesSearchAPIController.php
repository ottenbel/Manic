<?php

namespace App\Http\Controllers\API\V1\Series;

use App\Http\Controllers\Controller;
use App\Models\TagObjects\Series\Series;
use App\Models\TagObjects\Series\SeriesAlias;
use Illuminate\Http\Request;
use DB;
use Input;
use SearchLookupHelper;

class SeriesSearchAPIController extends Controller
{
	/*
	 * Internal search API (find series by name).
	 */
    public function SearchByName(Request $request)
	{
		$searchString = trim(Input::get('searchString'));
		return SearchLookupHelper::SeriesLookupHelper($searchString);
	}
}

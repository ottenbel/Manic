<?php

namespace App\Http\Controllers\API\V1\Scanalator;

use App\Http\Controllers\Controller;
use App\Models\TagObjects\Scanalator\Scanalator;
use App\Models\TagObjects\Scanalator\ScanalatorAlias;
use Illuminate\Http\Request;
use DB;
use Input;
use SearchLookupHelper;

class ScanalatorSearchAPIController extends Controller
{
	/*
	 * Internal search API (find scanalators by name).
	 */
    public function SearchByName(Request $request)
	{
		$searchString = trim(Input::get('searchString'));
		return SearchLookupHelper::ScanalatorLookupHelper($searchString);
	}
}

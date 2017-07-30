<?php

namespace App\Http\Controllers\API\V1\Tag;

use App\Http\Controllers\Controller;
use App\Models\TagObjects\Tag\Tag;
use App\Models\TagObjects\Tag\TagAlias;
use Illuminate\Http\Request;
use DB;
use Input;
use SearchLookupHelper;

class TagSearchAPIController extends Controller
{
	/*
	 * Internal search API (find tags by name).
	 */
	public function SearchByName(Request $request)
	{
		$searchString = trim(Input::get('searchString'));
		return SearchLookupHelper::TagLookupHelper($searchString);
	}    
}

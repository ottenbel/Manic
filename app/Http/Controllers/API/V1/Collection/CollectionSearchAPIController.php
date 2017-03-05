<?php

namespace App\Http\Controllers\API\V1\Collection;

use App\Http\Controllers\Controller;
use App\Collection;
use Illuminate\Http\Request;
use Input;

class CollectionSearchAPIController extends Controller
{
	/*
	 * Internal search API (find collections by name).
	 */
	public function SearchByName(Request $request)
	{
		$searchString = trim(Input::get('searchString'));
		$collections = Collection::where('name', 'like', '%' . $searchString . '%')->orderBy('name', 'asc')->take(5)->pluck('name');
		
		return $collections;
	}
}

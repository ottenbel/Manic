<?php

namespace App\Http\Controllers\API\V1\Collection;

use App\Http\Controllers\Controller;
use App\Models\Collection\Collection;
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
		$collections = Collection::where('name', 'like', '%' . $searchString . '%')->orderBy('name', 'asc')->take(5)->pluck('name', 'id');
		
		$collections = $collections->sortBy('name');
		
		$collectionList = array();
		foreach ($collections as $key => $collection)
		{
			array_push($collectionList, ['value' => $key, 'label' => $collection]);
		}
		
		return $collectionList;
	}
}

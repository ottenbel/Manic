<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Input;

class SearchController extends Controller
{	
	/* 
	 * Build the search string and provide it to the site index
	 */
	public function search(Request $request)
	{
		$searchString = trim(Input::get('query_string'));
		
		//Redirect to index with query string
		return redirect()->route('index_collection', ['search' => $searchString]);
	}
}
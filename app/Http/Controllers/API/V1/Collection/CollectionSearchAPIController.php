<?php

namespace App\Http\Controllers\API\V1\Collection;

use App\Http\Controllers\Controller;
use App\Collection;
use Illuminate\Http\Request;

class CollectionSearchAPIController extends Controller
{
	/*
	 * Internal search API (find collections by name).
	 */
	public function SearchByName(Request $request)
	{
		return "Collection";
	}
}

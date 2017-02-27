<?php

namespace App\Http\Controllers\API\V1\Tag;

use App\Http\Controllers\Controller;
use App\Tag;
use Illuminate\Http\Request;

class TagSearchAPIController extends Controller
{
	/*
	 * Internal search API (find tags by name).
	 */
	public function SearchByName(Request $request)
	{
		return "Tag";
	}    
}

<?php

namespace App\Http\Controllers\API\V1\Series;

use App\Http\Controllers\Controller;
use App\Series;
use Illuminate\Http\Request;

class SeriesSearchAPIController extends Controller
{
	/*
	 * Internal search API (find series by name).
	 */
    public function SearchByName(Request $request)
	{
		return "Series";
	}
}

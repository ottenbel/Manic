<?php

namespace App\Http\Controllers\API\V1\Scanalator;

use App\Http\Controllers\Controller;
use App\Scanalator;
use Illuminate\Http\Request;

class ScanalatorSearchAPIController extends Controller
{
	/*
	 * Internal search API (find scanalators by name).
	 */
    public function SearchByName(Request $request)
	{
		return "Scanalator";
	}
}

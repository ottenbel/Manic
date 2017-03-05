<?php

namespace App\Http\Controllers\API\V1\Scanalator;

use App\Http\Controllers\Controller;
use App\Scanalator;
use Illuminate\Http\Request;
use DB;
use Input;

class ScanalatorSearchAPIController extends Controller
{
	/*
	 * Internal search API (find scanalators by name).
	 */
    public function SearchByName(Request $request)
	{
		$searchString = trim(Input::get('searchString'));
		$scanalators = Scanalator::where('name', 'like', '%' . $searchString . '%')->leftjoin('chapter_scanalator', 'scanalators.id', '=', 'chapter_scanalator.scanalator_id')->select('scanalators.*', DB::raw('count(*) as total'))->groupBy('name')->orderBy('total', 'desc')->take(5)->pluck('name');
		
		$scanalators = $scanalators->sortBy('name');
		
		return $scanalators;
	}
}

<?php

namespace App\Http\Controllers\API\V1\Series;

use App\Http\Controllers\Controller;
use App\Series;
use Illuminate\Http\Request;
use DB;
use Input;

class SeriesSearchAPIController extends Controller
{
	/*
	 * Internal search API (find series by name).
	 */
    public function SearchByName(Request $request)
	{
		$searchString = trim(Input::get('searchString'));
		$series = Series::where('name', 'like', '%' . $searchString . '%')->leftjoin('collection_series', 'series.id', '=', 'collection_series.series_id')->select('series.*', DB::raw('count(*) as total'))->groupBy('name')->orderBy('total', 'desc')->take(5)->pluck('name');
		
		$series = $series->sort();
		
		return $series;
	}
}

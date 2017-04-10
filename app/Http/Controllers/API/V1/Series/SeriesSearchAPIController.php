<?php

namespace App\Http\Controllers\API\V1\Series;

use App\Http\Controllers\Controller;
use App\Models\TagObjects\Series\Series;
use App\Models\TagObjects\Series\SeriesAlias;
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
		
		//Add personal aliases to pluck list if tags don't exit ('figure out how this works for an API call)
		
		//Add global series to pluck list if tags don't exist
		if (count($series) < 5)
		{
			$global_aliases = SeriesAlias::where('user_id', '=', null)->where('alias', 'like', '%' . $searchString . '%')->orderBy('alias', 'asc')->take(5 - count($series))->pluck('alias');
			
			foreach ($global_aliases as $alias)
			{
				$series->put('name', $alias);
			}
		}
		
		$series = $series->sortBy('name');
		
		$seriesList = array();
		foreach ($series as $ser)
		{
			array_push($seriesList, ['value' => $ser, 'label' => $ser]);
		}
		
		return $seriesList;
	}
}

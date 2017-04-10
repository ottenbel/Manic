<?php

namespace App\Http\Controllers\API\V1\Scanalator;

use App\Http\Controllers\Controller;
use App\Models\TagObjects\Scanalator\Scanalator;
use App\Models\TagObjects\Scanalator\ScanalatorAlias;
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
		
		//Add personal aliases to pluck list if tags don't exit ('figure out how this works for an API call)
		
		//Add global aliases to pluck list if tags don't exist
		if (count($scanalators) < 5)
		{
			$global_aliases = ScanalatorAlias::where('user_id', '=', null)->where('alias', 'like', '%' . $searchString . '%')->orderBy('alias', 'asc')->take(5 - count($scanalators))->pluck('alias');
			
			foreach ($global_aliases as $alias)
			{
				$scanalators->put('name', $alias);
			}
		}
		
		$scanalators = $scanalators->sortBy('name');
		
		$scanalatorList = array();
		foreach ($scanalators as $scanalator)
		{
			array_push($scanalatorList, ['value' => $scanalator, 'label' => $scanalator]);
		}
		
		return $scanalatorList;
	}
}

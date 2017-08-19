<?php

namespace App\Http\Controllers\TagObjects\Series;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Auth;
use DB;
use Input;
use Config;
use ConfigurationLookupHelper;
use App\Models\TagObjects\Series\SeriesAlias;
use App\Models\TagObjects\Series\Series;

class SeriesAliasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
		$flashed_success = $request->session()->get('flashed_success');
		$flashed_data = $request->session()->get('flashed_data');
		$flashed_warning = $request->session()->get('flashed_warning');
		
		$alias_list_type = trim(strtolower($request->input('type')));
		$alias_list_order = trim(strtolower($request->input('order')));
		
		if (($alias_list_type != Config::get('constants.sortingStringComparison.aliasListType.global')) 
			&& ($alias_list_type != Config::get('constants.sortingStringComparison.aliasListType.personal')) 
			&& ($alias_list_type != Config::get('constants.sortingStringComparison.aliasListType.mixed')))
		{
			$alias_list_type = Config::get('constants.sortingStringComparison.aliasListType.mixed');
		}
		
		if (($alias_list_order != Config::get('constants.sortingStringComparison.listOrder.ascending')) 
			&& ($alias_list_order != Config::get('constants.sortingStringComparison.listOrder.descending')))
		{
			$alias_list_order = Config::get('constants.sortingStringComparison.listOrder.ascending');
		}
		
		$aliases = new SeriesAlias();
		
		$lookupKey = Config::get('constants.keys.pagination.seriesAliasesPerPageIndex');
		$paginationCount = ConfigurationLookupHelper::LookupPaginationConfiguration($lookupKey)->value;
		
		if (Auth::user())
		{
			if ($alias_list_type == Config::get('constants.sortingStringComparison.aliasListType.global'))
			{
				$aliases = $aliases->where('user_id', '=', null)->orderBy('alias', $alias_list_order)->paginate($paginationCount);
			}
			
			if ($alias_list_type == Config::get('constants.sortingStringComparison.aliasListType.personal'))
			{
				$aliases = $aliases->where('user_id', '=', Auth::user()->id)->orderBy('alias', $alias_list_order)->paginate($paginationCount);
			}
			
			if ($alias_list_type == Config::get('constants.sortingStringComparison.aliasListType.mixed'))
			{
				$aliases = $aliases->where('user_id', '=', null)->orWhere('user_id', '=', Auth::user()->id)->orderBy('alias', $alias_list_order)->paginate($paginationCount);
			}
		}
		else
		{
			$aliases = new SeriesAlias();
			$aliases = $aliases->where('user_id', '=', null)->orderBy('alias', $alias_list_order)->paginate($paginationCount);
		}
		
		return View('tagObjects.series.alias.index', array('aliases' => $aliases->appends(Input::except('page')), 'list_type' => $alias_list_type, 'list_order' => $alias_list_order, 'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request, Series $series)
    {
        $isGlobalAlias = Input::get('is_global_alias');
		$isPersonalAlias = Input::get('is_personal_alias');
		if ($isGlobalAlias)
		{
			//Define authorization in the controller as global variables can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
			$this->authorize([SeriesAlias::class, true]);
			
			$this->validate($request, [
				'global_alias' => 'required|unique:series,name|unique:series_alias,alias,null,null,user_id,NULL|regex:/^[^,:-]+$/']);
		}
		else if ($isPersonalAlias)
		{
			//Define authorization in the controller as global variables can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
			$this->authorize([SeriesAlias::class, false]);
			
			$this->validate($request, [
				'personal_alias' => 'required|unique:series,name|unique:series_alias,alias,null,null,user_id,'.Auth::user()->id.'|regex:/^[^,:-]+$/']);
		}
		
		$seriesAlias = new SeriesAlias();
		$seriesAlias->series_id = $series->id;
		 
        if ($isGlobalAlias)
		{
			$seriesAlias->alias = Input::get('global_alias');
			$seriesAlias->user_id = null;
		}
		else
		{
			$seriesAlias->alias = Input::get('personal_alias');
			$seriesAlias->user_id = Auth::user()->id;
		}
				
		$seriesAlias->save();
		
		//Redirect to the series that the alias was created for
		return redirect()->route('show_series', ['series' => $series])->with("flashed_success", array("Successfully created alias $seriesAlias->alias on series $series->name."));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  SeriesAlias  $seriesAlias
     * @return Response
     */
    public function destroy(SeriesAlias $seriesAlias)
    {
        //Define authorization in the controller as global variables can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize($seriesAlias);
		
		$series = $seriesAlias->series_id;
		
		//Force deleting for now, build out functionality for soft deleting later.
		$seriesAlias->forceDelete();
		//redirect to the series that the alias existed for
		return redirect()->route('show_series', ['series' => $series])->with("flashed_success", array("Successfully purged alias from series."));
    }
}

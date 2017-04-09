<?php

namespace App\Http\Controllers\TagObjects\Series;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Auth;
use DB;
use Input;
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
		
		if (($alias_list_type != "global") && ($alias_list_type != "personal") && ($alias_list_type != "mixed"))
		{
			$alias_list_type = "mixed";
		}
		
		if (($alias_list_order != "asc") && ($alias_list_order != "desc"))
		{
			$alias_list_order = "asc";
		}
		
		$aliases = new SeriesAlias();
		
		if (Auth::user())
		{
			if ($alias_list_type == "global")
			{
				$aliases = $aliases->where('user_id', '=', null)->orderBy('alias', $alias_list_order)->paginate(30);
			}
			
			if ($alias_list_type == "personal")
			{
				$aliases = $aliases->where('user_id', '=', Auth::user()->id)->orderBy('alias', $alias_list_order)->paginate(30);
			}
			
			if ($alias_list_type == "mixed")
			{
				$aliases = $aliases->where('user_id', '=', null)->orWhere('user_id', '=', Auth::user()->id)->orderBy('alias', $alias_list_order)->paginate(30);
			}
		}
		else
		{
			$aliases = new SeriesAlias();
			$aliases = $aliases->where('user_id', '=', null)->orderBy('alias', $alias_list_order)->paginate(30);
		}
		
		return View('series.alias.index', array('aliases' => $aliases->appends(Input::except('page')), 'list_type' => $alias_list_type, 'list_order' => $alias_list_order, 'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request, Series $series)
    {
        $isGlobalAlias = Input::get('is_global_alias');
		if ($isGlobalAlias)
		{
			$this->validate($request, [
				'global_alias' => 'required|unique:series,name|unique:series_alias,alias,null,null,user_id,NULL']);
		}
		else
		{
			$this->validate($request, [
				'personal_alias' => 'required|unique:series,name|unique:series_alias,alias,null,null,user_id,'.Auth::user()->id]);
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
		
		$seriesAlias->created_by = Auth::user()->id;
		$seriesAlias->updated_by = Auth::user()->id;
		
		$seriesAlias->save();
		
		//Redirect to the series that the alias was created for
		return redirect()->action('TagObjects\Series\SeriesController@show', [$series])->with("flashed_success", array("Successfully created alias $seriesAlias->alias on series $series->name."));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}

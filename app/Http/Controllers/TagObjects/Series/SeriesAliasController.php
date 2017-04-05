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
		//
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

<?php

namespace App\Http\Controllers\TagObjects\Scanalator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Auth;
use DB;
use Input;
use App\Models\TagObjects\Scanalator\ScanalatorAlias;
use App\Models\TagObjects\Scanalator\Scanalator;

class ScanalatorAliasController extends Controller
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
		
		$aliases = new ScanalatorAlias();
		
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
			$aliases = new ScanalatorAlias();
			$aliases = $aliases->where('user_id', '=', null)->orderBy('alias', $alias_list_order)->paginate(30);
		}
		
		return View('scanalators.alias.index', array('aliases' => $aliases->appends(Input::except('page')), 'list_type' => $alias_list_type, 'list_order' => $alias_list_order, 'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request, Scanalator $scanalator)
    {
        $isGlobalAlias = Input::get('is_global_alias');
		if ($isGlobalAlias)
		{
			$this->validate($request, [
				'global_alias' => 'required|unique:scanalators,name|unique:scanalator_alias,alias,null,null,user_id,NULL']);
		}
		else
		{
			$this->validate($request, [
				'personal_alias' => 'required|unique:scanalators,name|unique:scanalator_alias,alias,null,null,user_id,'.Auth::user()->id]);
		}
		
		$scanalatorAlias = new ScanalatorAlias();
		$scanalatorAlias->scanalator_id = $scanalator->id;
		 
        if ($isGlobalAlias)
		{
			$scanalatorAlias->alias = Input::get('global_alias');
			$scanalatorAlias->user_id = null;
		}
		else
		{
			$scanalatorAlias->alias = Input::get('personal_alias');
			$scanalatorAlias->user_id = Auth::user()->id;
		}
				
		$scanalatorAlias->save();
		
		//Redirect to the scanalator that the alias was created for
		return redirect()->action('TagObjects\Scanalator\ScanalatorController@show', [$scanalator])->with("flashed_success", array("Successfully created alias $scanalatorAlias->alias on scanalator $scanalator->name."));
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

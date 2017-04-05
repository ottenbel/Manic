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
		//
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
		
		$scanalatorAlias->created_by = Auth::user()->id;
		$scanalatorAlias->updated_by = Auth::user()->id;
		
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

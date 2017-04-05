<?php

namespace App\Http\Controllers\TagObjects\Character;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Auth;
use DB;
use Input;
use App\Models\TagObjects\Character\Character;
use App\Models\TagObjects\Character\CharacterAlias;
use App\Models\TagObjects\Series\Series;
use App\Models\TagObjects\Series\SeriesAlias;

class CharacterAliasController extends Controller
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
    public function store(Request $request, Character $character)
    {	
		$isGlobalAlias = Input::get('is_global_alias');
		if ($isGlobalAlias)
		{
			$this->validate($request, [
				'global_alias' => 'required|unique:characters,name|unique:character_alias,alias,null,null,user_id,NULL']);
		}
		else
		{
			$this->validate($request, [
				'personal_alias' => 'required|unique:characters,name|unique:character_alias,alias,null,null,user_id,'.Auth::user()->id]);
		}
		
		$characterAlias = new CharacterAlias();
		$characterAlias->character_id = $character->id;
		 
        if ($isGlobalAlias)
		{
			$characterAlias->alias = Input::get('global_alias');
			$characterAlias->user_id = null;
		}
		else
		{
			$characterAlias->alias = Input::get('personal_alias');
			$characterAlias->user_id = Auth::user()->id;
		}
		
		$characterAlias->created_by = Auth::user()->id;
		$characterAlias->updated_by = Auth::user()->id;
		
		$characterAlias->save();
		
		//Redirect to the character that the alias was created for
		return redirect()->action('TagObjects\Character\CharacterController@show', [$character])->with("flashed_success", array("Successfully created alias $characterAlias->alias on character $character->name."));
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

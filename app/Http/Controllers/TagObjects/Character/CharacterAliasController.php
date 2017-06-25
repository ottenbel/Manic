<?php

namespace App\Http\Controllers\TagObjects\Character;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Auth;
use DB;
use Input;
use Config;
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
		
		$aliases = new CharacterAlias();
		
		if (Auth::user())
		{
			if ($alias_list_type == Config::get('constants.sortingStringComparison.aliasListType.global'))
			{
				$aliases = $aliases->where('user_id', '=', null)->orderBy('alias', $alias_list_order)->paginate(Config::get('constants.pagination.tagAliasesPerPageIndex'));
			}
			
			if ($alias_list_type == Config::get('constants.sortingStringComparison.aliasListType.personal'))
			{
				$aliases = $aliases->where('user_id', '=', Auth::user()->id)->orderBy('alias', $alias_list_order)->paginate(Config::get('constants.pagination.tagAliasesPerPageIndex'));
			}
			
			if ($alias_list_type == Config::get('constants.sortingStringComparison.aliasListType.mixed'))
			{
				$aliases = $aliases->where('user_id', '=', null)->orWhere('user_id', '=', Auth::user()->id)->orderBy('alias', $alias_list_order)->paginate(Config::get('constants.pagination.tagAliasesPerPageIndex'));
			}
		}
		else
		{
			$aliases = new CharacterAlias();
			$aliases = $aliases->where('user_id', '=', null)->orderBy('alias', $alias_list_order)->paginate(Config::get('constants.pagination.tagAliasesPerPageIndex'));
		}
		
		return View('tagObjects.characters.alias.index', array('aliases' => $aliases->appends(Input::except('page')), 'list_type' => $alias_list_type, 'list_order' => $alias_list_order, 'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
    }
	
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request, Character $character)
    {	
		$isGlobalAlias = Input::get('is_global_alias');
		$isPersonalAlias = Input::get('is_personal_alias');
		if ($isGlobalAlias)
		{
			//Define authorization in the controller as global variables can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
			$this->authorize([CharacterAlias::class, true]);
			
			$this->validate($request, [
				'global_alias' => 'required|unique:characters,name|unique:character_alias,alias,null,null,user_id,NULL|regex:/^[^,:-]+$/']);
		}
		else if ($isPersonalAlias)
		{
			//Define authorization in the controller as global variables can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
			$this->authorize([CharacterAlias::class, false]);
			
			$this->validate($request, [
				'personal_alias' => 'required|unique:characters,name|unique:character_alias,alias,null,null,user_id,'.Auth::user()->id.'|regex:/^[^,:-]+$/']);
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
				
		$characterAlias->save();
		
		//Redirect to the character that the alias was created for
		return redirect()->route('show_character', ['character' => $character])->with("flashed_success", array("Successfully created alias $characterAlias->alias on character $character->name."));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  CharacterAlias  $characterAlias
     * @return Response
     */
    public function destroy(CharacterAlias $characterAlias)
    {
        //Define authorization in the controller as global variables can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize($characterAlias);
		
		$character = $characterAlias->character_id;
		
		//Force deleting for now, build out functionality for soft deleting later.
		$characterAlias->forceDelete();
		//redirect to the character that the alias existed for
		return redirect()->route('show_character', ['character' => $character])->with("flashed_success", array("Successfully purged alias from character."));
    }
}

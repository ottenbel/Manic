<?php

namespace App\Http\Controllers\TagObjects\Scanalator;

use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Auth;
use DB;
use Input;
use Config;
use ConfigurationLookupHelper;
use App\Models\TagObjects\Scanalator\ScanalatorAlias;
use App\Models\TagObjects\Scanalator\Scanalator;

class ScanalatorAliasController extends WebController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
		$messages = self::GetFlashedMessages($request);
		
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
		
		$aliases = new ScanalatorAlias();
		
		$lookupKey = Config::get('constants.keys.pagination.scanalatorAliasesPerPageIndex');
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
			$aliases = new ScanalatorAlias();
			$aliases = $aliases->where('user_id', '=', null)->orderBy('alias', $alias_list_order)->paginate($paginationCount);
		}
		
		return View('tagObjects.scanalators.alias.index', array('aliases' => $aliases->appends(Input::except('page')), 'list_type' => $alias_list_type, 'list_order' => $alias_list_order, 'messages' => $messages));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request, Scanalator $scanalator)
    {
        $isGlobalAlias = Input::get('is_global_alias');
		$isPersonalAlias = Input::get('is_personal_alias');
		if ($isGlobalAlias)
		{
			//Define authorization in the controller as global variables can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
			$this->authorize([ScanalatorAlias::class, true]);
			
			$this->validate($request, [
				'global_alias' => 'required|unique:scanalators,name|unique:scanalator_alias,alias,null,null,user_id,NULL|regex:/^[^,:-]+$/']);
		}
		else if ($isPersonalAlias)
		{
			//Define authorization in the controller as global variables can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
			$this->authorize([ScanalatorAlias::class, false]);
			
			$this->validate($request, [
				'personal_alias' => 'required|unique:scanalators,name|unique:scanalator_alias,alias,null,null,user_id,'.Auth::user()->id.'|regex:/^[^,:-]+$/']);
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
		
		$messages = self::BuildFlashedMessagesVariable(["Successfully created alias $scanalatorAlias->alias on scanalator $scanalator->name."], null, null);
		//Redirect to the scanalator that the alias was created for
		return redirect()->route('show_scanalator', ['scanalator' => $scanalator])->with("messages", $messages);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  ScanalatorAlias  $scanalatorAlias
     * @return Response
     */
    public function destroy(ScanalatorAlias $scanalatorAlias)
    {
        //Define authorization in the controller as global variables can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize($scanalatorAlias);
		
		$scanalator = $scanalatorAlias->scanalator_id;
		
		//Force deleting for now, build out functionality for soft deleting later.
		$scanalatorAlias->forceDelete();
		
		$messages = self::BuildFlashedMessagesVariable(["Successfully purged alias from scanalator."], null, null);
		//redirect to the scanalator that the alias existed for
		return redirect()->route('show_scanalator', ['scanalator' => $scanalator])->with("messages", $messages);
    }
}

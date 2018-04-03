<?php

namespace App\Http\Controllers\TagObjects;

use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Auth;
use DB;
use Input;
use Config;
use ConfigurationLookupHelper;

class TagObjectAliasController extends WebController
{
	public function __construct()
    {
		parent::__construct();
	}
	
	protected function GetAliasIndex(Request $request, $aliases, $paginationKey, $paginationType)
	{
		$this->GetFlashedMessages($request);
		$orderAndSorting = self::GetIndexOrdering($request);
		$aliases = self::GetAliases($aliases, $orderAndSorting['type'], $orderAndSorting['order'], $paginationKey);
		
		return View('tagObjects.' . $paginationType . '.alias.index', array('aliases' => $aliases->appends(Input::except('page')), 'list_type' => $orderAndSorting['type'], 'list_order' => $orderAndSorting['order'], 'messages' => $this->messages));
	}
	
	protected function StoreAlias($request, $alias, $parentObject, $aliasIDField, $objectType, $showRoute)
	{
		DB::beginTransaction();
		try
		{
			$isGlobalAlias = Input::get('is_global_alias');
			$isPersonalAlias = Input::get('is_personal_alias');
			
			$alias->{$aliasIDField} = $parentObject->id;
			 
			if ($isGlobalAlias)
			{
				$alias->alias = Input::get('global_alias');
				$alias->user_id = null;
			}
			else
			{
				$alias->alias = Input::get('personal_alias');
				$alias->user_id = Auth::user()->id;
			}
			
			$alias->save();
		}
		catch (\Exception $e)
		{
			DB::rollBack();
			$this->AddWarningMessage("Unable to successfully add alias to $objectType.");
			return Redirect::back()->with(["messages" => $this->messages])->withInput();
		}
		DB::commit();
		
		$this->AddSuccessMessage("Successfully created alias $alias->alias on $objectType  $parentObject->name.");
		//Redirect to the object that the alias was created for
		return redirect()->route($showRoute, [$objectType => $parentObject])->with("messages", $this->messages);
	}
	
	protected function DeleteAlias($alias, $parentIDField, $objectType, $showRoute)
	{
		DB::beginTransaction();
		try
		{
			$object = $alias->{$parentIDField};

			//Force deleting for now, build out functionality for soft deleting later.
			$alias->forceDelete();
		}
		catch (\Exception $e)
		{
			DB::rollBack();
			$this->AddWarningMessage("Unable to successfully purge alias from $objectType.");
			return Redirect::back()->with(["messages" => $this->messages])->withInput();
		}
		DB::commit();
		
		//redirect to the object that the alias existed for
		$this->AddSuccessMessage("Successfully purged alias from $objectType.");
		return redirect()->route($showRoute, [$objectType => $object])->with("messages", $this->messages);
	}
	
	private static function GetIndexOrdering(Request $request)
	{
		$aliasListType = trim(strtolower($request->input('type')));
		$aliasListOrder = trim(strtolower($request->input('order')));
		
		if (($aliasListType != Config::get('constants.sortingStringComparison.aliasListType.global')) 
			&& ($aliasListType != Config::get('constants.sortingStringComparison.aliasListType.personal')) 
			&& ($aliasListType != Config::get('constants.sortingStringComparison.aliasListType.mixed')))
		{
			$aliasListType = Config::get('constants.sortingStringComparison.aliasListType.mixed');
		}
		
		if (($aliasListOrder != Config::get('constants.sortingStringComparison.listOrder.ascending')) 
			&& ($aliasListOrder != Config::get('constants.sortingStringComparison.listOrder.descending')))
		{
			$aliasListOrder = Config::get('constants.sortingStringComparison.listOrder.ascending');
		}
		
		return ['type' => $aliasListType, 'order' => $aliasListOrder];
	}
	
	private static function GetAliases($aliases, $aliasListType, $aliasListOrder, $paginationKey)
	{
		$paginationCount = ConfigurationLookupHelper::LookupPaginationConfiguration($paginationKey)->value;
		
		if (Auth::user())
		{
			if ($aliasListType == Config::get('constants.sortingStringComparison.aliasListType.global'))
			{
				$aliases = $aliases->where('user_id', '=', null)->orderBy('alias', $aliasListOrder)->paginate($paginationCount);
			}
			
			if ($aliasListType == Config::get('constants.sortingStringComparison.aliasListType.personal'))
			{
				$aliases = $aliases->where('user_id', '=', Auth::user()->id)->orderBy('alias', $aliasListOrder)->paginate($paginationCount);
			}
			
			if ($aliasListType == Config::get('constants.sortingStringComparison.aliasListType.mixed'))
			{
				$aliases = $aliases->where('user_id', '=', null)->orWhere('user_id', '=', Auth::user()->id)->orderBy('alias', $aliasListOrder)->paginate($paginationCount);
			}
		}
		else
		{
			$aliases = $aliases->where('user_id', '=', null)->orderBy('alias', $aliasListOrder)->paginate($paginationCount);
		}
		
		return $aliases;
	}
}
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
	protected static function GetAliasIndex(Request $request, $aliases, $paginationKey, $paginationType)
	{
		$messages = self::GetFlashedMessages($request);
		$orderAndSorting = self::GetIndexOrdering($request);
		$aliases = self::GetAliases($aliases, $orderAndSorting['type'], $orderAndSorting['order'], $paginationKey);
		
		return View('tagObjects.' . $paginationType . '.alias.index', array('aliases' => $aliases->appends(Input::except('page')), 'list_type' => $orderAndSorting['type'], 'list_order' => $orderAndSorting['order'], 'messages' => $messages));
	}
	
	protected static function StoreAlias($request, $alias, $parentObject, $aliasIDField, $objectType, $showRoute)
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
			$messages = self::BuildFlashedMessagesVariable(null, null, ["Unable to successfully add alias to $objectType."]);
			return Redirect::back()->with(["messages" => $messages])->withInput();
		}
		DB::commit();
		
		$messages = self::BuildFlashedMessagesVariable(["Successfully created alias $alias->alias on $objectType  $parentObject->name."], null, null);
		//Redirect to the object that the alias was created for
		return redirect()->route($showRoute, [$objectType => $parentObject])->with("messages", $messages);
	}
	
	protected static function DeleteAlias($alias, $parentIDField, $objectType, $showRoute)
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
			$messages = self::BuildFlashedMessagesVariable(null, null, ["Unable to successfully purge alias from $objectType."]);
			return Redirect::back()->with(["messages" => $messages])->withInput();
		}
		DB::commit();
		
		//redirect to the object that the alias existed for
		$messages = self::BuildFlashedMessagesVariable(["Successfully purged alias from $objectType."], null, null);
		return redirect()->route($showRoute, [$objectType => $object])->with("messages", $messages);
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
		$lookupKey = Config::get('constants.keys.pagination.'.$paginationKey);
		$paginationCount = ConfigurationLookupHelper::LookupPaginationConfiguration($lookupKey)->value;
		
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
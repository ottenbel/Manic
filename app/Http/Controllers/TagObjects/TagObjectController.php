<?php

namespace App\Http\Controllers\TagObjects;

use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Auth;
use Input;
use Config;
use DB;
use ConfigurationLookupHelper;
use App\Models\Configuration\ConfigurationPlaceholder;

class TagObjectController extends WebController
{
	protected static function GetTagObjectIndex(Request $request, $tagObjects, $paginationKey, $tagType, $pivotTableName, $tagIDField)
	{
		$messages = self::GetFlashedMessages($request);
		$orderAndSorting = self::GetIndexOrdering($request);
		$tagObjects = self::GetTagObjects($tagObjects, $paginationKey, $tagType, $pivotTableName, $tagIDField, $orderAndSorting);
		
		return View('tagObjects.'.$tagType.'.index', array($tagType => $tagObjects->appends(Input::except('page')), 'list_type' => $orderAndSorting['type'], 'list_order' => $orderAndSorting['order'], 'messages' => $messages));
	}
	
	protected static function GetConfiguration($tagType)
	{
		$configurations = Auth::user()->placeholder_configuration()->where('key', 'like', $tagType.'%')->get();
		
		$name = $configurations->where('key', '=', Config::get('constants.keys.placeholders.'.$tagType.'.name'))->first();
		$shortDescription = $configurations->where('key', '=', Config::get('constants.keys.placeholders.'.$tagType.'.shortDescription'))->first();
		$description = $configurations->where('key', '=', Config::get('constants.keys.placeholders.'.$tagType.'.description'))->first();
		$source = $configurations->where('key', '=', Config::get('constants.keys.placeholders.'.$tagType.'.source'))->first();
		$parent = $configurations->where('key', '=', Config::get('constants.keys.placeholders.'.$tagType.'.parent'))->first();
		$child = $configurations->where('key', '=', Config::get('constants.keys.placeholders.'.$tagType.'.child'))->first();
		
		$configurationsArray = array('name' => $name, 'shortDescription' => $shortDescription, 'description' => $description, 'source' => $source, 'child' => $child);
		
		if ($parent != null)
		{
			$configurationsArray = array_merge($configurationsArray, ['parent' => $parent]);
		}
		
		return $configurationsArray;
	}
	
	private static function GetIndexOrdering(Request $request)
	{
		$listType = trim(strtolower($request->input('type')));
		$listOrder = trim(strtolower($request->input('order')));
		
		if (($listType != Config::get('constants.sortingStringComparison.tagListType.usage')) 
			&& ($listType != Config::get('constants.sortingStringComparison.tagListType.alphabetic')))
		{
			$listType = Config::get('constants.sortingStringComparison.tagListType.usage');
		}
		
		if (($listOrder != Config::get('constants.sortingStringComparison.listOrder.ascending')) 
			&& ($listOrder != Config::get('constants.sortingStringComparison.listOrder.descending')))
		{
			if($listType == Config::get('constants.sortingStringComparison.tagListType.usage'))
			{
				$listOrder = Config::get('constants.sortingStringComparison.listOrder.ascending');
			}
			else
			{
				$listOrder = Config::get('constants.sortingStringComparison.listOrder.descending');
			}
		}
		
		return ['type' => $listType, 'order' => $listOrder];
	}
	
	private static function GetTagObjects($tagObjects, $paginationKey, $tagType, $pivotTableName, $tagIDField, $orderAndSorting)
	{
		$lookupKey = Config::get('constants.keys.pagination.'.$paginationKey);
		$paginationCount = ConfigurationLookupHelper::LookupPaginationConfiguration($lookupKey)->value;
		
		if ($orderAndSorting['type'] == Config::get('constants.sortingStringComparison.tagListType.alphabetic'))
		{
			$tagObjects = $tagObjects->orderBy('name', $orderAndSorting['order'])->paginate($paginationCount);
		}
		else
		{	
			$tagObjects = $tagObjects->join($pivotTableName, $tagType.'.id', '=', $pivotTableName.'.'.$tagIDField)->select($tagType.'.*', DB::raw('count(*) as total'))->groupBy('name')->orderBy('total', $orderAndSorting['order'])->orderBy('name', 'desc')->paginate($paginationCount);
		}
		
		return $tagObjects;
	}
}
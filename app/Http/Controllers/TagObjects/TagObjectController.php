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
use App\Models\Configuration\ConfigurationPlaceholder;

class TagObjectController extends WebController
{
	protected $aliasesPaginationKey;
	
	public function __construct()
    {
		parent::__construct();
	}
	
	protected function GetTagObjectIndex(Request $request, $tagObjects, $paginationKey, $tagType, $pivotTableName, $tagIDField)
	{
		$this->GetFlashedMessages($request);
		$orderAndSorting = self::GetIndexOrdering($request);
		$tagObjects = $this->GetTagObjects($tagObjects, $paginationKey, $tagType, $pivotTableName, $tagIDField, $orderAndSorting);
		
		return View('tagObjects.'.$tagType.'.index', array($tagType => $tagObjects->appends(Input::except('page')), 'list_type' => $orderAndSorting['type'], 'list_order' => $orderAndSorting['order'], 'messages' => $this->messages));
	}
	
	protected function DestroyTagObject($object, $objectType)
	{
		DB::beginTransaction();
		try
		{
			$objectName = $object->name;
		
			$parents = $object->parents()->get();
			$children = $object->children()->get();
			
			//Ensure passed through relationships are sustained after deleting the intermediary
			foreach ($parents as $parent)
			{
				foreach ($children as $child)
				{
					if ($parent->children()->where('id', '=', $child->id)->count() == 0)
					{
						$parent->children()->attach($child);
					}
				}
			}
			
			//Force deleting for now, build out functionality for soft deleting later.
			$object->forceDelete();
		}
		catch (\Exception $e)
		{
			DB::rollBack();
			$this->AddWarningMessage("Unable to successfully purge $objectType $objectName from the database.", ["$objectType" => $object->id, 'error' => $e]);
			return Redirect::back()->with(["messages" => $this->messages])->withInput();
		}
		DB::commit();
		
		$this->AddSuccessMessage("Successfully purged $objectType $objectName from the database.");
		return redirect()->route('index_collection')->with("messages", $this->messages);
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
	
	protected function GetAliasShowOrdering(Request $request)
	{
		$globalListOrder = trim(strtolower($request->input('global_order')));
		$personalListOrder = trim(strtolower($request->input('personal_order')));
		
		if (($globalListOrder != Config::get('constants.sortingStringComparison.listOrder.ascending')) 
			&& ($globalListOrder != Config::get('constants.sortingStringComparison.listOrder.descending')))
		{
			$globalListOrder = Config::get('constants.sortingStringComparison.listOrder.ascending');
		}
		
		if (($personalListOrder != Config::get('constants.sortingStringComparison.listOrder.ascending')) 
			&& ($personalListOrder != Config::get('constants.sortingStringComparison.listOrder.descending')))
		{
			$personalListOrder = Config::get('constants.sortingStringComparison.listOrder.ascending');
		}
		
		return ['global' => $globalListOrder, 'personal' => $personalListOrder];
		
	}
	
	private function GetTagObjects($tagObjects, $paginationKey, $tagType, $pivotTableName, $tagIDField, $orderAndSorting)
	{
		$paginationCount = ConfigurationLookupHelper::LookupPaginationConfiguration($this->paginationKey)->value;
		
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
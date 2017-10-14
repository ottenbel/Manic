<?php

namespace App\Http\Controllers\TagObjects\Series;

use App\Http\Controllers\TagObjects\TagObjectController;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Auth;
use DB;
use Input;
use Config;
use MappingHelper;
use ConfigurationLookupHelper;
use App\Models\TagObjects\Series\Series;
use App\Models\TagObjects\Series\SeriesAlias;
use App\Http\Requests\TagObjects\Series\StoreSeriesRequest;
use App\Http\Requests\TagObjects\Series\UpdateSeriesRequest;

class SeriesController extends TagObjectController
{
    public function index(Request $request)
    {
		$series = new Series();
		return self::GetTagObjectIndex($request, $series, 'seriesPerPageIndex', 'series', 'collection_series', 'series_id');
    }

    public function create(Request $request)
    {
		$this->authorize(Series::class);	
        $messages = self::GetFlashedMessages($request);
		$configurations = self::GetConfiguration('series');
		return View('tagObjects.series.create', array('configurations' => $configurations, 'messages' => $messages));
    }

    public function store(StoreSeriesRequest $request)
    {
		$series = new Series();
		return self::InsertOrUpdate($request, $series, 'created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Request $request, Series $series)
    {
        $messages = self::GetFlashedMessages($request);
		$aliasOrdering = self::GetAliasShowOrdering($request);
		$characterOrdering = self::GetCharacterShowOrdering($request);
		
		$lookupKey = Config::get('constants.keys.pagination.charactersPerPageSeries');
		$paginationCount = ConfigurationLookupHelper::LookupPaginationConfiguration($lookupKey)->value;
		
		if ($characterOrdering['type'] == Config::get('constants.sortingStringComparison.tagListType.alphabetic'))
		{
			$characters = $series->characters();
			$characters_output = $characters->orderBy('name', $characterOrdering['order'])->paginate($paginationCount, ['*'], 'character_page');

			$characters = $characters_output;
		}
		else
		{	
			$characters = $series->characters()	;
			$characters_used = $characters->join('character_collection', 'characters.id', '=', 'character_collection.character_id')->select('characters.*', DB::raw('count(*) as total'))->groupBy('name')->orderBy('total', $characterOrdering['order'])->orderBy('name', 'desc')->paginate($paginationCount, ['*'], 'character_page');
					
			$characters = $characters_used;
		}
		
		$lookupKey = Config::get('constants.keys.pagination.seriesAliasesPerPageParent');
		$paginationCount = ConfigurationLookupHelper::LookupPaginationConfiguration($lookupKey)->value;
		
		$global_aliases = $series->aliases()->where('user_id', '=', null)->orderBy('alias', $aliasOrdering['global'])->paginate($paginationCount, ['*'], 'global_alias_page');
		$global_aliases->appends(Input::except('global_alias_page'));
		
		$personal_aliases = null;
		
		if (Auth::check())
		{
			$personal_aliases = $series->aliases()->where('user_id', '=', Auth::user()->id)->orderBy('alias', $aliasOrdering['personal'])->paginate($paginationCount, ['*'], 'personal_alias_page');
			$personal_aliases->appends(Input::except('personal_alias_page'));
		}
		
		return View('tagObjects.series.show', array('series' => $series, 'characters' => $characters->appends(Input::except('character_page')), 'character_list_type' => $characterOrdering['type'], 'character_list_order' => $characterOrdering['order'], 'global_list_order' => $aliasOrdering['global'], 'personal_list_order' => $aliasOrdering['personal'], 'global_aliases' => $global_aliases, 'personal_aliases' => $personal_aliases, 'messages' => $messages));
    }
	
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $request, Series $series)
    {
		//Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize($series);
		$configurations = self::GetConfiguration('series');
				
        $messages = self::GetFlashedMessages($request);
		$aliasOrdering = self::GetAliasShowOrdering($request);
		
		$lookupKey = Config::get('constants.keys.pagination.seriesAliasesPerPageParent');
		$paginationCount = ConfigurationLookupHelper::LookupPaginationConfiguration($lookupKey)->value;
		
		$global_aliases = $series->aliases()->where('user_id', '=', null)->orderBy('alias', $aliasOrdering['global'])->paginate($paginationCount, ['*'], 'global_alias_page');
		$global_aliases->appends(Input::except('global_alias_page'));
		
		$personal_aliases = null;
		
		if (Auth::check())
		{
			$personal_aliases = $series->aliases()->where('user_id', '=', Auth::user()->id)->orderBy('alias', $aliasOrdering['personal'])->paginate($paginationCount, ['*'], 'personal_alias_page');
			$personal_aliases->appends(Input::except('personal_alias_page'));
		}
		
		$freeChildren = collect();
		$lockedChildren = collect();
		
		foreach ($series->children()->get() as $child)
		{
			//Check whether or not removing the parent child relationship can be done without breaking series/character relationship mapping on collections
			if (($child->children()->count() == 0) && ($child->usage_count() == 0))
			{
				$freeChildren->push($child);
			}
			else
			{
				$lockedChildren->push($child);
			}
		}
		
		return View('tagObjects.series.edit', array('configurations' => $configurations, 'series' => $series, 'freeChildren' => $freeChildren, 'lockedChildren' =>$lockedChildren, 'global_list_order' => $aliasOrdering['global'], 'personal_list_order' => $aliasOrdering['personal'], 'global_aliases' => $global_aliases, 'personal_aliases' => $personal_aliases, 'messages' => $messages));
    }

    public function update(UpdateSeriesRequest $request, Series $series)
    {
		return self::InsertOrUpdate($request, $series, 'updated');
    }

    public function destroy(Series $series)
    {
		$this->authorize($series);
        return self::DestroyTagObject($series, 'series');
    }
	
	private static function InsertOrUpdate($request, $series, $action)
	{
		SeriesAlias::where('alias', '=', trim(Input::get('name')))->delete();
		$series->fill($request->only(['name', 'short_description', 'description', 'url']));
		$series->save();
		
		$lockedChildren = collect();
		$children = $series->children()->get();
		
		foreach ($children as $child)
		{
			//Check whether or not removing the parent child relationship can be done without breaking series/character relationship mapping on collections
			if (!(($child->children()->count() == 0) && ($child->usage_count() == 0)))
			{
				$lockedChildren->push($child);
			}
		}
		
		$series->children()->detach();
		$seriesChildrenArray = array_unique(array_map('trim', explode(',', Input::get('series_child'))));
		$causedLoops = MappingHelper::MapSeriesChildren($series, $seriesChildrenArray, $lockedChildren);
		
		if (count($causedLoops))
		{	
			$childCausingLoopsMessage = "The following series (" . implode(", ", $causedLoops) . ") were not attached as children to " . $series->name . " as their addition would cause loops in tag implication.";
			
			$messages = self::BuildFlashedMessagesVariable(null, ["Partially $action series $series->name."], [$childCausingLoopsMessage]);
			return redirect()->route('show_series', ['series' => $series])->with("messages", $messages);
		}
		else
		{	
			$messages = self::BuildFlashedMessagesVariable(["Successfully $action series $series->name."], null, null);
			//Redirect to the series that was created
			return redirect()->route('show_series', ['series' => $series])->with("messages", $messages);
		}
	}
	
	private function GetCharacterShowOrdering($request)
	{
		$type = trim(strtolower($request->input('character_type')));
		$order = trim(strtolower($request->input('character_order')));
		
		if (($type != Config::get('constants.sortingStringComparison.tagListType.usage')) 
			&& ($type != Config::get('constants.sortingStringComparison.tagListType.alphabetic')))
		{
			$type = Config::get('constants.sortingStringComparison.tagListType.usage');
		}
		
		if (($order != Config::get('constants.sortingStringComparison.listOrder.ascending')) 
			&& ($order != Config::get('constants.sortingStringComparison.listOrder.descending')))
		{
			if($type == Config::get('constants.sortingStringComparison.tagListType.usage'))
			{
				$order = Config::get('constants.sortingStringComparison.listOrder.ascending');
			}
			else
			{
				$order = Config::get('constants.sortingStringComparison.listOrder.descending');
			}
		}
		
		return ['type' => $type, 'order' => $order];
	}
}

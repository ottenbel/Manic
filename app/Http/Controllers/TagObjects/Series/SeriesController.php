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

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
		//Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize(Series::class);
		
        $this->validate($request, [
			'name' => 'required|unique:series|regex:/^[^,:-]+$/',
			'url' => 'URL',
		]);
		
		$series = new Series();
		$series->name = trim(Input::get('name'));
		$series->short_description = trim(Input::get('short_description'));
		$series->description = trim(Input::get('description'));
		$series->url = trim(Input::get('url'));
		
		//Delete any series aliases that share the name with the artist to be created.
		$aliases_list = SeriesAlias::where('alias', '=', trim(Input::get('name')))->get();
		
		foreach ($aliases_list as $alias)
		{
			$alias->delete();
		}
		
		$series->save();
		
		$lockedChildren = collect();
		
		$series->children()->detach();
		$seriesChildrenArray = array_unique(array_map('trim', explode(',', Input::get('series_child'))));
		$causedLoops = MappingHelper::MapSeriesChildren($series, $seriesChildrenArray, $lockedChildren);
		
		if (count($causedLoops))
		{	
			$childCausingLoopsMessage = "The following series (" . implode(", ", $causedLoops) . ") were not attached as children to " . $series->name . " as their addition would cause loops in tag implication.";
			
			$messages = self::BuildFlashedMessagesVariable(null, ["Partially created series $series->name."], [$childCausingLoopsMessage]);
			return redirect()->route('show_series', ['series' => $series])->with("messages", $messages);
		}
		else
		{
			$messages = self::BuildFlashedMessagesVariable(["Successfully created series $series->name."], null, null);
			//Redirect to the series that was created
			return redirect()->route('show_series', ['series' => $series])->with("messages", $messages);
		}
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
		
		$characters_list_type = trim(strtolower($request->input('character_type')));
		$characters_list_order = trim(strtolower($request->input('character_order')));
		
		if (($characters_list_type != Config::get('constants.sortingStringComparison.tagListType.usage')) 
			&& ($characters_list_type != Config::get('constants.sortingStringComparison.tagListType.alphabetic')))
		{
			$characters_list_type = Config::get('constants.sortingStringComparison.tagListType.usage');
		}
		
		if (($characters_list_order != Config::get('constants.sortingStringComparison.listOrder.ascending')) 
			&& ($characters_list_order != Config::get('constants.sortingStringComparison.listOrder.descending')))
		{
			if($characters_list_type == Config::get('constants.sortingStringComparison.tagListType.usage'))
			{
				$characters_list_order = Config::get('constants.sortingStringComparison.listOrder.ascending');
			}
			else
			{
				$characters_list_order = Config::get('constants.sortingStringComparison.listOrder.descending');
			}
		}
		
		$lookupKey = Config::get('constants.keys.pagination.charactersPerPageSeries');
		$paginationCount = ConfigurationLookupHelper::LookupPaginationConfiguration($lookupKey)->value;
		
		if ($characters_list_type == Config::get('constants.sortingStringComparison.tagListType.alphabetic'))
		{
			$characters = $series->characters();
			$characters_output = $characters->orderBy('name', $characters_list_order)->paginate($paginationCount, ['*'], 'character_page');

			$characters = $characters_output;
		}
		else
		{	
			$characters = $series->characters()	;
			$characters_used = $characters->join('character_collection', 'characters.id', '=', 'character_collection.character_id')->select('characters.*', DB::raw('count(*) as total'))->groupBy('name')->orderBy('total', $characters_list_order)->orderBy('name', 'desc')->paginate($paginationCount, ['*'], 'character_page');
			
			//Leaving this code commented outhere until the paginator handling for union gets fixed in Laravel (this adds series that aren't used into the dataset used for popularity)
			
			/*$characters_not_used = $characters->leftjoin('character_collection', 'characters.id', '=', 'character_collection.character_id')->where('collection_id', '=', null)->select('characters.*', DB::raw('0 as total'))->groupBy('name');
			
			$characters_output = $characters_used->union($characters_not_used)->orderBy('total', $characters_list_order)->orderBy('name', 'desc')->get();*/
			
			$characters = $characters_used;
		}
		
		$global_list_order = trim(strtolower($request->input('global_order')));
		$personal_list_order = trim(strtolower($request->input('personal_order')));
		
		if (($global_list_order != Config::get('constants.sortingStringComparison.listOrder.ascending')) 
			&& ($global_list_order != 'desc'))
		{
			$global_list_order = Config::get('constants.sortingStringComparison.listOrder.ascending');
		}
		
		if (($personal_list_order != Config::get('constants.sortingStringComparison.listOrder.ascending')) 
			&& ($personal_list_order != 'desc'))
		{
			$personal_list_order = Config::get('constants.sortingStringComparison.listOrder.ascending');
		}
		
		$lookupKey = Config::get('constants.keys.pagination.seriesAliasesPerPageParent');
		$paginationCount = ConfigurationLookupHelper::LookupPaginationConfiguration($lookupKey)->value;
		
		$global_aliases = $series->aliases()->where('user_id', '=', null)->orderBy('alias', $global_list_order)->paginate($paginationCount, ['*'], 'global_alias_page');
		$global_aliases->appends(Input::except('global_alias_page'));
		
		$personal_aliases = null;
		
		if (Auth::check())
		{
			$personal_aliases = $series->aliases()->where('user_id', '=', Auth::user()->id)->orderBy('alias', $personal_list_order)->paginate($paginationCount, ['*'], 'personal_alias_page');
			$personal_aliases->appends(Input::except('personal_alias_page'));
		}
		
		return View('tagObjects.series.show', array('series' => $series, 'characters' => $characters->appends(Input::except('character_page')), 'character_list_type' => $characters_list_type, 'character_list_order' => $characters_list_order, 'global_list_order' => $global_list_order, 'personal_list_order' => $personal_list_order, 'global_aliases' => $global_aliases, 'personal_aliases' => $personal_aliases, 'messages' => $messages));
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
		
        $messages = self::GetFlashedMessages($request);
		$configurations = self::GetConfiguration('series');
		
		$global_list_order = trim(strtolower($request->input('global_order')));
		$personal_list_order = trim(strtolower($request->input('personal_order')));
		
		if (($global_list_order != Config::get('constants.sortingStringComparison.listOrder.ascending')) 
			&& ($global_list_order != Config::get('constants.sortingStringComparison.listOrder.descending')))
		{
			$global_list_order = Config::get('constants.sortingStringComparison.listOrder.ascending');
		}
		
		if (($personal_list_order != Config::get('constants.sortingStringComparison.listOrder.ascending')) 
			&& ($personal_list_order != Config::get('constants.sortingStringComparison.listOrder.descending')))
		{
			$personal_list_order = Config::get('constants.sortingStringComparison.listOrder.ascending');
		}
		
		$lookupKey = Config::get('constants.keys.pagination.seriesAliasesPerPageParent');
		$paginationCount = ConfigurationLookupHelper::LookupPaginationConfiguration($lookupKey)->value;
		
		$global_aliases = $series->aliases()->where('user_id', '=', null)->orderBy('alias', $global_list_order)->paginate($paginationCount, ['*'], 'global_alias_page');
		$global_aliases->appends(Input::except('global_alias_page'));
		
		$personal_aliases = null;
		
		if (Auth::check())
		{
			$personal_aliases = $series->aliases()->where('user_id', '=', Auth::user()->id)->orderBy('alias', $personal_list_order)->paginate($paginationCount, ['*'], 'personal_alias_page');
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
		
		return View('tagObjects.series.edit', array('configurations' => $configurations, 'series' => $series, 'freeChildren' => $freeChildren, 'lockedChildren' =>$lockedChildren, 'global_list_order' => $global_list_order, 'personal_list_order' => $personal_list_order, 'global_aliases' => $global_aliases, 'personal_aliases' => $personal_aliases, 'messages' => $messages));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, Series $series)
    {
		//Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize($series);
		
		$this->validate($request, [
		'name' => ['required',
					Rule::unique('series')->ignore($series->id),
					'regex:/^[^,:-]+$/'],
				'url' => 'URL',
		]);
		
		$series->name = trim(Input::get('name'));
		$series->short_description = trim(Input::get('short_description'));
		$series->description = trim(Input::get('description'));
		$series->url = trim(Input::get('url'));
		
		//Delete any series aliases that share the name with the artist to be created.
		$aliases_list = SeriesAlias::where('alias', '=', trim(Input::get('name')))->get();
		
		foreach ($aliases_list as $alias)
		{
			$alias->delete();
		}
		
		$series->save();
		
		$lockedChildren = collect();
		
		foreach ($series->children()->get() as $child)
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
			
			$messages = self::BuildFlashedMessagesVariable(null, ["Partially updated series $series->name."], [$childCausingLoopsMessage]);
			return redirect()->route('show_series', ['series' => $series])->with("messages", $messages);
		}
		else
		{	
			$messages = self::BuildFlashedMessagesVariable(["Successfully updated series $series->name."], null, null);
			//Redirect to the series that was created
			return redirect()->route('show_series', ['series' => $series])->with("messages", $messages);
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Series  $series
     * @return Response
     */
    public function destroy(Series $series)
    {
        //Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize($series);
		
		$seriesName = $series->name;
		
		//Force deleting for now, build out functionality for soft deleting later.
		$series->forceDelete();
		
		$messages = self::BuildFlashedMessagesVariable(["Successfully purged series $seriesName from the database."], null, null);
		return redirect()->route('index_collection')->with("messages", $messages);
    }
}

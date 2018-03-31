<?php

namespace App\Http\Controllers\TagObjects\Series;

use App\Http\Controllers\TagObjects\TagObjectController;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
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
	protected $childCharactersPaginationKey;
	
	public function __construct()
    {
		parent::__construct();
		
		$this->paginationKey = "pagination_series_per_page_index";
		$this->aliasesPaginationKey = "pagination_series_aliases_per_page_parent";
		$this->childCharactersPaginationKey = "pagination_characters_per_page_series";
		$this->placeholderStub = "series";
		$this->placeheldFields = array('name', 'short_description', 'description', 'source', 'child');
		
		$this->middleware('auth')->except(['index', 'show']);
		$this->middleware('permission:Create Series')->only(['create', 'store']);
		$this->middleware('permission:Edit Series')->only(['edit', 'update']);
		$this->middleware('permission:Delete Series')->only('destroy');
	}
	
    public function index(Request $request)
    {
		$series = new Series();
		return $this->GetTagObjectIndex($request, $series, 'seriesPerPageIndex', 'series', 'collection_series', 'series_id');
    }

    public function create(Request $request)
    {
		$this->authorize(Series::class);	
        $this->GetFlashedMessages($request);
		$configurations = $this->GetConfiguration();
		return View('tagObjects.series.create', array('configurations' => $configurations, 'messages' => $this->messages));
    }

    public function store(StoreSeriesRequest $request)
    {
		$series = new Series();
		return $this->InsertOrUpdate($request, $series, 'created', 'create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Request $request, Series $series)
    {
        $this->GetFlashedMessages($request);
		$aliasOrdering = $this->GetAliasShowOrdering($request);
		$characterOrdering = $this->GetCharacterShowOrdering($request);
		
		$paginationCount = ConfigurationLookupHelper::LookupPaginationConfiguration($this->childCharactersPaginationKey)->value;
		
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
		
		$paginationCount = ConfigurationLookupHelper::LookupPaginationConfiguration($this->aliasesPaginationKey)->value;
		
		$global_aliases = $series->aliases()->where('user_id', '=', null)->orderBy('alias', $aliasOrdering['global'])->paginate($paginationCount, ['*'], 'global_alias_page');
		$global_aliases->appends(Input::except('global_alias_page'));
		
		$personal_aliases = null;
		
		if (Auth::check())
		{
			$personal_aliases = $series->aliases()->where('user_id', '=', Auth::user()->id)->orderBy('alias', $aliasOrdering['personal'])->paginate($paginationCount, ['*'], 'personal_alias_page');
			$personal_aliases->appends(Input::except('personal_alias_page'));
		}
		
		return View('tagObjects.series.show', array('series' => $series, 'characters' => $characters->appends(Input::except('character_page')), 'character_list_type' => $characterOrdering['type'], 'character_list_order' => $characterOrdering['order'], 'global_list_order' => $aliasOrdering['global'], 'personal_list_order' => $aliasOrdering['personal'], 'global_aliases' => $global_aliases, 'personal_aliases' => $personal_aliases, 'messages' => $this->messages));
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
		$configurations = $this->GetConfiguration();
				
        $this->GetFlashedMessages($request);
		$aliasOrdering = $this->GetAliasShowOrdering($request);
		
		$paginationCount = ConfigurationLookupHelper::LookupPaginationConfiguration($this->aliasesPaginationKey)->value;
		
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
		
		return View('tagObjects.series.edit', array('configurations' => $configurations, 'series' => $series, 'freeChildren' => $freeChildren, 'lockedChildren' =>$lockedChildren, 'global_list_order' => $aliasOrdering['global'], 'personal_list_order' => $aliasOrdering['personal'], 'global_aliases' => $global_aliases, 'personal_aliases' => $personal_aliases, 'messages' => $this->messages));
    }

    public function update(UpdateSeriesRequest $request, Series $series)
    {
		return $this->InsertOrUpdate($request, $series, 'updated', 'update');
    }

    public function destroy(Series $series)
    {
		$this->authorize($series);
        return $this->DestroyTagObject($series, 'series');
    }
	
	private function InsertOrUpdate($request, $series, $action, $errorAction)
	{
		DB::beginTransaction();
		try
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
		}
		catch (\Exception $e)
		{
			DB::rollBack();
			
			$this->AddWarningMessage("Unable to successfully $errorAction series $series->name.");
			return Redirect::back()->with(["messages" => $this->messages])->withInput();
		}
		DB::commit();
		
		if (count($causedLoops))
		{	
			$childCausingLoopsMessage = "The following series (" . implode(", ", $causedLoops) . ") were not attached as children to " . $series->name . " as their addition would cause loops in tag implication.";
			
			$this->AddWarningMessage($childCausingLoopsMessage);
			$this->AddDataMessage("Partially $action series $series->name.");
			return redirect()->route('show_series', ['series' => $series])->with("messages", $this->messages);
		}
		else
		{	
			$this->AddSuccessMessage("Successfully $action series $series->name.");
			//Redirect to the series that was created
			return redirect()->route('show_series', ['series' => $series])->with("messages", $this->messages);
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

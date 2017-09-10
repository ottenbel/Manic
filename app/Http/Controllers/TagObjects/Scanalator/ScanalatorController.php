<?php

namespace App\Http\Controllers\TagObjects\Scanalator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Auth;
use DB;
use Input;
use Config;
use MappingHelper;
use ConfigurationLookupHelper;
use App\Models\TagObjects\Scanalator\Scanalator;
use App\Models\TagObjects\Scanalator\ScanalatorAlias;

class ScanalatorController extends Controller
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
	
		$scanalators = null;
		$scanalator_list_type = trim(strtolower($request->input('type')));
		$scanalator_list_order = trim(strtolower($request->input('order')));
		
		if (($scanalator_list_type != Config::get('constants.sortingStringComparison.tagListType.usage')) 
			&& ($scanalator_list_type != Config::get('constants.sortingStringComparison.tagListType.alphabetic')))
		{
			$scanalator_list_type = Config::get('constants.sortingStringComparison.tagListType.usage');
		}
		
		if (($scanalator_list_order != Config::get('constants.sortingStringComparison.listOrder.ascending')) 
			&& ($scanalator_list_order != Config::get('constants.sortingStringComparison.listOrder.descending')))
		{
			if($scanalator_list_type == Config::get('constants.sortingStringComparison.tagListType.usage'))
			{
				$scanalator_list_order = Config::get('constants.sortingStringComparison.listOrder.ascending');
			}
			else
			{
				$scanalator_list_order = Config::get('constants.sortingStringComparison.listOrder.descending');
			}
		}
		
		$lookupKey = Config::get('constants.keys.pagination.scanalatorsPerPageIndex');
		$paginationCount = ConfigurationLookupHelper::LookupPaginationConfiguration($lookupKey)->value;
		
		if ($scanalator_list_type == Config::get('constants.sortingStringComparison.tagListType.alphabetic'))
		{
			$scanalators = new Scanalator();	
			$scanalator_output = $scanalators->orderBy('name', $scanalator_list_order)->paginate($paginationCount);
			
			$scanalators = $scanalator_output;
		}
		else
		{	
			$scanalators = new Scanalator();
			$scanalators_used = $scanalators->join('chapter_scanalator', 'scanalators.id', '=', 'chapter_scanalator.scanalator_id')->select('scanalators.*', DB::raw('count(*) as total'))->groupBy('name')->orderBy('total', $scanalator_list_order)->orderBy('name', 'desc')->paginate($paginationCount);
			
			//Leaving this code commented outhere until the paginator handling for union gets fixed in Laravel (this adds scanalators that aren't used into the dataset used for popularity)
			
			/*$scanalators_not_used = $scanalators->leftjoin('chapter_scanalator', 'scanalators.id', '=', 'chapter_scanalator.scanalator_id')->where('collection_id', '=', null)->select('scanalators.*', DB::raw('0 as total'))->groupBy('name');
			
			$scanalator_output = $scanalators_used->union($scanalators_not_used)->orderBy('total', $scanalator_list_order)->orderBy('name', 'desc')->get();*/
			
			$scanalators = $scanalators_used;
		}		
		
		return View('tagObjects.scanalators.index', array('scanalators' => $scanalators->appends(Input::except('page')), 'list_type' => $scanalator_list_type, 'list_order' => $scanalator_list_order, 'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
		//Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize(Scanalator::class);
		
        $flashed_success = $request->session()->get('flashed_success');
		$flashed_data = $request->session()->get('flashed_data');
		$flashed_warning = $request->session()->get('flashed_warning');
		
		$configurations = self::GetConfiguration();
		
		return View('tagObjects.scanalators.create', array('configurations' => $configurations, 'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
		//Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize(Scanalator::class);
		
        $this->validate($request, [
			'name' => 'required|unique:scanalators|regex:/^[^,:-]+$/',
			'url' => 'URL',
		]);
		
		$scanalator = new Scanalator();
		$scanalator->name = trim(Input::get('name'));
		$scanalator->short_description = trim(Input::get('short_description'));
		$scanalator->description = trim(Input::get('description'));
		$scanalator->url = trim(Input::get('url'));
		
		//Delete any scanalator aliases that share the name with the artist to be created.
		$aliases_list = ScanalatorAlias::where('alias', '=', trim(Input::get('name')))->get();
		
		foreach ($aliases_list as $alias)
		{
			$alias->delete();
		}
		
		$scanalator->save();
		
		$scanalator->children()->detach();
		$scanalatorChildrenArray = array_unique(array_map('trim', explode(',', Input::get('scanalator_child'))));
		$causedLoops = MappingHelper::MapScanalatorChildren($scanalator, $scanalatorChildrenArray);
		
		if (count($causedLoops))
		{	
			$childCausingLoopsMessage = "The following scanalators (" . implode(", ", $causedLoops) . ") were not attached as children to " . $scanalator->name . " as their addition would cause loops in tag implication.";
			
			return redirect()->route('show_scanalator', ['scanalator' => $scanalator])->with("flashed_data", array("Partially updated scanalator $scanalator->name."))->with("flashed_warning", array($childCausingLoopsMessage));
		}
		else
		{
			//Redirect to the scanalator that was created
			return redirect()->route('show_scanalator', ['scanalator' => $scanalator])->with("flashed_success", array("Successfully created scanalator $scanalator->name."));
		}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Request $request, Scanalator $scanalator)
    {
        $flashed_success = $request->session()->get('flashed_success');
		$flashed_data = $request->session()->get('flashed_data');
		$flashed_warning = $request->session()->get('flashed_warning');
		
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
		
		$lookupKey = Config::get('constants.keys.pagination.scanalatorAliasesPerPageParent');
		$paginationCount = ConfigurationLookupHelper::LookupPaginationConfiguration($lookupKey)->value;
		
		$global_aliases = $scanalator->aliases()->where('user_id', '=', null)->orderBy('alias', $global_list_order)->paginate($paginationCount, ['*'], 'global_alias_page');
		$global_aliases->appends(Input::except('global_alias_page'));
		
		$personal_aliases = null;
		
		if (Auth::check())
		{
			$personal_aliases = $scanalator->aliases()->where('user_id', '=', Auth::user()->id)->orderBy('alias', $personal_list_order)->paginate($paginationCount, ['*'], 'personal_alias_page');
			$personal_aliases->appends(Input::except('personal_alias_page'));
		}
		
		return View('tagObjects.scanalators.show', array('scanalator' => $scanalator, 'global_list_order' => $global_list_order, 'personal_list_order' => $personal_list_order, 'global_aliases' => $global_aliases, 'personal_aliases' => $personal_aliases, 'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $request, Scanalator $scanalator)
    {
		//Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize($scanalator);
		
        $flashed_success = $request->session()->get('flashed_success');
		$flashed_data = $request->session()->get('flashed_data');
		$flashed_warning = $request->session()->get('flashed_warning');
		
		$configurations = self::GetConfiguration();
		
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
		
		$lookupKey = Config::get('constants.keys.pagination.scanalatorAliasesPerPageParent');
		$paginationCount = ConfigurationLookupHelper::LookupPaginationConfiguration($lookupKey)->value;
		
		$global_aliases = $scanalator->aliases()->where('user_id', '=', null)->orderBy('alias', $global_list_order)->paginate($paginationCount, ['*'], 'global_alias_page');
		$global_aliases->appends(Input::except('global_alias_page'));
		
		$personal_aliases = null;
		
		if (Auth::check())
		{
			$personal_aliases = $scanalator->aliases()->where('user_id', '=', Auth::user()->id)->orderBy('alias', $personal_list_order)->paginate($paginationCount, ['*'], 'personal_alias_page');
			$personal_aliases->appends(Input::except('personal_alias_page'));
		}
		
		return View('tagObjects.scanalators.edit', array('configurations' => $configurations, 'scanalator' => $scanalator, 'global_list_order' => $global_list_order, 'personal_list_order' => $personal_list_order, 'global_aliases' => $global_aliases, 'personal_aliases' => $personal_aliases, 'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, Scanalator $scanalator)
    {
		//Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize($scanalator);
		
		$this->validate($request, [
		'name' => ['required',
					Rule::unique('scanalators')->ignore($scanalator->id),
					'regex:/^[^,:-]+$/'],
				'url' => 'URL',
		]);
		
		$scanalator->name = trim(Input::get('name'));
		$scanalator->short_description = trim(Input::get('short_description'));
		$scanalator->description = trim(Input::get('description'));
		$scanalator->url = trim(Input::get('url'));
		
		//Delete any scanalator aliases that share the name with the artist to be created.
		$aliases_list = ScanalatorAlias::where('alias', '=', trim(Input::get('name')))->get();
		
		foreach ($aliases_list as $alias)
		{
			$alias->delete();
		}
		
		$scanalator->save();
		
		$scanalator->children()->detach();
		$scanalatorChildrenArray = array_unique(array_map('trim', explode(',', Input::get('scanalator_child'))));
		$causedLoops = MappingHelper::MapScanalatorChildren($scanalator, $scanalatorChildrenArray);
		
		if (count($causedLoops))
		{	
			$childCausingLoopsMessage = "The following scanalators (" . implode(", ", $causedLoops) . ") were not attached as children to " . $scanalator->name . " as their addition would cause loops in tag implication.";
			
			return redirect()->route('show_scanalator', ['scanalator' => $scanalator])->with("flashed_data", array("Partially created scanalator $scanalator->name."))->with("flashed_warning", array($childCausingLoopsMessage));
		}
		else
		{		
			//Redirect to the scanalator that was created
			return redirect()->route('show_scanalator', ['scanalator' => $scanalator])->with("flashed_success", array("Successfully updated scanalator $scanalator->name."));
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Scanalator  $scanalator
     * @return Response
     */
    public function destroy(Scanalator $scanalator)
    {
        //Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize($scanalator);
		
		$scanalatorName = $scanalator->name;
		
		$parents = $scanalator->parents()->get();
		$children = $scanalator->children()->get();
		
		//Ensure passed through relationships are sustained after deleting intermediary
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
		$scanalator->forceDelete();
		
		return redirect()->route('index_collection')->with("flashed_success", array("Successfully purged scanalator $scanalatorName from the database."));
    }
	
	private static function GetConfiguration()
	{
		$configurations = Auth::user()->placeholder_configuration()->where('key', 'like', 'scanalator%')->get();
		
		$name = $configurations->where('key', '=', Config::get('constants.keys.placeholders.scanalator.name'))->first();
		$shortDescription = $configurations->where('key', '=', Config::get('constants.keys.placeholders.scanalator.shortDescription'))->first();
		$description = $configurations->where('key', '=', Config::get('constants.keys.placeholders.scanalator.description'))->first();
		$source = $configurations->where('key', '=', Config::get('constants.keys.placeholders.scanalator.source'))->first();
		$parent = $configurations->where('key', '=', Config::get('constants.keys.placeholders.scanalator.parent'))->first();
		$child = $configurations->where('key', '=', Config::get('constants.keys.placeholders.scanalator.child'))->first();
		
		$configurationsArray = array('name' => $name, 'shortDescription' => $shortDescription, 'description' => $description, 'source' => $source, 'parent' => $parent, 'child' => $child);
		
		return $configurationsArray;
	}
}

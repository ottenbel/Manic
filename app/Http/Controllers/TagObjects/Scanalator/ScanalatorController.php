<?php

namespace App\Http\Controllers\TagObjects\Scanalator;

use App\Http\Controllers\TagObjects\TagObjectController;
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

class ScanalatorController extends TagObjectController
{
    public function index(Request $request)
    {
		$scanalators = new Scanalator();
		return self::GetTagObjectIndex($request, $scanalators, 'scanalatorsPerPageIndex', 'scanalators', 'chapter_scanalator', 'scanalator_id');
    }

    public function create(Request $request)
    {
		$this->authorize(Scanalator::class);	
        $messages = self::GetFlashedMessages($request);
		$configurations = self::GetConfiguration('scanalator');
		return View('tagObjects.scanalators.create', array('configurations' => $configurations, 'messages' => $messages));
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
			
			$messages = self::BuildFlashedMessagesVariable(null, ["Partially created scanalator $scanalator->name."], [$childCausingLoopsMessage]);
			return redirect()->route('show_scanalator', ['scanalator' => $scanalator])->with("messages", $messages);
		}
		else
		{
			$messages = self::BuildFlashedMessagesVariable(["Successfully created scanalator $scanalator->name."], null, null);
			//Redirect to the scanalator that was created
			return redirect()->route('show_scanalator', ['scanalator' => $scanalator])->with("messages", $messages);
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
        $messages = self::GetFlashedMessages($request);
		
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
		
		return View('tagObjects.scanalators.show', array('scanalator' => $scanalator, 'global_list_order' => $global_list_order, 'personal_list_order' => $personal_list_order, 'global_aliases' => $global_aliases, 'personal_aliases' => $personal_aliases, 'messages' => $messages));
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
		
        $messages = self::GetFlashedMessages($request);
		$configurations = self::GetConfiguration('scanalator');
		
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
		
		return View('tagObjects.scanalators.edit', array('configurations' => $configurations, 'scanalator' => $scanalator, 'global_list_order' => $global_list_order, 'personal_list_order' => $personal_list_order, 'global_aliases' => $global_aliases, 'personal_aliases' => $personal_aliases, 'messages' => $messages));
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
			
			$messages = self::BuildFlashedMessagesVariable(null, ["Partially updated scanalator $scanalator->name."], [$childCausingLoopsMessage]);
			return redirect()->route('show_scanalator', ['scanalator' => $scanalator])->with("messages", $messages);
		}
		else
		{	
			$messages = self::BuildFlashedMessagesVariable(["Successfully updated scanalator $scanalator->name."], null, null);
			//Redirect to the scanalator that was created
			return redirect()->route('show_scanalator', ['scanalator' => $scanalator])->with("messages", $messages);
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
		
		$messages = self::BuildFlashedMessagesVariable(["Successfully purged scanalator $scanalatorName from the database."], null, null);
		return redirect()->route('index_collection')->with("messages", $messages);
    }
	
	
}

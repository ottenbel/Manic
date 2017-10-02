<?php

namespace App\Http\Controllers\TagObjects\Character;

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
use App\Models\TagObjects\Character\Character;
use App\Models\TagObjects\Character\CharacterAlias;
use App\Models\TagObjects\Series\Series;
use App\Models\TagObjects\Series\SeriesAlias;

class CharacterController extends TagObjectController
{
    public function index(Request $request)
    {
		$characters = new Character();
		return self::GetTagObjectIndex($request, $characters, 'charactersPerPageIndex', 'characters', 'character_collection', 'character_id');
    }

    public function create(Request $request, Series $series = null)
    {
		$this->authorize(Character::class);	
        $messages = self::GetFlashedMessages($request);
		$configurations = self::GetConfiguration('character');
		return View('tagObjects.characters.create', array('configurations' => $configurations, 'series' => $series, 'messages' => $messages));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
		//Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize(Character::class);
		
		$this->validate($request, [
		'name' => 'required|unique:characters|regex:/^[^,:-]+$/',
				'url' => 'URL',
		]);
		
		$parent_series = Series::where('name', '=', trim(Input::get('parent_series')))->first();
		
		if ($parent_series == null)
		{
			return Redirect::back()->withErrors(['parent_series' => 'A character must have a valid parent series associated with it.'])->withInput();
		}
		
		$character = new Character();
		$character->name = trim(Input::get('name'));
		$character->short_description = trim(Input::get('short_description'));
		$character->description = trim(Input::get('description'));
		$character->url = trim(Input::get('url'));
		$character->series_id = $parent_series->id;
		
		//Delete any character aliases that share the name with the character to be created.
		$aliases_list = CharacterAlias::where('alias', '=', trim(Input::get('name')))->get();
		
		foreach ($aliases_list as $alias)
		{
			$alias->delete();
		}
		
		$character->save();
		
		$droppedChildren = array();
		
		$character->children()->detach();
		$characterChildrenArray = array_unique(array_map('trim', explode(',', Input::get('character_child'))));
		$causedLoops = MappingHelper::MapCharacterChildren($character, $characterChildrenArray, $droppedChildren);
		
		if ((count($causedLoops) > 0) || (count($droppedChildren) > 0))
		{
			$warnings = array();
			
			if (count($causedLoops) > 0)
			{
				$childCausingLoopsMessage = "The following characters (" . implode(", ", $causedLoops) . ") were not attached as children to " . $character->name . " as their addition would cause loops in tag implication.";
				array_push($warnings, $childCausingLoopsMessage);
			}
			
			if (count($droppedChildren) > 0)
			{
				$droppedChildrenMessage = "The following characters (" . implode(", ", $droppedChildren) . ") were not attached as children to " . $character->name . " as they could not be found attached to " . $character->series->name . " or a child series of it.";
				array_push($warnings, $droppedChildrenMessage);
			}
			
			$messages = self::BuildFlashedMessagesVariable(null, ["Partially created character $character->name."], $warnings);
			return redirect()->route('show_character', ['character' => $character])->with("messages", $messages);
		}
		else
		{
			$messages = self::BuildFlashedMessagesVariable(["Successfully created character $character->name under series $parent_series->name."], null, null);
			//Redirect to the character that was created
			return redirect()->route('show_character', ['character' => $character])->with("messages", $messages);
		}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Request $request, Character $character)
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
		
		$lookupKey = Config::get('constants.keys.pagination.characterAliasesPerPageParent');
		$paginationCount = ConfigurationLookupHelper::LookupPaginationConfiguration($lookupKey)->value;
		
		$global_aliases = $character->aliases()->where('user_id', '=', null)->orderBy('alias', $global_list_order)->paginate($paginationCount, ['*'], 'global_alias_page');
		$global_aliases ->appends(Input::except('global_alias_page'));
		
		$personal_aliases = null;
		
		if (Auth::check())
		{
			$personal_aliases = $character->aliases()->where('user_id', '=', Auth::user()->id)->orderBy('alias', $personal_list_order)->paginate($paginationCount, ['*'], 'personal_alias_page');
			$personal_aliases->appends(Input::except('personal_alias_page'));
		}
		
		return View('tagObjects.characters.show', array('character' => $character, 'global_list_order' => $global_list_order, 'personal_list_order' => $personal_list_order, 'global_aliases' => $global_aliases, 'personal_aliases' => $personal_aliases, 'messages' => $messages));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $request, Character $character)
    {
		//Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize($character);
		
        $messages = self::GetFlashedMessages($request);
		$configurations = self::GetConfiguration('GetConfiguration');
		
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
		
		$lookupKey = Config::get('constants.keys.pagination.characterAliasesPerPageParent');
		$paginationCount = ConfigurationLookupHelper::LookupPaginationConfiguration($lookupKey)->value;
		
		$global_aliases = $character->aliases()->where('user_id', '=', null)->orderBy('alias', $global_list_order)->paginate($paginationCount, ['*'], 'global_alias_page');
		$global_aliases->appends(Input::except('global_alias_page'));
		
		$personal_aliases = null;
		
		if (Auth::check())
		{
			$personal_aliases = $character->aliases()->where('user_id', '=', Auth::user()->id)->orderBy('alias', $personal_list_order)->paginate($paginationCount, ['*'], 'personal_alias_page');
			$personal_aliases->appends(Input::except('personal_alias_page'));
		}
		
		return View('tagObjects.characters.edit', array('configurations' => $configurations, 'character' => $character, 'global_list_order' => $global_list_order, 'personal_list_order' => $personal_list_order, 'global_aliases' => $global_aliases, 'personal_aliases' => $personal_aliases, 'messages' => $messages));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, Character $character)
    {
		//Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize($character);
		
		$this->validate($request, [
		'name' => ['required',
					Rule::unique('characters')->ignore($character->id),
					'regex:/^[^,:-]+$/'],
				'url' => 'URL',
		]);
		
		$character->name = trim(Input::get('name'));
		$character->short_description = trim(Input::get('short_description'));
		$character->description = trim(Input::get('description'));
		$character->url = trim(Input::get('url'));
		
		//Delete any character aliases that share the name with the character to be created.
		$aliases_list = CharacterAlias::where('alias', '=', trim(Input::get('name')))->get();
		
		foreach ($aliases_list as $alias)
		{
			$alias->delete();
		}
		
		$character->save();
		
		$droppedChildren = array();
		
		$character->children()->detach();
		$characterChildrenArray = array_unique(array_map('trim', explode(',', Input::get('character_child'))));
		$causedLoops = MappingHelper::MapCharacterChildren($character, $characterChildrenArray, $droppedChildren);
			
		$warnings = array();
		
		if ((count($causedLoops) > 0) || (count ($droppedChildren) > 0))
		{	
			if (count($causedLoops) > 0)
			{
				$childCausingLoopsMessage = "The following characters (" . implode(", ", $causedLoops) . ") were not attached as children to " . $character->name . " as their addition would cause loops in tag implication.";
				array_push($warnings, $childCausingLoopsMessage);
			}
			
			if (count($droppedChildren) > 0)
			{
				$droppedChildrenMessage = "The following characters (" . implode(", ", $droppedChildren) . ") were not attached as children to " . $character->name . " as they could not be found attached to " . $character->series->name . " or a child series of it.";
				array_push($warnings, $droppedChildrenMessage);
			}
			
			$messages = self::BuildFlashedMessagesVariable(null, ["Partially updated character $character->name."], $warnings);
			return redirect()->route('show_character', ['character' => $character])->with("messages", $messages);
		}
		else
		{
			$messages = self::BuildFlashedMessagesVariable(["Successfully updated character $character->name."], null, null);
			//Redirect to the character that was created
			return redirect()->route('show_character', [$character])->with("messages", $messages);
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Character  $character
     * @return Response
     */
    public function destroy(Character $character)
    {
        //Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize($character);
		
		$characterName = $character->name;
		
		$parents = $character->parents()->get();
		$children = $character->children()->get();
		
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
		
		$messages = self::BuildFlashedMessagesVariable(["Successfully purged character $characterName from the database."], null, null);
		//Force deleting for now, build out functionality for soft deleting later.
		$character->forceDelete();
		
		return redirect()->route('index_collection')->with("messages", $messages);
    }
}

<?php

namespace App\Http\Controllers\TagObjects\Character;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Auth;
use DB;
use Input;
use Config;
use MappingHelper;
use App\Models\TagObjects\Character\Character;
use App\Models\TagObjects\Character\CharacterAlias;
use App\Models\TagObjects\Series\Series;
use App\Models\TagObjects\Series\SeriesAlias;

class CharacterController extends Controller
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
		
		$characters = null;
		$character_list_type = trim(strtolower($request->input('type')));
		$character_list_order = trim(strtolower($request->input('order')));
		
		if (($character_list_type != Config::get('constants.sortingStringComparison.tagListType.usage')) 
			&& ($character_list_type != Config::get('constants.sortingStringComparison.tagListType.alphabetic')))
		{
			$character_list_type = Config::get('constants.sortingStringComparison.tagListType.usage');
		}
		
		if (($character_list_order != Config::get('constants.sortingStringComparison.listOrder.ascending')) 
			&& ($character_list_order != Config::get('constants.sortingStringComparison.listOrder.descending')))
		{
			if($character_list_type == Config::get('constants.sortingStringComparison.tagListType.usage'))
			{
				$character_list_order = Config::get('constants.sortingStringComparison.listOrder.ascending');
			}
			else
			{
				$character_list_order = Config::get('constants.sortingStringComparison.listOrder.descending');
			}
		}
		
		if ($character_list_type == Config::get('constants.sortingStringComparison.tagListType.alphabetic'))
		{
			$characters = new Character();
			$character_output = $characters->orderBy('name', $character_list_order)->paginate(Config::get('constants.pagination.tagObjectsPerPageIndex'));
			
			$characters = $character_output;
		}
		else
		{	
			$characters = new Character();
			$characters_used = $characters->join('character_collection', 'characters.id', '=', 'character_collection.character_id')->select('characters.*', DB::raw('count(*) as total'))->groupBy('name')->orderBy('total', $character_list_order)->orderBy('name', 'desc')->paginate(Config::get('constants.pagination.tagObjectsPerPageIndex'));
			
			//Leaving this code commented outhere until the paginator handling for union gets fixed in Laravel (this adds characters that aren't used into the dataset used for popularity)
			
			/*$characters_not_used = $characters->leftjoin('character_collection', 'characters.id', '=', 'character_collection.character_id')->where('collection_id', '=', null)->select('characters.*', DB::raw('0 as total'))->groupBy('name');
			
			$character_output = $characters_used->union($characters_not_used)->orderBy('total', $character_list_order)->orderBy('name', 'desc')->get();*/
			
			$characters = $characters_used;
		}		
		
		return View('tagObjects.characters.index', array('characters' => $characters->appends(Input::except('page')), 'list_type' => $character_list_type, 'list_order' => $character_list_order, 'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request, Series $series = null)
    {
		//Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize(Character::class);
		
        $flashed_success = $request->session()->get('flashed_success');
		$flashed_data = $request->session()->get('flashed_data');
		$flashed_warning = $request->session()->get('flashed_warning');
		
		return View('tagObjects.characters.create', array('series' => $series, 'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
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
		
		//Delete any character aliases that share the name with the artist to be created.
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
			
			return redirect()->route('show_character', ['character' => $character])->with("flashed_data", array("Partially created character $character->name."))->with("flashed_warning", $warnings);
		}
		else
		{
			//Redirect to the character that was created
			return redirect()->route('show_character', ['character' => $character])->with("flashed_success", array("Successfully created character $character->name under series $parent_series->name."));
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
		
		$global_aliases = $character->aliases()->where('user_id', '=', null)->orderBy('alias', $global_list_order)->paginate(Config::get('constants.pagination.tagAliasesPerPageParent'), ['*'], 'global_alias_page');
		$global_aliases ->appends(Input::except('global_alias_page'));
		
		$personal_aliases = null;
		
		if (Auth::check())
		{
			$personal_aliases = $character->aliases()->where('user_id', '=', Auth::user()->id)->orderBy('alias', $personal_list_order)->paginate(Config::get('constants.pagination.tagAliasesPerPageParent'), ['*'], 'personal_alias_page');
			$personal_aliases->appends(Input::except('personal_alias_page'));
		}
		
		return View('tagObjects.characters.show', array('character' => $character, 'global_list_order' => $global_list_order, 'personal_list_order' => $personal_list_order, 'global_aliases' => $global_aliases, 'personal_aliases' => $personal_aliases, 'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
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
		
		$global_aliases = $character->aliases()->where('user_id', '=', null)->orderBy('alias', $global_list_order)->paginate(Config::get('constants.pagination.tagAliasesPerPageParent'), ['*'], 'global_alias_page');
		$global_aliases->appends(Input::except('global_alias_page'));
		
		$personal_aliases = null;
		
		if (Auth::check())
		{
			$personal_aliases = $character->aliases()->where('user_id', '=', Auth::user()->id)->orderBy('alias', $personal_list_order)->paginate(Config::get('constants.pagination.tagAliasesPerPageParent'), ['*'], 'personal_alias_page');
			$personal_aliases->appends(Input::except('personal_alias_page'));
		}
		
		return View('tagObjects.characters.edit', array('character' => $character, 'global_list_order' => $global_list_order, 'personal_list_order' => $personal_list_order, 'global_aliases' => $global_aliases, 'personal_aliases' => $personal_aliases, 'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
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
			
			return redirect()->route('show_character', ['character' => $character])->with("flashed_data", array("Partially updated character $character->name."))->with("flashed_warning", $warnings);
		}
		else
		{
			//Redirect to the character that was created
			return redirect()->route('show_character', [$character])->with("flashed_success", array("Successfully updated character $character->name."));
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
		
		//Force deleting for now, build out functionality for soft deleting later.
		$character->forceDelete();
		
		return redirect()->route('index_collection')->with("flashed_success", array("Successfully purged character $characterName from the database."));
    }
}

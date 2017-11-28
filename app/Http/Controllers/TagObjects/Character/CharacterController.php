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
use App\Http\Requests\TagObjects\Character\StoreCharacterRequest;
use App\Http\Requests\TagObjects\Character\UpdateCharacterRequest;

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

    public function store(StoreCharacterRequest $request)
    {
		$parentSeries = Series::where('name', '=', trim(Input::get('parent_series')))->first();
		
		if ($parentSeries == null)
		{
			return Redirect::back()->withErrors(['parent_series' => 'A character must have a valid parent series associated with it.'])->withInput();
		}
		
		$character = new Character();
		$character->series_id = $parentSeries->id;
		
		return self::InsertOrUpdate($request, $character, 'created', 'create');
    }

    public function show(Request $request, Character $character)
    {
        return self::GetCharacterToDisplay($request, $character, 'show');
    }

    public function edit(Request $request, Character $character)
    {
		$this->authorize($character);
		$configurations = self::GetConfiguration('character');
        return self::GetCharacterToDisplay($request, $character, 'edit', $configurations);
    }

    public function update(UpdateCharacterRequest $request, Character $character)
    {		
		return self::InsertOrUpdate($request, $character, 'updated', 'update');
    }

    public function destroy(Character $character)
    {
		$this->authorize($character);
		return self::DestroyTagObject($character, 'character');
    }
	
	private static function InsertOrUpdate($request, $character, $action, $errorAction)
	{
		DB::beginTransaction();
		try
		{
			CharacterAlias::where('alias', '=', trim(Input::get('name')))->delete();
			$character->fill($request->only(['name', 'short_description', 'description', 'url']));
			$character->save();
			
			$droppedChildren = array();
			
			$character->children()->detach();
			$characterChildrenArray = array_unique(array_map('trim', explode(',', Input::get('character_child'))));
			$causedLoops = MappingHelper::MapCharacterChildren($character, $characterChildrenArray, $droppedChildren);
		}
		catch (\Exception $e)
		{
			DB::rollBack();
			$messages = self::BuildFlashedMessagesVariable(null, null, ["Unable to successfully $errorAction character $character->name."]);
			return Redirect::back()->with(["messages" => $messages])->withInput();
		}
		DB::commit();
		
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
			
			$seriesName = $character->series->name;
			$messages = self::BuildFlashedMessagesVariable(null, ["Partially $action character $character->name under series $seriesName."], $warnings);
			return redirect()->route('show_character', ['character' => $character])->with("messages", $messages);
		}
		else
		{
			$seriesName = $character->series->name;
			$messages = self::BuildFlashedMessagesVariable(["Successfully $action character $character->name under series $seriesName."], null, null);
			return redirect()->route('show_character', ['character' => $character])->with("messages", $messages);
		}
	}
	
	private static function GetCharacterToDisplay($request, $character, $route, $configurations = null)
	{
		$messages = self::GetFlashedMessages($request);
		$aliasOrdering = self::GetAliasShowOrdering($request);
		
		$lookupKey = Config::get('constants.keys.pagination.characterAliasesPerPageParent');
		$paginationCount = ConfigurationLookupHelper::LookupPaginationConfiguration($lookupKey)->value;
		
		$globalAliases = $character->aliases()->where('user_id', '=', null)->orderBy('alias', $aliasOrdering['global'])->paginate($paginationCount, ['*'], 'global_alias_page');
		$globalAliases->appends(Input::except('global_alias_page'));
		
		$personalAliases = null;
		
		if (Auth::check())
		{
			$personalAliases = $character->aliases()->where('user_id', '=', Auth::user()->id)->orderBy('alias', $aliasOrdering['personal'])->paginate($paginationCount, ['*'], 'personal_alias_page');
			$personalAliases->appends(Input::except('personal_alias_page'));
		}
		
		return View('tagObjects.characters.'.$route, array('configurations' => $configurations, 'character' => $character, 'global_list_order' => $aliasOrdering['global'], 'personal_list_order' => $aliasOrdering['personal'], 'global_aliases' => $globalAliases, 'personal_aliases' => $personalAliases, 'messages' => $messages));
	}
}

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
use App\Http\Requests\TagObjects\Scanalator\StoreScanalatorRequest;
use App\Http\Requests\TagObjects\Scanalator\UpdateScanalatorRequest;

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

    public function store(StoreScanalatorRequest $request)
    {
		$scanalator = new Scanalator();
		return self::InsertOrUpdate($request, $scanalator, 'created');
    }

    public function show(Request $request, Scanalator $scanalator)
    {
        return self::GetScanalatorToDisplay($request, $scanalator, 'show');
    }

    public function edit(Request $request, Scanalator $scanalator)
    {
		$this->authorize($scanalator);
		$configurations = self::GetConfiguration('scanalator');
        return self::GetScanalatorToDisplay($request, $scanalator, 'edit', $configurations);
    }

    public function update(UpdateScanalatorRequest $request, Scanalator $scanalator)
    {
		return self::InsertOrUpdate($request, $scanalator, 'update');
    }

    public function destroy(Scanalator $scanalator)
    {
		$this->authorize($scanalator);
        return self::DestroyTagObject($scanalator, 'scanalator');
    }
	
	private static function InsertOrUpdate($request, $scanalator, $action)
	{
		ScanalatorAlias::where('alias', '=', trim(Input::get('name')))->delete();
		$scanalator->fill($request->only(['name', 'short_description', 'description', 'url']));
		$scanalator->save();
		
		$scanalator->children()->detach();
		$scanalatorChildrenArray = array_unique(array_map('trim', explode(',', Input::get('scanalator_child'))));
		$causedLoops = MappingHelper::MapScanalatorChildren($scanalator, $scanalatorChildrenArray);
		
		if (count($causedLoops))
		{	
			$childCausingLoopsMessage = "The following scanalators (" . implode(", ", $causedLoops) . ") were not attached as children to " . $scanalator->name . " as their addition would cause loops in tag implication.";
			
			$messages = self::BuildFlashedMessagesVariable(null, ["Partially $action scanalator $scanalator->name."], [$childCausingLoopsMessage]);
			return redirect()->route('show_scanalator', ['scanalator' => $scanalator])->with("messages", $messages);
		}
		else
		{
			$messages = self::BuildFlashedMessagesVariable(["Successfully $action scanalator $scanalator->name."], null, null);
			return redirect()->route('show_scanalator', ['scanalator' => $scanalator])->with("messages", $messages);
		}
	}
	
	private static function GetScanalatorToDisplay($request, $scanalator, $route, $configurations = null)
	{
		$messages = self::GetFlashedMessages($request);
		$aliasOrdering = self::GetAliasShowOrdering($request);
		
		$lookupKey = Config::get('constants.keys.pagination.scanalatorAliasesPerPageParent');
		$paginationCount = ConfigurationLookupHelper::LookupPaginationConfiguration($lookupKey)->value;
		
		$global_aliases = $scanalator->aliases()->where('user_id', '=', null)->orderBy('alias', $aliasOrdering['global'])->paginate($paginationCount, ['*'], 'global_alias_page');
		$global_aliases->appends(Input::except('global_alias_page'));
		
		$personal_aliases = null;
		
		if (Auth::check())
		{
			$personal_aliases = $scanalator->aliases()->where('user_id', '=', Auth::user()->id)->orderBy('alias', $aliasOrdering['personal'])->paginate($paginationCount, ['*'], 'personal_alias_page');
			$personal_aliases->appends(Input::except('personal_alias_page'));
		}
		
		return View('tagObjects.scanalators.'.$route, array('configurations' => $configurations, 'scanalator' => $scanalator, 'global_list_order' => $aliasOrdering['global'], 'personal_list_order' => $aliasOrdering['personal'], 'global_aliases' => $global_aliases, 'personal_aliases' => $personal_aliases, 'messages' => $messages));
	}
}

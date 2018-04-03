<?php

namespace App\Http\Controllers\TagObjects\Scanalator;

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
use App\Models\TagObjects\Scanalator\Scanalator;
use App\Models\TagObjects\Scanalator\ScanalatorAlias;
use App\Http\Requests\TagObjects\Scanalator\StoreScanalatorRequest;
use App\Http\Requests\TagObjects\Scanalator\UpdateScanalatorRequest;

class ScanalatorController extends TagObjectController
{
	public function __construct()
    {
		parent::__construct();
		
		$this->paginationKey = "pagination_scanalators_per_page_index";
		$this->aliasesPaginationKey = "pagination_scanalator_aliases_per_page_parent";
		$this->placeholderStub = "scanalator";
		$this->placeheldFields = array('name', 'short_description', 'description', 'source', 'child');
		
		$this->middleware('auth')->except(['index', 'show']);
		$this->middleware('permission:Create Scanalator')->only(['create', 'store']);
		$this->middleware('permission:Edit Scanalator')->only(['edit', 'update']);
		$this->middleware('permission:Delete Scanalator')->only('destroy');
	}
	
    public function index(Request $request)
    {
		$scanalators = new Scanalator();
		return $this->GetTagObjectIndex($request, $scanalators, 'scanalatorsPerPageIndex', 'scanalators', 'chapter_scanalator', 'scanalator_id');
    }

    public function create(Request $request)
    {
		$this->authorize(Scanalator::class);	
        $this->GetFlashedMessages($request);
		$configurations = $this->GetConfiguration();
		return View('tagObjects.scanalators.create', array('configurations' => $configurations, 'messages' => $this->messages));
    }

    public function store(StoreScanalatorRequest $request)
    {
		$scanalator = new Scanalator();
		return $this->InsertOrUpdate($request, $scanalator, 'created', 'create');
    }

    public function show(Request $request, Scanalator $scanalator)
    {
        return $this->GetScanalatorToDisplay($request, $scanalator, 'show');
    }

    public function edit(Request $request, Scanalator $scanalator)
    {
		$this->authorize($scanalator);
		$configurations = $this->GetConfiguration();
        return $this->GetScanalatorToDisplay($request, $scanalator, 'edit', $configurations);
    }

    public function update(UpdateScanalatorRequest $request, Scanalator $scanalator)
    {
		return $this->InsertOrUpdate($request, $scanalator, 'updated', 'update');
    }

    public function destroy(Scanalator $scanalator)
    {
		$this->authorize($scanalator);
        return $this->DestroyTagObject($scanalator, 'scanalator');
    }
	
	private function InsertOrUpdate($request, $scanalator, $action, $errorAction)
	{
		DB::beginTransaction();
		try
		{
			ScanalatorAlias::where('alias', '=', trim(Input::get('name')))->delete();
			$scanalator->fill($request->only(['name', 'short_description', 'description', 'url']));
			$scanalator->save();
			
			$scanalator->children()->detach();
			$scanalatorChildrenArray = array_unique(array_map('trim', explode(',', Input::get('scanalator_child'))));
			$causedLoops = MappingHelper::MapScanalatorChildren($scanalator, $scanalatorChildrenArray);
		}
		catch (\Exception $e)
		{
			DB::rollBack();
			$this->AddWarningMessage("Unable to successfully $errorAction scanalator $scanalator->name.");
			return Redirect::back()->with(["messages" => $this->messages])->withInput();
		}
		DB::commit();
		
		if (count($causedLoops))
		{	
			$childCausingLoopsMessage = "The following scanalators (" . implode(", ", $causedLoops) . ") were not attached as children to " . $scanalator->name . " as their addition would cause loops in tag implication.";
			
			$this->AddWarningMessage($childCausingLoopsMessage);
			$this->AddDataMessage("Partially $action scanalator $scanalator->name.");
			
			return redirect()->route('show_scanalator', ['scanalator' => $scanalator])->with("messages", $this->messages);
		}
		else
		{
			$this->AddSuccessMessage("Successfully $action scanalator $scanalator->name.");
			return redirect()->route('show_scanalator', ['scanalator' => $scanalator])->with("messages", $this->messages);
		}
	}
	
	private function GetScanalatorToDisplay($request, $scanalator, $route, $configurations = null)
	{
		$this->GetFlashedMessages($request);
		$aliasOrdering = $this->GetAliasShowOrdering($request);
		
		$paginationCount = ConfigurationLookupHelper::LookupPaginationConfiguration($this->aliasesPaginationKey)->value;
		
		$global_aliases = $scanalator->aliases()->where('user_id', '=', null)->orderBy('alias', $aliasOrdering['global'])->paginate($paginationCount, ['*'], 'global_alias_page');
		$global_aliases->appends(Input::except('global_alias_page'));
		
		$personal_aliases = null;
		
		if (Auth::check())
		{
			$personal_aliases = $scanalator->aliases()->where('user_id', '=', Auth::user()->id)->orderBy('alias', $aliasOrdering['personal'])->paginate($paginationCount, ['*'], 'personal_alias_page');
			$personal_aliases->appends(Input::except('personal_alias_page'));
		}
		
		return View('tagObjects.scanalators.'.$route, array('configurations' => $configurations, 'scanalator' => $scanalator, 'global_list_order' => $aliasOrdering['global'], 'personal_list_order' => $aliasOrdering['personal'], 'global_aliases' => $global_aliases, 'personal_aliases' => $personal_aliases, 'messages' => $this->messages));
	}
}

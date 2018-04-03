<?php

namespace App\Http\Controllers\TagObjects\Tag;

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
use App\Models\TagObjects\Tag\Tag;
use App\Models\TagObjects\Tag\TagAlias;
use App\Http\Requests\TagObjects\Tag\StoreTagRequest;
use App\Http\Requests\TagObjects\Tag\UpdateTagRequest;

class TagController extends TagObjectController
{
	public function __construct()
    {
		parent::__construct();
		
		$this->paginationKey = "pagination_tags_per_page_index";
		$this->aliasesPaginationKey = "pagination_tag_aliases_per_page_parent";
		$this->placeholderStub = "tag";
		$this->placeheldFields = array('name', 'short_description', 'description', 'source', 'child');
		
		$this->middleware('auth')->except(['index', 'show']);
		$this->middleware('permission:Create Tag')->only(['create', 'store']);
		$this->middleware('permission:Edit Tag')->only(['edit', 'update']);
		$this->middleware('permission:Delete Tag')->only('destroy');
	}
	
    public function index(Request $request)
    {
		$tags = new Tag();
		return $this->GetTagObjectIndex($request, $tags, 'tagsPerPageIndex', 'tags', 'collection_tag', 'tag_id');
    }

    public function create(Request $request)
    {
		$this->authorize(Tag::class);	
		
        $this->GetFlashedMessages($request);
		$configurations = $this->GetConfiguration();
		
		return View('tagObjects.tags.create', array('configurations' => $configurations, 'messages' => $this->messages));
    }

    public function store(StoreTagRequest $request)
    {
		$tag = new Tag();
		return $this->InsertOrUpdate($request, $tag, 'created', 'create');
    }
	
    public function show(Request $request, Tag $tag)
    {
        return $this->GetTagToDisplay($request, $tag, 'show');
    }

    public function edit(Request $request, Tag $tag)
    {
		//Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize($tag);
		
		$configurations = $this->GetConfiguration();
		return $this->GetTagToDisplay($request, $tag, 'edit', $configurations);
    }

    public function update(UpdateTagRequest $request, Tag $tag)
    {
		return $this->InsertOrUpdate($request, $tag, 'updated', 'update');
    }

    public function destroy(Tag $tag)
    {
		$this->authorize($tag);
		return $this->DestroyTagObject($tag, 'tag');
    }
	
	private function InsertOrUpdate($request, $tag, $action, $errorAction)
	{
		DB::beginTransaction();
		try
		{
			TagAlias::where('alias', '=', trim(Input::get('name')))->delete();
			$tag->fill($request->only(['name', 'short_description', 'description', 'url']));
			$tag->save();
			
			$tag->children()->detach();
			$tagChildrenArray = array_unique(array_map('trim', explode(',', Input::get('tag_child'))));
			$causedLoops = MappingHelper::MapTagChildren($tag, $tagChildrenArray);
		}
		catch (\Exception $e)
		{
			DB::rollBack();
			$this->AddWarningMessage("Unable to successfully $errorAction tag $tag->name.");
			return Redirect::back()->with(["messages" => $this->messages])->withInput();
		}
		DB::commit();
		
		if (count($causedLoops))
		{	
			$childCausingLoopsMessage = "The following tags (" . implode(", ", $causedLoops) . ") were not attached as children to " . $tag->name . " as their addition would cause loops in tag implication.";
			
			$this->AddWarningMessage($childCausingLoopsMessage);
			$this->AddDataMessage("Partially $action tag $tag->name.");
			return redirect()->route('show_tag', ['tag' => $tag])->with("messages", $this->messages);
		}
		else
		{
			$this->AddSuccessMessage("Successfully $action tag $tag->name.");
			return redirect()->route('show_tag', ['tag' => $tag])->with("messages", $this->messages);
		}
	}
	
	private function GetTagToDisplay($request, $tag, $route, $configurations = null)
	{
		$this->GetFlashedMessages($request);
		$aliasOrdering = $this->GetAliasShowOrdering($request);
		
		$paginationCount = ConfigurationLookupHelper::LookupPaginationConfiguration($this->aliasesPaginationKey)->value;
		
		$globalAliases = $tag->aliases()->where('user_id', '=', null)->orderBy('alias', $aliasOrdering['global'])->paginate($paginationCount, ['*'], 'global_alias_page');
		$globalAliases->appends(Input::except('global_alias_page'));
		
		$personalAliases = null;
		
		if (Auth::check())
		{
			$personalAliases = $tag->aliases()->where('user_id', '=', Auth::user()->id)->orderBy('alias', $aliasOrdering['personal'])->paginate($paginationCount, ['*'], 'personal_alias_page');
			$personalAliases->appends(Input::except('personal_alias_page'));
		}
		
		return View('tagObjects.tags.'.$route, array('configurations' => $configurations, 'tag' => $tag, 'global_list_order' => $aliasOrdering['global'], 'personal_list_order' => $aliasOrdering['personal'], 'global_aliases' => $globalAliases, 'personal_aliases' => $personalAliases, 'messages' => $this->messages));
	}
}

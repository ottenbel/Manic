<?php

namespace App\Http\Controllers\TagObjects\Tag;

use App\Http\Controllers\TagObjects\TagObjectController;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
    public function index(Request $request)
    {
		$tags = new Tag();
		return self::GetTagObjectIndex($request, $tags, 'tagsPerPageIndex', 'tags', 'collection_tag', 'tag_id');
    }

    public function create(Request $request)
    {
		$this->authorize(Tag::class);	
        $messages = self::GetFlashedMessages($request);
		$configurations = self::GetConfiguration('tag');
		return View('tagObjects.tags.create', array('configurations' => $configurations, 'messages' => $messages));
    }

    public function store(StoreTagRequest $request)
    {
		$tag = new Tag();
		return self::CreateOrUpdateTag($request, $tag, 'created');
    }
	
    public function show(Request $request, Tag $tag)
    {
        return self::GetTagToDisplay($request, $tag, 'show');
    }

    public function edit(Request $request, Tag $tag)
    {
		//Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize($tag);
		$configurations = self::GetConfiguration('tag');
		return self::GetTagToDisplay($request, $tag, 'edit', $configurations);
    }

    public function update(UpdateTagRequest $request, Tag $tag)
    {
		return self::CreateOrUpdateTag($request, $tag, 'updated');
    }

    public function destroy(Tag $tag)
    {
		$this->authorize($tag);
		return self::DestroyTagObject($tag, 'tag');
    }
	
	protected static function CreateOrUpdateTag($request, $tag, $action)
	{
		TagAlias::where('alias', '=', trim(Input::get('name')))->delete();
		$tag->fill($request->only(['name', 'short_description', 'description', 'url']));
		$tag->save();
		
		$tag->children()->detach();
		$tagChildrenArray = array_unique(array_map('trim', explode(',', Input::get('tag_child'))));
		$causedLoops = MappingHelper::MapTagChildren($tag, $tagChildrenArray);
		
		if (count($causedLoops))
		{	
			$childCausingLoopsMessage = "The following tags (" . implode(", ", $causedLoops) . ") were not attached as children to " . $tag->name . " as their addition would cause loops in tag implication.";
			
			$messages = self::BuildFlashedMessagesVariable(null, ["Partially $action tag $tag->name."], [$childCausingLoopsMessage]);
			return redirect()->route('show_tag', ['tag' => $tag])->with("messages", $messages);
		}
		else
		{
			$messages = self::BuildFlashedMessagesVariable(["Successfully $action tag $tag->name."], null, null);
			return redirect()->route('show_tag', ['tag' => $tag])->with("messages", $messages);
		}
	}
	
	protected static function GetTagToDisplay($request, $tag, $route, $configurations = null)
	{
		$messages = self::GetFlashedMessages($request);
		$aliasOrdering = self::GetAliasShowOrdering($request);
		
		$lookupKey = Config::get('constants.keys.pagination.tagAliasesPerPageParent');
		$paginationCount = ConfigurationLookupHelper::LookupPaginationConfiguration($lookupKey)->value;
		
		$globalAliases = $tag->aliases()->where('user_id', '=', null)->orderBy('alias', $aliasOrdering['global'])->paginate($paginationCount, ['*'], 'global_alias_page');
		$globalAliases->appends(Input::except('global_alias_page'));
		
		$personalAliases = null;
		
		if (Auth::check())
		{
			$personalAliases = $tag->aliases()->where('user_id', '=', Auth::user()->id)->orderBy('alias', $aliasOrdering['personal'])->paginate($paginationCount, ['*'], 'personal_alias_page');
			$personalAliases->appends(Input::except('personal_alias_page'));
		}
		
		return View('tagObjects.tags.'.$route, array('configurations' => $configurations, 'tag' => $tag, 'global_list_order' => $aliasOrdering['global'], 'personal_list_order' => $aliasOrdering['personal'], 'global_aliases' => $globalAliases, 'personal_aliases' => $personalAliases, 'messages' => $messages));
	}
}

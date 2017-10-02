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

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
		//Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize(Tag::class);
		
        $this->validate($request, [
			'name' => 'required|unique:tags|regex:/^[^,:-]+$/',
			'url' => 'URL',
		]);
		
		$tag = new Tag();
		$tag->name = trim(Input::get('name'));
		$tag->short_description = trim(Input::get('short_description'));
		$tag->description = trim(Input::get('description'));
		$tag->url = trim(Input::get('url'));
		
		//Delete any tag aliases that share the name with the artist to be created.
		$aliases_list = TagAlias::where('alias', '=', trim(Input::get('name')))->get();
		
		foreach ($aliases_list as $alias)
		{
			$alias->delete();
		}
		
		$tag->save();
		
		$tag->children()->detach();
		$tagChildrenArray = array_unique(array_map('trim', explode(',', Input::get('tag_child'))));
		$causedLoops = MappingHelper::MapTagChildren($tag, $tagChildrenArray);
		
		if (count($causedLoops))
		{	
			$childCausingLoopsMessage = "The following tags (" . implode(", ", $causedLoops) . ") were not attached as children to " . $tag->name . " as their addition would cause loops in tag implication.";
			
			$messages = self::BuildFlashedMessagesVariable(null, ["Partially created tag $tag->name."], [$childCausingLoopsMessage]);
			return redirect()->route('show_tag', ['tag' => $tag])->with("messages", $messages);
		}
		else
		{
			$messages = self::BuildFlashedMessagesVariable(["Successfully created tag $tag->name."], null, null);
			//Redirect to the tag that was created
			return redirect()->route('show_tag', ['tag' => $tag])->with("messages", $messages);
		}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Request $request, Tag $tag)
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
		
		$lookupKey = Config::get('constants.keys.pagination.tagAliasesPerPageParent');
		$paginationCount = ConfigurationLookupHelper::LookupPaginationConfiguration($lookupKey)->value;
		
		$global_aliases = $tag->aliases()->where('user_id', '=', null)->orderBy('alias', $global_list_order)->paginate($paginationCount, ['*'], 'global_alias_page');
		$global_aliases->appends(Input::except('global_alias_page'));
		
		$personal_aliases = null;
		
		if (Auth::check())
		{
			$personal_aliases = $tag->aliases()->where('user_id', '=', Auth::user()->id)->orderBy('alias', $personal_list_order)->paginate($paginationCount, ['*'], 'personal_alias_page');
			$personal_aliases->appends(Input::except('personal_alias_page'));
		}
		
		return View('tagObjects.tags.show', array('tag' => $tag, 'global_list_order' => $global_list_order, 'personal_list_order' => $personal_list_order, 'global_aliases' => $global_aliases, 'personal_aliases' => $personal_aliases, 'messages' => $messages));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $request, Tag $tag)
    {
		//Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize($tag);
		
        $messages = self::GetFlashedMessages($request);
		$configurations = self::GetConfiguration('tag');
		
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
		
		$lookupKey = Config::get('constants.keys.pagination.tagAliasesPerPageParent');
		$paginationCount = ConfigurationLookupHelper::LookupPaginationConfiguration($lookupKey)->value;
		
		$global_aliases = $tag->aliases()->where('user_id', '=', null)->orderBy('alias', $global_list_order)->paginate($paginationCount, ['*'], 'global_alias_page');
		$global_aliases->appends(Input::except('global_alias_page'));
		
		$personal_aliases = null;
		
		if (Auth::check())
		{
			$personal_aliases = $tag->aliases()->where('user_id', '=', Auth::user()->id)->orderBy('alias', $personal_list_order)->paginate($paginationCount, ['*'], 'personal_alias_page');
			$personal_aliases->appends(Input::except('personal_alias_page'));
		}
		
		return View('tagObjects.tags.edit', array('configurations' => $configurations, 'tag' => $tag, 'global_list_order' => $global_list_order, 'personal_list_order' => $personal_list_order, 'global_aliases' => $global_aliases, 'personal_aliases' => $personal_aliases, 'messages' => $messages));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, Tag $tag)
    {
		//Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize($tag);
		
		$this->validate($request, [
		'name' => ['required',
					Rule::unique('tags')->ignore($tag->id),
					'regex:/^[^,:-]+$/'],
				'url' => 'URL',
		]);
		
		$tag->name = trim(Input::get('name'));
		$tag->short_description = trim(Input::get('short_description'));
		$tag->description = trim(Input::get('description'));
		$tag->url = trim(Input::get('url'));
		
		//Delete any tag aliases that share the name with the artist to be created.
		$aliases_list = TagAlias::where('alias', '=', trim(Input::get('name')))->get();
		
		foreach ($aliases_list as $alias)
		{
			$alias->delete();
		}
		
		$tag->save();
		
		$tag->children()->detach();
		$tagChildrenArray = array_unique(array_map('trim', explode(',', Input::get('tag_child'))));
		$causedLoops = MappingHelper::MapTagChildren($tag, $tagChildrenArray);
		
		if (count($causedLoops))
		{	
			$childCausingLoopsMessage = "The following tags (" . implode(", ", $causedLoops) . ") were not attached as children to " . $tag->name . " as their addition would cause loops in tag implication.";
			
			$messages = self::BuildFlashedMessagesVariable(null, ["Partially updated tag $tag->name."], [$childCausingLoopsMessage]);
			return redirect()->route('show_tag', ['tag' => $tag])->with("messages", $messages);
		}
		else
		{
			$messages = self::BuildFlashedMessagesVariable(["Successfully updated tag $tag->name."], null, null);
			//Redirect to the tag that was created
			return redirect()->route('show_tag', ['tag' => $tag])->with("messages", $messages);
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Tag  $tag
     * @return Response
     */
    public function destroy(Tag $tag)
    {
		//Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
        $this->authorize($tag);
		
		$tagName = $tag->name;
		
		$parents = $tag->parents()->get();
		$children = $tag->children()->get();
		
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
		$tag->forceDelete();
		
		$messages = self::BuildFlashedMessagesVariable(["Successfully purged tag $tagName from the database."], null, null);
		return redirect()->route('index_collection')->with("messages", $messages);
    }
}

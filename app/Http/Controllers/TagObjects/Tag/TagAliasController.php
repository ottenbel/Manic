<?php

namespace App\Http\Controllers\TagObjects\Tag;

use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Auth;
use DB;
use Input;
use Config;
use ConfigurationLookupHelper;
use App\Models\TagObjects\Tag\TagAlias;
use App\Models\TagObjects\Tag\Tag;

class TagAliasController extends WebController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
		$messages = self::GetFlashedMessages($request);
		
		$alias_list_type = trim(strtolower($request->input('type')));
		$alias_list_order = trim(strtolower($request->input('order')));
		
		if (($alias_list_type != Config::get('constants.sortingStringComparison.aliasListType.global')) 
			&& ($alias_list_type != Config::get('constants.sortingStringComparison.aliasListType.personal')) 
			&& ($alias_list_type != Config::get('constants.sortingStringComparison.aliasListType.mixed')))
		{
			$alias_list_type = Config::get('constants.sortingStringComparison.aliasListType.mixed');
		}
		
		if (($alias_list_order != Config::get('constants.sortingStringComparison.listOrder.ascending')) 
			&& ($alias_list_order != Config::get('constants.sortingStringComparison.listOrder.descending')))
		{
			$alias_list_order = Config::get('constants.sortingStringComparison.listOrder.ascending');
		}
		
		$aliases = new TagAlias();
		
		$lookupKey = Config::get('constants.keys.pagination.tagAliasesPerPageIndex');
		$paginationCount = ConfigurationLookupHelper::LookupPaginationConfiguration($lookupKey)->value;
		
		if (Auth::user())
		{
			if ($alias_list_type == Config::get('constants.sortingStringComparison.aliasListType.global'))
			{
				$aliases = $aliases->where('user_id', '=', null)->orderBy('alias', $alias_list_order)->paginate($paginationCount);
			}
			
			if ($alias_list_type == Config::get('constants.sortingStringComparison.aliasListType.personal'))
			{
				$aliases = $aliases->where('user_id', '=', Auth::user()->id)->orderBy('alias', $alias_list_order)->paginate($paginationCount);
			}
			
			if ($alias_list_type == Config::get('constants.sortingStringComparison.aliasListType.mixed'))
			{
				$aliases = $aliases->where('user_id', '=', null)->orWhere('user_id', '=', Auth::user()->id)->orderBy('alias', $alias_list_order)->paginate($paginationCount);
			}
		}
		else
		{
			$aliases = new TagAlias();
			$aliases = $aliases->where('user_id', '=', null)->orderBy('alias', $alias_list_order)->paginate($paginationCount);
		}
		
		return View('tagObjects.tags.alias.index', array('aliases' => $aliases->appends(Input::except('page')), 'list_type' => $alias_list_type, 'list_order' => $alias_list_order, 'messages' => $messages));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request, Tag $tag)
    {
        $isGlobalAlias = Input::get('is_global_alias');
		$isPersonalAlias = Input::get('is_personal_alias');
		if ($isGlobalAlias)
		{
			//Define authorization in the controller as global variables can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
			$this->authorize([TagAlias::class, true]);
			
			$this->validate($request, [
				'global_alias' => 'required|unique:tags,name|unique:tag_alias,alias,null,null,user_id,NULL|regex:/^[^,:-]+$/']);
		}
		else if ($isPersonalAlias)
		{
			//Define authorization in the controller as global variables can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
			$this->authorize([TagAlias::class, false]);
			
			$this->validate($request, [
				'personal_alias' => 'required|unique:tags,name|unique:tag_alias,alias,null,null,user_id,'.Auth::user()->id.'|regex:/^[^,:-]+$/']);
		}
		
		$tagAlias = new TagAlias();
		$tagAlias->tag_id = $tag->id;
		 
        if ($isGlobalAlias)
		{
			$tagAlias->alias = Input::get('global_alias');
			$tagAlias->user_id = null;
		}
		else
		{
			$tagAlias->alias = Input::get('personal_alias');
			$tagAlias->user_id = Auth::user()->id;
		}
				
		$tagAlias->save();
		
		$messages = self::BuildFlashedMessagesVariable(["Successfully created alias $tagAlias->alias on tag $tag->name."], null, null);
		//Redirect to the tag that the alias was created for
		return redirect()->route('show_tag', ['tag' => $tag])->with("messages", $messages);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  TagAlias  $tagAlias
     * @return Response
     */
    public function destroy(TagAlias $tagAlias)
    {
        //Define authorization in the controller as global variables can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize($tagAlias);
		
		$tag = $tagAlias->tag_id;
		
		//Force deleting for now, build out functionality for soft deleting later.
		$tagAlias->forceDelete();
		
		$messages = self::BuildFlashedMessagesVariable(["Successfully purged alias from tag."], null, null);
		//redirect to the tag that the alias existed for
		return redirect()->route('show_tag', ['tag' => $tag])->with("messages", $messages);
    }
}

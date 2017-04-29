<?php

namespace App\Http\Controllers\TagObjects\Tag;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Auth;
use DB;
use Input;
use Config;
use App\Models\TagObjects\Tag\Tag;
use App\Models\TagObjects\Tag\TagAlias;

class TagController extends Controller
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
	
		$tags = null;
		$tag_list_type = trim(strtolower($request->input('type')));
		$tag_list_order = trim(strtolower($request->input('order')));
		
		if (($tag_list_type != Config::get('constants.sortingStringComparison.tagListType.usage')) 
			&& ($tag_list_type != Config::get('constants.sortingStringComparison.tagListType.alphabetic')))
		{
			$tag_list_type = Config::get('constants.sortingStringComparison.tagListType.usage');
		}
		
		if (($tag_list_order != Config::get('constants.sortingStringComparison.listOrder.ascending')) 
			&& ($tag_list_order != Config::get('constants.sortingStringComparison.listOrder.descending')))
		{
			if($tag_list_type == Config::get('constants.sortingStringComparison.tagListType.usage'))
			{
				$tag_list_order = Config::get('constants.sortingStringComparison.listOrder.ascending');
			}
			else
			{
				$tag_list_order = Config::get('constants.sortingStringComparison.listOrder.descending');
			}
		}
		
		if ($tag_list_type == Config::get('constants.sortingStringComparison.tagListType.alphabetic'))
		{
			$tags = new Tag();
			$tag_output = $tags->orderBy('name', $tag_list_order)->paginate(Config::get('constants.pagination.tagObjectsPerPageIndex'));
			
			$tags = $tag_output;
		}
		else
		{	
			$tags = new Tag();
			$tags_used = $tags->join('collection_tag', 'tags.id', '=', 'collection_tag.tag_id')->select('tags.*', DB::raw('count(*) as total'))->groupBy('name')->orderBy('total', $tag_list_order)->orderBy('name', 'desc')->paginate(Config::get('constants.pagination.tagObjectsPerPageIndex'));
			
			//Leaving this code commented outhere until the paginator handling for union gets fixed in Laravel (this adds tags that aren't used into the dataset used for popularity)
			
			/*$tags_not_used = $tags->leftjoin('collection_tag', 'tags.id', '=', 'collection_tag.tag_id')->where('collection_id', '=', null)->select('tags.*', DB::raw('0 as total'))->groupBy('name');
			
			$tag_output = $tags_used->union($tags_not_used)->orderBy('total', $tag_list_order)->orderBy('name', 'desc')->get();*/
			
			$tags = $tags_used;
		}		
				
		return View('tags.index', array('tags' => $tags->appends(Input::except('page')), 'list_type' => $tag_list_type, 'list_order' => $tag_list_order, 'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
		//Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize(Tag::class);
		
        $flashed_success = $request->session()->get('flashed_success');
		$flashed_data = $request->session()->get('flashed_data');
		$flashed_warning = $request->session()->get('flashed_warning');
		
		return View('tags.create', array('flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
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
			'name' => 'required|unique:tags|regex:/^[^,]+$/',
			'url' => 'URL',
		]);
		
		$tag = new Tag();
		$tag->name = trim(Input::get('name'));
		$tag->description = trim(Input::get('description'));
		$tag->url = trim(Input::get('url'));
		
		//Delete any tag aliases that share the name with the artist to be created.
		$aliases_list = TagAlias::where('alias', '=', trim(Input::get('name')))->get();
		
		foreach ($aliases_list as $alias)
		{
			$alias->delete();
		}
		
		$tag->save();
		
		//Redirect to the tag that was created
		return redirect()->route('show_tag', ['tag' => $tag])->with("flashed_success", array("Successfully created tag $tag->name."));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Request $request, Tag $tag)
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
		
		$global_aliases = $tag->aliases()->where('user_id', '=', null)->orderBy('alias', $global_list_order)->paginate(Config::get('constants.pagination.tagAliasesPerPageParent'), ['*'], 'global_alias_page');
		$global_aliases->appends(Input::except('global_alias_page'));
		
		$personal_aliases = null;
		
		if (Auth::check())
		{
			$personal_aliases = $tag->aliases()->where('user_id', '=', Auth::user()->id)->orderBy('alias', $personal_list_order)->paginate(Config::get('constants.pagination.tagAliasesPerPageParent'), ['*'], 'personal_alias_page');
			$personal_aliases->appends(Input::except('personal_alias_page'));
		}
		
		return View('tags.show', array('tag' => $tag, 'global_list_order' => $global_list_order, 'personal_list_order' => $personal_list_order, 'global_aliases' => $global_aliases, 'personal_aliases' => $personal_aliases, 'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
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
		
		$global_aliases = $tag->aliases()->where('user_id', '=', null)->orderBy('alias', $global_list_order)->paginate(Config::get('constants.pagination.tagAliasesPerPageParent'), ['*'], 'global_alias_page');
		$global_aliases->appends(Input::except('global_alias_page'));
		
		$personal_aliases = null;
		
		if (Auth::check())
		{
			$personal_aliases = $tag->aliases()->where('user_id', '=', Auth::user()->id)->orderBy('alias', $personal_list_order)->paginate(Config::get('constants.pagination.tagAliasesPerPageParent'), ['*'], 'personal_alias_page');
			$personal_aliases->appends(Input::except('personal_alias_page'));
		}
		
		return View('tags.edit', array('tag' => $tag, 'global_list_order' => $global_list_order, 'personal_list_order' => $personal_list_order, 'global_aliases' => $global_aliases, 'personal_aliases' => $personal_aliases, 'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
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
					'regex:/^[^,]+$/'],
				'url' => 'URL',
		]);
		
		$tag->name = trim(Input::get('name'));
		$tag->description = trim(Input::get('description'));
		$tag->url = trim(Input::get('url'));
		
		//Delete any tag aliases that share the name with the artist to be created.
		$aliases_list = TagAlias::where('alias', '=', trim(Input::get('name')))->get();
		
		foreach ($aliases_list as $alias)
		{
			$alias->delete();
		}
		
		$tag->save();
		
		//Redirect to the tag that was created
		return redirect()->route('show_tag', ['tag' => $tag])->with("flashed_success", array("Successfully updated tag $tag->name."));
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
    }
}

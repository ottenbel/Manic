<?php

namespace App\Http\Controllers\TagObjects\Tag;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Auth;
use DB;
use Input;
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
		
		if (($tag_list_type != "usage") && ($tag_list_type != "alphabetic"))
		{
			$tag_list_type = "usage";
		}
		
		if (($tag_list_order != "asc") && ($tag_list_order != "desc"))
		{
			if($tag_list_type == "usage")
			{
				$tag_list_order = "asc";
			}
			else
			{
				$tag_list_order = "desc";
			}
		}
		
		if ($tag_list_type == "alphabetic")
		{
			$tags = new Tag();
			$tag_output = $tags->orderBy('name', $tag_list_order)->paginate(30);
			
			$tags = $tag_output;
		}
		else
		{	
			$tags = new Tag();
			$tags_used = $tags->join('collection_tag', 'tags.id', '=', 'collection_tag.tag_id')->select('tags.*', DB::raw('count(*) as total'))->groupBy('name')->orderBy('total', $tag_list_order)->orderBy('name', 'desc')->paginate(30);
			
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
        $this->validate($request, [
			'name' => 'required|unique:tags',
			'url' => 'URL',
		]);
		
		$tag = new Tag();
		$tag->name = trim(Input::get('name'));
		$tag->description = trim(Input::get('description'));
		$tag->url = trim(Input::get('url'));
		$tag->created_by = Auth::user()->id;
		$tag->updated_by = Auth::user()->id;
		
		$tag->save();
		
		//Redirect to the tag that was created
		return redirect()->action('TagObjects\Tag\TagController@show', [$tag])->with("flashed_success", array("Successfully created tag $tag->name."));
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
		
		$global_aliases = $tag->aliases()->where('user_id', '=', null)->orderBy('alias', 'asc')->paginate(10, ['*'], 'global_alias_page');
		$personal_aliases = null;
		
		if (Auth::check())
		{
			$personal_aliases = $tag->aliases()->where('user_id', '=', Auth::user()->id)->orderBy('alias', 'asc')->paginate(10, ['*'], 'personal_alias_page');
		}
		
		return View('tags.show', array('tag' => $tag, 'global_aliases' => $global_aliases->appends(Input::except('global_alias_page')), 'personal_aliases' => $personal_aliases->appends(Input::except('personal_alias_page')), 'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $request, Tag $tag)
    {
        $flashed_success = $request->session()->get('flashed_success');
		$flashed_data = $request->session()->get('flashed_data');
		$flashed_warning = $request->session()->get('flashed_warning');
		
		$global_aliases = $tag->aliases()->where('user_id', '=', null)->orderBy('alias', 'asc')->paginate(10, ['*'], 'global_alias_page');
		$global_aliases->appends(Input::except('global_alias_page'));
		
		$personal_aliases = null;
		
		if (Auth::check())
		{
			$personal_aliases = $tag->aliases()->where('user_id', '=', Auth::user()->id)->orderBy('alias', 'asc')->paginate(10, ['*'], 'personal_alias_page');
			$personal_aliases->appends(Input::except('personal_alias_page'));
		}
		
		return View('tags.edit', array('tagObject' => $tag, 'global_aliases' => $global_aliases, 'personal_aliases' => $personal_aliases, 'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, Tag $tag)
    {
		$this->validate($request, [
		'name' => ['required',
					Rule::unique('tags')->where(function ($query){
						$query->where('id', '!=', trim(Input::get('tag_id')));
					})],
				'url' => 'URL',
		]);
		
		$tag->name = trim(Input::get('name'));
		$tag->description = trim(Input::get('description'));
		$tag->url = trim(Input::get('url'));
		$tag->updated_by = Auth::user()->id;
		
		$tag->save();
		
		//Redirect to the tag that was created
		return redirect()->action('TagObjects\Tag\TagController@show', [$tag])->with("flashed_success", array("Successfully updated tag $tag->name."));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}

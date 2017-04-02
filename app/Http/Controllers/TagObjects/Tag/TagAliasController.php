<?php

namespace App\Http\Controllers\TagObjects\Tag;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Auth;
use DB;
use Input;
use App\Models\TagObjects\Tag\TagAlias;
use App\Models\TagObjects\Tag\Tag;

class TagAliasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
		//
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request, Tag $tag)
    {
        $isGlobalAlias = Input::get('is_global_alias');
		if ($isGlobalAlias)
		{
			$this->validate($request, [
				'global_alias' => 'required|unique:tags,name|unique:tag_alias,alias',
			]);
		}
		else
		{
			$this->validate($request, [
				'personal_alias' => 'required|unique:tags,name|unique:tag_alias,alias',
			]);
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
		
		$tagAlias->created_by = Auth::user()->id;
		$tagAlias->updated_by = Auth::user()->id;
		
		$tagAlias->save();
		
		//Redirect to the tag that the alias was created for
		return redirect()->action('TagObjects\Tag\TagController@show', [$tag])->with("flashed_success", array("Successfully created alias $tagAlias->alias on tag $tag->name."));
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

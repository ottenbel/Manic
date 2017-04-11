<?php

namespace App\Http\Controllers\TagObjects\Artist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Auth;
use DB;
use Input;
use App\Models\TagObjects\Artist\ArtistAlias;
use App\Models\TagObjects\Artist\Artist;

class ArtistAliasController extends Controller
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
		
		$alias_list_type = trim(strtolower($request->input('type')));
		$alias_list_order = trim(strtolower($request->input('order')));
		
		if (($alias_list_type != "global") && ($alias_list_type != "personal") && ($alias_list_type != "mixed"))
		{
			$alias_list_type = "mixed";
		}
		
		if (($alias_list_order != "asc") && ($alias_list_order != "desc"))
		{
			$alias_list_order = "asc";
		}
		
		$aliases = new ArtistAlias();
		
		if (Auth::user())
		{
			if ($alias_list_type == "global")
			{
				$aliases = $aliases->where('user_id', '=', null)->orderBy('alias', $alias_list_order)->paginate(30);
			}
			
			if ($alias_list_type == "personal")
			{
				$aliases = $aliases->where('user_id', '=', Auth::user()->id)->orderBy('alias', $alias_list_order)->paginate(30);
			}
			
			if ($alias_list_type == "mixed")
			{
				$aliases = $aliases->where('user_id', '=', null)->orWhere('user_id', '=', Auth::user()->id)->orderBy('alias', $alias_list_order)->paginate(30);
			}
		}
		else
		{
			$aliases = new ArtistAlias();
			$aliases = $aliases->where('user_id', '=', null)->orderBy('alias', $alias_list_order)->paginate(30);
		}
		
		return View('artists.alias.index', array('aliases' => $aliases->appends(Input::except('page')), 'list_type' => $alias_list_type, 'list_order' => $alias_list_order, 'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request, Artist $artist)
    {
		$isGlobalAlias = Input::get('is_global_alias');
		if ($isGlobalAlias)
		{
			$this->validate($request, [
				'global_alias' => 'required|unique:artists,name|unique:artist_alias,alias,null,null,user_id,NULL']);
		}
		else
		{
			$this->validate($request, [
				'personal_alias' => 'required|unique:artists,name|unique:artist_alias,alias,null,null,user_id,'.Auth::user()->id]);
		}
		
		$artistAlias = new ArtistAlias();
		$artistAlias->artist_id = $artist->id;
		 
        if ($isGlobalAlias)
		{
			$artistAlias->alias = Input::get('global_alias');
			$artistAlias->user_id = null;
		}
		else
		{
			$artistAlias->alias = Input::get('personal_alias');
			$artistAlias->user_id = Auth::user()->id;
		}
		
		$artistAlias->save();
		
		//Redirect to the artist that the alias was created for
		return redirect()->action('TagObjects\Artist\ArtistController@show', [$artist])->with("flashed_success", array("Successfully created alias $artistAlias->alias on artist $artist->name."));
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

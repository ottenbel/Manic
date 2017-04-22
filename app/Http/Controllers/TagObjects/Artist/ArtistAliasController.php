<?php

namespace App\Http\Controllers\TagObjects\Artist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Auth;
use DB;
use Input;
use Config;
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
		
		$aliases = new ArtistAlias();
		
		if (Auth::user())
		{
			if ($alias_list_type == Config::get('constants.sortingStringComparison.aliasListType.global'))
			{
				$aliases = $aliases->where('user_id', '=', null)->orderBy('alias', $alias_list_order)->paginate(Config::get('constants.pagination.tagAliasesPerPageIndex'));
			}
			
			if ($alias_list_type == Config::get('constants.sortingStringComparison.aliasListType.personal'))
			{
				$aliases = $aliases->where('user_id', '=', Auth::user()->id)->orderBy('alias', $alias_list_order)->paginate(Config::get('constants.pagination.tagAliasesPerPageIndex'));
			}
			
			if ($alias_list_type == Config::get('constants.sortingStringComparison.aliasListType.mixed'))
			{
				$aliases = $aliases->where('user_id', '=', null)->orWhere('user_id', '=', Auth::user()->id)->orderBy('alias', $alias_list_order)->paginate(Config::get('constants.pagination.tagAliasesPerPageIndex'));
			}
		}
		else
		{
			$aliases = new ArtistAlias();
			$aliases = $aliases->where('user_id', '=', null)->orderBy('alias', $alias_list_order)->paginate(Config::get('constants.pagination.tagAliasesPerPageIndex'));
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
		$isPersonalAlias = Input::get('is_personal_alias');
		if ($isGlobalAlias)
		{
			//Define authorization in the controller as global variables can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
			$this->authorize([ArtistAlias::class, true]);
			
			$this->validate($request, [
				'global_alias' => 'required|unique:artists,name|unique:artist_alias,alias,null,null,user_id,NULL|regex:/^[^,]+$/']);
		}
		else if ($isPersonalAlias)
		{
			//Define authorization in the controller as global variables can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
			$this->authorize([ArtistAlias::class, false]);
			
			$this->validate($request, [
				'personal_alias' => 'required|unique:artists,name|unique:artist_alias,alias,null,null,user_id,'.Auth::user()->id.'|regex:/^[^,]+$/']);
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
     * @param  ArtistAlias  $artistAlias
     * @return Response
     */
    public function destroy(ArtistAlias $artistAlias)
    {
        //Define authorization in the controller as global variables can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
			$this->authorize($artistAlias);
    }
}

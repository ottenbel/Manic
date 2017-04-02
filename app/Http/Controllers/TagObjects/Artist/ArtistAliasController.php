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
		//
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
				'global_alias' => 'required|unique:artists,name|unique:artist_alias,alias',
			]);
		}
		else
		{
			$this->validate($request, [
				'personal_alias' => 'required|unique:artists,name|unique:artist_alias,alias',
			]);
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
		
		$artistAlias->created_by = Auth::user()->id;
		$artistAlias->updated_by = Auth::user()->id;
		
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

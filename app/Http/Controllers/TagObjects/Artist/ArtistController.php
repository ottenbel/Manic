<?php

namespace App\Http\Controllers\TagObjects\Artist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Auth;
use DB;
use Input;
use App\Models\TagObjects\Artist\Artist;
use App\Models\TagObjects\Artist\ArtistAlias;

class ArtistController extends Controller
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
	
		$artists = null;
		$artist_list_type = trim(strtolower($request->input('type')));
		$artist_list_order = trim(strtolower($request->input('order')));
		
		if (($artist_list_type != "usage") && ($artist_list_type != "alphabetic"))
		{
			$artist_list_type = "usage";
		}
		
		if (($artist_list_order != "asc") && ($artist_list_order != "desc"))
		{
			if($artist_list_type == "usage")
			{
				$artist_list_order = "asc";
			}
			else
			{
				$artist_list_order = "desc";
			}
		}
		
		if ($artist_list_type == "alphabetic")
		{
			$artists = new artist();
			$artist_output = $artists->orderBy('name', $artist_list_order)->paginate(30);
			
			$artists = $artist_output;
		}
		else
		{	
			$artists = new artist();
			$artists_used = $artists->join('artist_collection', 'artists.id', '=', 'artist_collection.artist_id')->select('artists.*', DB::raw('count(*) as total'))->groupBy('name')->orderBy('total', $artist_list_order)->orderBy('name', 'desc')->paginate(30);
			
			//Leaving this code commented outhere until the paginator handling for union gets fixed in Laravel (this adds artists that aren't used into the dataset used for popularity)
			
			/*$artists_not_used = $artists->leftjoin('artist_collection', 'artists.id', '=', 'artist_collection.artist_id')->where('collection_id', '=', null)->select('artists.*', DB::raw('0 as total'))->groupBy('name');
			
			$artist_output = $artists_used->union($artists_not_used)->orderBy('total', $artist_list_order)->orderBy('name', 'desc')->get();*/
			
			$artists = $artists_used;
		}		
		
		return View('artists.index', array('artists' => $artists->appends(Input::except('page')), 'list_type' => $artist_list_type, 'list_order' => $artist_list_order, 'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
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
		
		return View('artists.create', array('flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
			'name' => 'required|unique:artists',
			'url' => 'URL',
		]);
		
		$artist = new Artist();
		$artist->name = trim(Input::get('name'));
		$artist->description = trim(Input::get('description'));
		$artist->url = trim(Input::get('url'));
		$artist->created_by = Auth::user()->id;
		$artist->updated_by = Auth::user()->id;
		
		$artist->save();
		
		//Redirect to the artist that was created
		return redirect()->action('TagObjects\Artist\ArtistController@show', [$artist])->with("flashed_success", array("Successfully created artist $artist->name."));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Request $request, Artist $artist)
    {
        $flashed_success = $request->session()->get('flashed_success');
		$flashed_data = $request->session()->get('flashed_data');
		$flashed_warning = $request->session()->get('flashed_warning');
		
		$global_aliases = $artist->aliases()->where('user_id', '=', null)->orderBy('alias', 'asc')->paginate(10, ['*'], 'global_alias_page');
		$global_aliases->appends(Input::except('global_alias_page'));
		
		$personal_aliases = null;
		
		if (Auth::check())
		{
			$personal_aliases = $artist->aliases()->where('user_id', '=', Auth::user()->id)->orderBy('alias', 'asc')->paginate(10, ['*'], 'personal_alias_page');
			$personal_aliases->appends(Input::except('personal_alias_page'));
		}
		
		return View('artists.show', array('artist' => $artist, 'global_aliases' => $global_aliases, 'personal_aliases' => $personal_aliases, 'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $request, Artist $artist)
    {
        $flashed_success = $request->session()->get('flashed_success');
		$flashed_data = $request->session()->get('flashed_data');
		$flashed_warning = $request->session()->get('flashed_warning');
		
		//Add auth check here
		$global_aliases = $artist->aliases()->where('user_id', '=', null)->orderBy('alias', 'asc')->paginate(10, ['*'], 'global_alias_page');
		$global_aliases->appends(Input::except('global_alias_page'));
		
		$personal_aliases = null;
		
		if (Auth::check())
		{
			$personal_aliases = $artist->aliases()->where('user_id', '=', Auth::user()->id)->orderBy('alias', 'asc')->paginate(10, ['*'], 'personal_alias_page');
			$personal_aliases->appends(Input::except('personal_alias_page'));
		}
		
		return View('artists.edit', array('tagObject' => $artist, 'global_aliases' => $global_aliases, 'personal_aliases' => $personal_aliases, 'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, Artist $artist)
    {
		$this->validate($request, [
		'name' => ['required',
					Rule::unique('artists')->where(function ($query){
						$query->where('id', '!=', trim(Input::get('artist_id')));
					})],
				'url' => 'URL',
		]);
		
		$artist->name = trim(Input::get('name'));
		$artist->description = trim(Input::get('description'));
		$artist->url = trim(Input::get('url'));
		$artist->updated_by = Auth::user()->id;
		
		$artist->save();
		
		//Redirect to the artist that was created
		return redirect()->action('TagObjects\Artist\ArtistController@show', [$artist])->with("flashed_success", array("Successfully updated artist $artist->name."));
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

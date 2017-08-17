<?php

namespace App\Http\Controllers\TagObjects\Artist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Auth;
use DB;
use Input;
use Config;
use MappingHelper;
use ConfigurationLookupHelper;
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
		
		if (($artist_list_type != Config::get('constants.sortingStringComparison.tagListType.usage')) 
			&& ($artist_list_type != Config::get('constants.sortingStringComparison.tagListType.alphabetic')))
		{
			$artist_list_type = Config::get('constants.sortingStringComparison.tagListType.usage');
		}
		
		if (($artist_list_order != Config::get('constants.sortingStringComparison.listOrder.ascending')) 
			&& ($artist_list_order != Config::get('constants.sortingStringComparison.listOrder.descending')))
		{
			if($artist_list_type == Config::get('constants.sortingStringComparison.tagListType.usage'))
			{
				$artist_list_order = Config::get('constants.sortingStringComparison.listOrder.ascending');
			}
			else
			{
				$artist_list_order = Config::get('constants.sortingStringComparison.listOrder.descending');
			}
		}
		
		$lookupKey = Config::get('constants.keys.pagination.artistsPerPageIndex');
		$paginationCount = ConfigurationLookupHelper::LookupPaginationConfiguration($lookupKey)->value;
		
		if ($artist_list_type == Config::get('constants.sortingStringComparison.tagListType.alphabetic'))
		{
			$artists = new artist();
			$artist_output = $artists->orderBy('name', $artist_list_order)->paginate($paginationCount);
			
			$artists = $artist_output;
		}
		else
		{	
			$artists = new artist();
			$artists_used = $artists->join('artist_collection', 'artists.id', '=', 'artist_collection.artist_id')->select('artists.*', DB::raw('count(*) as total'))->groupBy('name')->orderBy('total', $artist_list_order)->orderBy('name', 'desc')->paginate($paginationCount);
			
			//Leaving this code commented outhere until the paginator handling for union gets fixed in Laravel (this adds artists that aren't used into the dataset used for popularity)
			
			/*$artists_not_used = $artists->leftjoin('artist_collection', 'artists.id', '=', 'artist_collection.artist_id')->where('collection_id', '=', null)->select('artists.*', DB::raw('0 as total'))->groupBy('name');
			
			$artist_output = $artists_used->union($artists_not_used)->orderBy('total', $artist_list_order)->orderBy('name', 'desc')->get();*/
			
			$artists = $artists_used;
		}		
		
		return View('tagObjects.artists.index', array('artists' => $artists->appends(Input::except('page')), 'list_type' => $artist_list_type, 'list_order' => $artist_list_order, 'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
		//Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize(Artist::class);
		
        $flashed_success = $request->session()->get('flashed_success');
		$flashed_data = $request->session()->get('flashed_data');
		$flashed_warning = $request->session()->get('flashed_warning');
		
		return View('tagObjects.artists.create', array('flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
		//Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize(Artist::class);
		
        $this->validate($request, [
			'name' => 'required|unique:artists|regex:/^[^,:-]+$/',
			'url' => 'URL',
		]);
		
		$artist = new Artist();
		$artist->name = trim(Input::get('name'));
		$artist->short_description = trim(Input::get('short_description'));
		$artist->description = trim(Input::get('description'));
		$artist->url = trim(Input::get('url'));
		
		//Delete any artist aliases that share the name with the artist to be created.
		$aliases_list = ArtistAlias::where('alias', '=', trim(Input::get('name')))->get();
		
		foreach ($aliases_list as $alias)
		{
			$alias->delete();
		}
		
		$artist->save();
		
		$artist->children()->detach();
		$artistChildrenArray = array_unique(array_map('trim', explode(',', Input::get('artist_child'))));
		$causedLoops = MappingHelper::MapArtistChildren($artist, $artistChildrenArray);
		
		if (count($causedLoops))
		{	
			$childCausingLoopsMessage = "The following artists (" . implode(", ", $causedLoops) . ") were not attached as children to " . $artist->name . " as their addition would cause loops in tag implication.";
			
			return redirect()->route('show_artist', ['artist' => $artist])->with("flashed_data", array("Partially created artist $artist->name."))->with("flashed_warning", array($childCausingLoopsMessage));
		}
		else
		{
			//Redirect to the artist that was created
			return redirect()->route('show_artist', ['artist' => $artist])->with("flashed_success", array("Successfully created artist $artist->name."));
		}
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
		
		$global_aliases = $artist->aliases()->where('user_id', '=', null)->orderBy('alias', $global_list_order)->paginate(Config::get('constants.pagination.tagAliasesPerPageParent'), ['*'], 'global_alias_page');
		$global_aliases->appends(Input::except('global_alias_page'));
		
		$personal_aliases = null;
		
		if (Auth::check())
		{
			$personal_aliases = $artist->aliases()->where('user_id', '=', Auth::user()->id)->orderBy('alias', $personal_list_order)->paginate(Config::get('constants.pagination.tagAliasesPerPageParent'), ['*'], 'personal_alias_page');
			$personal_aliases->appends(Input::except('personal_alias_page'));
		}
		
		return View('tagObjects.artists.show', array('artist' => $artist, 'global_list_order' => $global_list_order, 'personal_list_order' => $personal_list_order, 'global_aliases' => $global_aliases, 'personal_aliases' => $personal_aliases, 'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $request, Artist $artist)
    {
		//Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize($artist);
		
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
		
		//Add auth check here
		$global_aliases = $artist->aliases()->where('user_id', '=', null)->orderBy('alias', $global_list_order)->paginate(Config::get('constants.pagination.tagAliasesPerPageParent'), ['*'], 'global_alias_page');
		$global_aliases->appends(Input::except('global_alias_page'));
		
		$personal_aliases = null;
		
		if (Auth::check())
		{
			$personal_aliases = $artist->aliases()->where('user_id', '=', Auth::user()->id)->orderBy('alias', $personal_list_order)->paginate(Config::get('constants.pagination.tagAliasesPerPageParent'), ['*'], 'personal_alias_page');
			$personal_aliases->appends(Input::except('personal_alias_page'));
		}
		
		return View('tagObjects.artists.edit', array('artist' => $artist, 'global_list_order' => $global_list_order, 'personal_list_order' => $personal_list_order, 'global_aliases' => $global_aliases, 'personal_aliases' => $personal_aliases, 'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, Artist $artist)
    {
		//Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize($artist);
		
		$this->validate($request, [
		'name' => ['required',
					Rule::unique('artists')->ignore($artist->id),
					'regex:/^[^,:-]+$/'],
				'url' => 'URL',
		]);
		
		$artist->name = trim(Input::get('name'));
		$artist->short_description = trim(Input::get('short_description'));
		$artist->description = trim(Input::get('description'));
		$artist->url = trim(Input::get('url'));
		
		//Delete any artist aliases that share the name with the artist to be created.
		$aliases_list = ArtistAlias::where('alias', '=', trim(Input::get('name')))->get();
		
		foreach ($aliases_list as $alias)
		{
			$alias->delete();
		}
		
		$artist->save();
		
		$artist->children()->detach();
		$artistChildrenArray = array_unique(array_map('trim', explode(',', Input::get('artist_child'))));
		$causedLoops = MappingHelper::MapArtistChildren($artist, $artistChildrenArray);
		
		if (count($causedLoops))
		{	
			$childCausingLoopsMessage = "The following artists (" . implode(", ", $causedLoops) . ") were not attached as children to " . $artist->name . " as their addition would cause loops in tag implication.";
			
			return redirect()->route('show_artist', ['artist' => $artist])->with("flashed_data", array("Partially updated artist $artist->name."))->with("flashed_warning", array($childCausingLoopsMessage));
		}
		else
		{
			//Redirect to the artist that was created
			return redirect()->route('show_artist', ['artist' => $artist])->with("flashed_success", array("Successfully updated artist $artist->name."));
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Artist  $artist
     * @return Response
     */
    public function destroy(Artist $artist)
    {
        //Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize($artist);
		
		$artistName = $artist->name;
		
		$parents = $artist->parents()->get();
		$children = $artist->children()->get();
		
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
		$artist->forceDelete();
		
		return redirect()->route('index_collection')->with("flashed_success", array("Successfully purged artist $artistName from the database."));
    }
}

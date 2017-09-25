<?php

namespace App\Http\Controllers\TagObjects\Artist;

use App\Http\Controllers\WebController;
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
use App\Models\Configuration\ConfigurationPlaceholder;

class ArtistController extends WebController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {		
		$messages = self::GetFlashedMessages($request);
	
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
		
		return View('tagObjects.artists.index', array('artists' => $artists->appends(Input::except('page')), 'list_type' => $artist_list_type, 'list_order' => $artist_list_order, 'messages' => $messages));
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
		
        $messages = self::GetFlashedMessages($request);
		$configurations = self::GetConfiguration();
		
		return View('tagObjects.artists.create', array('configurations' => $configurations, 'messages' => $messages));
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
			
			$messages = self::BuildFlashedMessagesVariable(null, ["Partially created artist $artist->name."], [$childCausingLoopsMessage]);
			return redirect()->route('show_artist', ['artist' => $artist])->with("messages", $messages);
		}
		else
		{
			$messages = self::BuildFlashedMessagesVariable(["Successfully created artist $artist->name."], null, null);
			//Redirect to the artist that was created
			return redirect()->route('show_artist', ['artist' => $artist])->with("messages", $messages);
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
		
		$lookupKey = Config::get('constants.keys.pagination.artistAliasesPerPageParent');
		$paginationCount = ConfigurationLookupHelper::LookupPaginationConfiguration($lookupKey)->value;
		
		$global_aliases = $artist->aliases()->where('user_id', '=', null)->orderBy('alias', $global_list_order)->paginate($paginationCount, ['*'], 'global_alias_page');
		$global_aliases->appends(Input::except('global_alias_page'));
		
		$personal_aliases = null;
		
		if (Auth::check())
		{
			$personal_aliases = $artist->aliases()->where('user_id', '=', Auth::user()->id)->orderBy('alias', $personal_list_order)->paginate($paginationCount, ['*'], 'personal_alias_page');
			$personal_aliases->appends(Input::except('personal_alias_page'));
		}
		
		return View('tagObjects.artists.show', array('artist' => $artist, 'global_list_order' => $global_list_order, 'personal_list_order' => $personal_list_order, 'global_aliases' => $global_aliases, 'personal_aliases' => $personal_aliases, 'messages' => $messages));
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
		
        $messages = self::GetFlashedMessages($request);
		$configurations = self::GetConfiguration();
		
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
		
		$lookupKey = Config::get('constants.keys.pagination.artistAliasesPerPageParent');
		$paginationCount = ConfigurationLookupHelper::LookupPaginationConfiguration($lookupKey)->value;
		
		//Add auth check here
		$global_aliases = $artist->aliases()->where('user_id', '=', null)->orderBy('alias', $global_list_order)->paginate($paginationCount, ['*'], 'global_alias_page');
		$global_aliases->appends(Input::except('global_alias_page'));
		
		$personal_aliases = null;
		
		if (Auth::check())
		{
			$personal_aliases = $artist->aliases()->where('user_id', '=', Auth::user()->id)->orderBy('alias', $personal_list_order)->paginate($paginationCount, ['*'], 'personal_alias_page');
			$personal_aliases->appends(Input::except('personal_alias_page'));
		}
		
		return View('tagObjects.artists.edit', array('configurations' => $configurations, 'artist' => $artist, 'global_list_order' => $global_list_order, 'personal_list_order' => $personal_list_order, 'global_aliases' => $global_aliases, 'personal_aliases' => $personal_aliases, 'messages' => $messages));
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
			
			$messages = self::BuildFlashedMessagesVariable(null, ["Partially updated artist $artist->name."], [$childCausingLoopsMessage]);
			return redirect()->route('show_artist', ['artist' => $artist])->with("messages", $messages);
		}
		else
		{
			$messages = self::BuildFlashedMessagesVariable(["Successfully updated artist $artist->name."], null, null);
			//Redirect to the artist that was created
			return redirect()->route('show_artist', ['artist' => $artist])->with("messages", $messages);
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
		
		$messages = self::BuildFlashedMessagesVariable(["Successfully purged artist $artistName from the database."], null, null);
		return redirect()->route('index_collection')->with("messages", $messages);
    }
	
	private static function GetConfiguration()
	{
		$configurations = Auth::user()->placeholder_configuration()->where('key', 'like', 'artist%')->get();
		
		$name = $configurations->where('key', '=', Config::get('constants.keys.placeholders.artist.name'))->first();
		$shortDescription = $configurations->where('key', '=', Config::get('constants.keys.placeholders.artist.shortDescription'))->first();
		$description = $configurations->where('key', '=', Config::get('constants.keys.placeholders.artist.description'))->first();
		$source = $configurations->where('key', '=', Config::get('constants.keys.placeholders.artist.source'))->first();
		$child = $configurations->where('key', '=', Config::get('constants.keys.placeholders.artist.child'))->first();
		
		$configurationsArray = array('name' => $name, 'shortDescription' => $shortDescription, 'description' => $description, 'source' => $source, 'child' => $child);
		
		return $configurationsArray;
	}
}

<?php

namespace App\Http\Controllers\TagObjects\Artist;

use App\Http\Controllers\TagObjects\TagObjectController;
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
use App\Http\Requests\TagObjects\Artist\StoreArtistRequest;
use App\Http\Requests\TagObjects\Artist\UpdateArtistRequest;

class ArtistController extends TagObjectController
{
    public function index(Request $request)
    {		
		$artists = new artist();
		return self::GetTagObjectIndex($request, $artists, 'artistsPerPageIndex', 'artists', 'artist_collection', 'artist_id');
    }

    public function create(Request $request)
    {
		$this->authorize(Artist::class);	
        $messages = self::GetFlashedMessages($request);
		$configurations = self::GetConfiguration('artist');
		return View('tagObjects.artists.create', array('configurations' => $configurations, 'messages' => $messages));
    }

    public function store(StoreArtistRequest $request)
    {
		$artist = new Artist();
		return self::CreateOrUpdateArtist($request, $artist, 'created');
    }

    public function show(Request $request, Artist $artist)
    {
        return self::GetArtistToDisplay($request, $artist, 'show');
    }

    public function edit(Request $request, Artist $artist)
    {
		$this->authorize($artist);
		$configurations = self::GetConfiguration('artist');
		return self::GetArtistToDisplay($request, $artist, 'edit', $configurations);
    }
	
    public function update(UpdateArtistRequest $request, Artist $artist)
    {		
		return self::CreateOrUpdateArtist($request, $artist, 'updated');
    }

    public function destroy(Artist $artist)
    {
		$this->authorize($artist);
		return self::DestroyTagObject($artist, 'artist');
    }
	
	protected static function CreateOrUpdateArtist($request, $artist, $action)
	{
		ArtistAlias::where('alias', '=', trim(Input::get('name')))->delete();
		$artist->fill($request->only(['name', 'short_description', 'description', 'url']));
		$artist->save();
		
		$artist->children()->detach();
		$artistChildrenArray = array_unique(array_map('trim', explode(',', Input::get('artist_child'))));
		$causedLoops = MappingHelper::MapArtistChildren($artist, $artistChildrenArray);
		
		if (count($causedLoops))
		{	
			$childCausingLoopsMessage = "The following artists (" . implode(", ", $causedLoops) . ") were not attached as children to " . $artist->name . " as their addition would cause loops in tag implication.";
			
			$messages = self::BuildFlashedMessagesVariable(null, ["Partially $action artist $artist->name."], [$childCausingLoopsMessage]);
			return redirect()->route('show_artist', ['artist' => $artist])->with("messages", $messages);
		}
		else
		{
			$messages = self::BuildFlashedMessagesVariable(["Successfully $action artist $artist->name."], null, null);
			return redirect()->route('show_artist', ['artist' => $artist])->with("messages", $messages);
		}
	}
	
	protected static function GetArtistToDisplay($request, $artist, $route, $configurations = null)
	{
		$messages = self::GetFlashedMessages($request);
		$aliasOrdering = self::GetAliasShowOrdering($request);
		
		$lookupKey = Config::get('constants.keys.pagination.artistAliasesPerPageParent');
		$paginationCount = ConfigurationLookupHelper::LookupPaginationConfiguration($lookupKey)->value;
		
		$globalAliases = $artist->aliases()->where('user_id', '=', null)->orderBy('alias', $aliasOrdering['global'])->paginate($paginationCount, ['*'], 'global_alias_page');
		$globalAliases->appends(Input::except('global_alias_page'));
		
		$personalAliases = null;
		
		if (Auth::check())
		{
			$personalAliases = $artist->aliases()->where('user_id', '=', Auth::user()->id)->orderBy('alias', $aliasOrdering['personal'])->paginate($paginationCount, ['*'], 'personal_alias_page');
			$personalAliases->appends(Input::except('personal_alias_page'));
		}
		
		return View('tagObjects.artists.'.$route, array('configurations' => $configurations, 'artist' => $artist, 'global_list_order' => $aliasOrdering['global'], 'personal_list_order' => $aliasOrdering['personal'], 'global_aliases' => $globalAliases, 'personal_aliases' => $personalAliases, 'messages' => $messages));
	}
}

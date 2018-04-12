<?php

namespace App\Http\Controllers\TagObjects\Artist;

use App\Http\Controllers\TagObjects\TagObjectController;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
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
	public function __construct()
    {
		parent::__construct();
		
		$this->paginationKey = "pagination_artists_per_page_index";
		$this->aliasesPaginationKey = "pagination_artist_aliases_per_page_parent";
		$this->placeholderStub = "artist";
		$this->placeheldFields = array('name', 'short_description', 'description', 'source', 'child');
		
		$this->middleware('auth')->except(['index', 'show']);
		$this->middleware('permission:Create Artist')->only(['create', 'store']);
		$this->middleware('permission:Edit Artist')->only(['edit', 'update']);
		$this->middleware('permission:Delete Artist')->only('destroy');
	}
	
    public function index(Request $request)
    {		
		$artists = new artist();
		return $this->GetTagObjectIndex($request, $artists, 'artistsPerPageIndex', 'artists', 'artist_collection', 'artist_id');
    }

    public function create(Request $request)
    {
		$this->authorize(Artist::class);	
        $this->GetFlashedMessages($request);
		$configurations = $this->GetConfiguration();
		return View('tagObjects.artists.create', array('configurations' => $configurations, 'messages' => $this->messages));
    }

    public function store(StoreArtistRequest $request)
    {
		$artist = new Artist();
		return $this->InsertOrUpdate($request, $artist, 'created', 'create');
    }

    public function show(Request $request, Artist $artist)
    {
        return $this->GetArtistToDisplay($request, $artist, 'show');
    }

    public function edit(Request $request, Artist $artist)
    {
		$this->authorize($artist);
		$configurations = $this->GetConfiguration();
		return $this->GetArtistToDisplay($request, $artist, 'edit', $configurations);
    }
	
    public function update(UpdateArtistRequest $request, Artist $artist)
    {		
		return $this->InsertOrUpdate($request, $artist, 'updated', 'update');
    }

    public function destroy(Artist $artist)
    {
		$this->authorize($artist);
		return $this->DestroyTagObject($artist, 'artist');
    }
	
	private function InsertOrUpdate($request, $artist, $action, $errorAction)
	{
		DB::beginTransaction();
		try
		{
			ArtistAlias::where('alias', '=', trim(Input::get('name')))->delete();
			$artist->fill($request->only(['name', 'short_description', 'description', 'url']));
			$artist->save();
			
			$artist->children()->detach();
			$artistChildrenArray = array_unique(array_map('trim', explode(',', Input::get('artist_child'))));
			$causedLoops = MappingHelper::MapArtistChildren($artist, $artistChildrenArray);
		}
		catch (\Exception $e)
		{
			DB::rollBack();
			$this->AddWarningMessage("Unable to successfully $errorAction artist $artist->name.", ['artist' => $artist->id, 'error' => $e]);
			return Redirect::back()->with(["messages" => $this->messages])->withInput();
		}
		DB::commit();
		
		if (count($causedLoops))
		{	
			$childCausingLoopsMessage = "The following artists (" . implode(", ", $causedLoops) . ") were not attached as children to " . $artist->name . " as their addition would cause loops in tag implication.";
			
			$this->AddDataMessage("Partially $action artist $artist->name.", ['artist' => $artist->id]);
			$this->AddDataMessage($childCausingLoopsMessage, ['artist' => $artist->id]);
			return redirect()->route('show_artist', ['artist' => $artist])->with("messages", $this->messages);
		}
		else
		{
			$this->AddSuccessMessage("Successfully $action artist $artist->name.", ['artist' => $artist->id]);
			return redirect()->route('show_artist', ['artist' => $artist])->with("messages", $this->messages);
		}
	}
	
	private function GetArtistToDisplay($request, $artist, $route, $configurations = null)
	{
		$this->GetFlashedMessages($request);
		$aliasOrdering = $this->GetAliasShowOrdering($request);
		
		$paginationCount = ConfigurationLookupHelper::LookupPaginationConfiguration($this->aliasesPaginationKey)->value;
		
		$globalAliases = $artist->aliases()->where('user_id', '=', null)->orderBy('alias', $aliasOrdering['global'])->paginate($paginationCount, ['*'], 'global_alias_page');
		$globalAliases->appends(Input::except('global_alias_page'));
		
		$personalAliases = null;
		
		if (Auth::check())
		{
			$personalAliases = $artist->aliases()->where('user_id', '=', Auth::user()->id)->orderBy('alias', $aliasOrdering['personal'])->paginate($paginationCount, ['*'], 'personal_alias_page');
			$personalAliases->appends(Input::except('personal_alias_page'));
		}
		
		return View('tagObjects.artists.'.$route, array('configurations' => $configurations, 'artist' => $artist, 'global_list_order' => $aliasOrdering['global'], 'personal_list_order' => $aliasOrdering['personal'], 'global_aliases' => $globalAliases, 'personal_aliases' => $personalAliases, 'messages' => $this->messages));
	}
}

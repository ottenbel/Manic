<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Auth;
use DB;
use Input;
use Config;
use Storage;
use MappingHelper;
use SearchParseHelper;
use ImageUploadHelper;
use InterventionImage;
use FileExportHelper;
use LookupHelper;
use ConfigurationLookupHelper;
use App\Models\TagObjects\Artist\Artist;
use App\Models\TagObjects\Artist\ArtistAlias;
use App\Models\TagObjects\Character\Character;
use App\Models\TagObjects\Character\CharacterAlias;
use App\Models\Collection;
use App\Models\Image;
use App\Models\Language;
use App\Models\Rating;
use App\Models\TagObjects\Series\Series;
use App\Models\TagObjects\Series\SeriesAlias;
use App\Models\Status;
use App\Models\TagObjects\Tag\Tag;
use App\Models\TagObjects\Tag\TagAlias;
use App\Http\Requests\Collection\StoreCollectionRequest;
use App\Http\Requests\Collection\UpdateCollectionRequest;
use App\Models\Configuration\ConfigurationRatingRestriction;

class CollectionController extends WebController
{
	public function __construct()
    {
		$this->middleware('auth')->except(['index', 'show']);
		$this->middleware('canInteractWithCollection')->except(['index', 'create', 'store']);
		$this->middleware('permission:Create Collection')->only(['create', 'store']);
		$this->middleware('permission:Edit Collection')->only(['edit', 'update']);
		$this->middleware('permission:Delete Collection')->only('destroy');
		$this->middleware('permission:Export Collection')->only('export');
	}
	
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
		$messages = self::GetFlashedMessages($request);
		$search_string = $request->query('search');
		
		$collections = $searchArtists = $searchCharacters = $searchScanalators = $searchSeries = $searchTags = $searchLanguages = $searchRatings = $searchStatuses = $searchCanonicity = $invalid_tokens = null; 
		
		$collections = Collection::with('language', 'primary_artists', 'secondary_artists', 'primary_series', 'secondary_series', 'primary_tags', 'secondary_tags', 'rating', 'status');
		$ratingRestrictions = null;
		if(Auth::check())
		{
			$ratingRestrictions = Auth::user()->rating_restriction_configuration->where('display', '=', false);
		}
		else
		{
			$ratingRestrictions = ConfigurationRatingRestriction::where('user_id', '=', null)->where('display', '=', false)->get();
		}
		
		foreach ($ratingRestrictions as $ratingRestriction)
		{
			$collections = $collections->where('rating_id', '!=', $ratingRestriction->rating_id);
		}
		
		//No search is conducted
		if ($search_string ==  "")
		{
			//Get all relevant collections
			$lookupKey = Config::get('constants.keys.pagination.collectionsPerPageIndex');
			$paginationCollectionsPerPageIndexCount = ConfigurationLookupHelper::LookupPaginationConfiguration($lookupKey)->value;
		
			$collections = $collections->orderBy('updated_at', 'desc')->paginate($paginationCollectionsPerPageIndexCount);
			$collections->appends(Input::except('page'));
		}
		else //Filter the collections return based on the search string
		{
			SearchParseHelper::Search($search_string, $collections, $searchArtists, $searchCharacters, $searchScanalators, $searchSeries, $searchTags, $searchLanguages, $searchRatings, $searchStatuses, $searchCanonicity, $invalid_tokens);
			$collections->appends(Input::except('page'));
		}
		
		return View('collections.index', array('collections' => $collections, 'search_artists_array' => $searchArtists, 'search_characters_array' => $searchCharacters, 'search_scanalators_array' => $searchScanalators, 'search_series_array' => $searchSeries, 'search_tags_array' => $searchTags, 'search_languages_array' => $searchLanguages, 'search_ratings_array' => $searchRatings, 'search_statues_array' => $searchStatuses, 'search_canonicity_array' => $searchCanonicity, 'invalid_tokens_array' => $invalid_tokens, 'messages' => $messages));
    }

    public function create(Request $request)
    {
		$this->authorize(Collection::class);
		$messages = self::GetFlashedMessages($request);
		$dropdowns = self::GetDropdowns();
		$configurations = self::GetConfiguration();
		
		return View('collections.create', array('configurations' => $configurations, 'ratings' => $dropdowns['ratings'], 'statuses' => $dropdowns['statuses'], 'languages' => $dropdowns['languages'], 'messages' => $messages));
    }

    public function store(StoreCollectionRequest $request)
    {
		$collection = new Collection();
		return self::InsertOrUpdate($request, $collection, 'created', 'create');
    }
	
    public function show(Request $request, Collection $collection)
    {	
		$messages = self::GetFlashedMessages($request);
		$sibling_collections = null;
		
		if($collection->parent_collection != null)
		{
			$sibling_collections = $collection->parent_collection->child_collections()->where('id', '!=', $collection->id)->get();
		}
		
        return view('collections.show', array('collection' => $collection, 'sibling_collections' => $sibling_collections, 'messages' => $messages));
    }

    public function edit(Request $request, Collection $collection)
    {
		$this->authorize($collection);
		$messages = self::GetFlashedMessages($request);
		$configurations = self::GetConfiguration();
        $dropdowns = self::GetDropdowns();
		$collection->load('language', 'primary_artists', 'secondary_artists', 'primary_series', 'secondary_series', 'primary_tags', 'secondary_tags', 'rating', 'status');
		
		return View('collections.edit', array('configurations' => $configurations, 'collection' => $collection, 'ratings' => $dropdowns['ratings'], 'statuses' => $dropdowns['statuses'], 'languages' => $dropdowns['languages'], 'messages' => $messages));
    }

    public function update(UpdateCollectionRequest $request, Collection $collection)
    {
		$collection->updated_by = Auth::user()->id;
		return self::InsertOrUpdate($request, $collection, 'updated', 'update');
	}

    public function destroy(Collection $collection)
    {
		$this->authorize($collection);
		$collectionName = "";		
		
		DB::beginTransaction();
		try
		{
			$collectionName = $collection->name;
		
			//Force deleting for now, build out functionality for soft deleting later.
			$collection->forceDelete();
		}
		catch (\Exception $e)
		{
			DB::rollBack();
			$messages = self::BuildFlashedMessagesVariable(null, null, ["Unable to successfully delete collection $collectionName."]);
			return Redirect::back()->with(["messages" => $messages])->withInput();
		}
		DB::commit();
		
		$messages = self::BuildFlashedMessagesVariable(["Successfully purged collection $collectionName from the database."], null, null);
		return redirect()->route('index_collection')->with("messages", $messages);
    }
	
	/**
     * Export the specified as a zip file.
     *
     * @param  int  $id
     * @return Response
     */
    public function export(Collection $collection)
    {
		//Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize($collection);
		
		$fileExport = FileExportHelper::ExportCollection($collection);
		
		if ($fileExport != null)
		{
			$collectionName = $collection->name;
			$collectionName = $collectionName . ".zip";
			
			return response()->download($fileExport->path, $collectionName);
		}
		else
		{
			$messages = self::BuildFlashedMessagesVariable(null, null, ["Unable to export zipped collection file."]);
			//Return an error message saying that it couldn't create a collection export
			return Redirect::back()->with(["messages" => $messages]);
		}
	}
	
	private static function InsertOrUpdate($request, $collection, $action, $errorAction)
	{
		DB::beginTransaction();
		try
		{
			$collection->fill($request->only(['name', 'parent_id', 'description', 'status_id', 'rating_id', 'language_id']));
			$collection->canonical = $request->has('canonical');
			
			//Handle uploading cover here
			if ($request->hasFile('image')) 
			{
				//Get posted image
				$file = $request->file('image');	
				$image = ImageUploadHelper::UploadImage($file);
				$collection->cover = $image->id;
			}
			else if (Input::has('delete_cover'))
			{
				$collection->cover = null;
			}
			
			$collection->save();
			
			//Explode the artists arrays to be processed (if commonalities exist force to primary)
			$primaryArtists = array_map('LookupHelper::GetArtistName', array_map('trim', explode(',', Input::get('artist_primary'))));
			$secondaryArtists = array_diff(array_map('LookupHelper::GetArtistName', array_map('trim', explode(',', Input::get('artist_secondary')))), $primaryArtists);
		
			$collection->artists()->detach();
			MappingHelper::MapArtists($collection, $primaryArtists, true);
			MappingHelper::MapArtists($collection, $secondaryArtists, false);
			
			//Explode the series arrays to be processed (if commonalities exist force to primary)
			$primarySeries = array_map('LookupHelper::GetSeriesName', array_map('trim', explode(',', Input::get('series_primary'))));
			$secondarySeries = array_diff(array_map('LookupHelper::GetSeriesName', array_map('trim', explode(',', Input::get('series_secondary')))), $primarySeries);
		
			$collection->series()->detach();
			MappingHelper::MapSeries($collection, $primarySeries, true);
			MappingHelper::MapSeries($collection, $secondarySeries, false);

			//Explode the character arrays to be processed (if commonalities exist force to primary)
			$primaryCharacters = array_map('LookupHelper::GetCharacterName', array_map('trim', explode(',', Input::get('character_primary'))));
			$secondaryCharacters = array_diff(array_map('LookupHelper::GetCharacterName', array_map('trim', explode(',', Input::get('character_secondary')))), $primaryCharacters);
			
			$collection->characters()->detach();
			$missingPrimaryCharacters = MappingHelper::MapCharacters($collection, $primaryCharacters, true);
			$missingSecondaryCharacters = MappingHelper::MapCharacters($collection, $secondaryCharacters, false);
			
			$missingCharacters = array_unique(array_merge($missingPrimaryCharacters, $missingSecondaryCharacters));
			
			//Explode the tags array to be processed (if commonalities exist force to primary)
			$primaryTags = array_map('LookupHelper::GetTagName', array_map('trim', explode(',', Input::get('tag_primary'))));
			$secondaryTags = array_diff(array_map('LookupHelper::GetTagName', array_map('trim', explode(',', Input::get('tag_secondary')))), $primaryTags);
			
			$collection->tags()->detach();
			MappingHelper::MapTags($collection, $primaryTags, true);
			MappingHelper::MapTags($collection, $secondaryTags, false);
		}
		catch (\Exception $e)
		{	
			$cover = $collection->cover_image;
			$coverImage = null;
			DB::rollBack();
			if ($cover != null)
			{
				$coverImage = Image::where('id', '=', $collection->cover_image->id)->first();
			}
			
			//Delete the cover image from the file system if the image isn't being used anywhere else
			if (($collection->cover != null) && ($coverImage == null)) 
			{
				Storage::delete($cover->name);
				Storage::delete($cover->thumbnail);
			}
			
			$messages = self::BuildFlashedMessagesVariable(null, null, ["Unable to successfully $errorAction collection $collection->name."]);
			return Redirect::back()->with(["messages" => $messages])->withInput();
		}
		DB::commit();
		
		//Redirect to the collection that was created
		if (count($missingCharacters))
		{
			$flashedWarnings = array();
			
			if (count($missingPrimaryCharacters))
			{
				$missingPrimaryCharacters_string = "Missing primary characters were not attached to collection (appropriate series was not added to collection or character has not been defined): " . implode(", ", $missingPrimaryCharacters) . ".";
				
				array_push($flashedWarnings, $missingPrimaryCharacters_string);
			}
			
			if (count($missingSecondaryCharacters))
			{
				$missingSecondaryCharacters_string = "Missing secondary characters were not attached to collection (appropriate series was not added to collection or character has not been defined): " . implode(", ", $missingSecondaryCharacters) . ".";
				
				array_push($flashedWarnings, $missingSecondaryCharacters_string);
			}
			
			$messages = self::BuildFlashedMessagesVariable(null, ["Partially $action collection $collection->name."], $flashedWarnings);
			return redirect()->route('show_collection', ['collection' => $collection])->with("messages", $messages);
		}
		else
		{
			$messages = self::BuildFlashedMessagesVariable(["Successfully $action collection $collection->name."], null, null);
			return redirect()->route('show_collection', ['collection' => $collection])->with("messages", $messages);
		}
	}
	
	private static function GetDropdowns()
	{
		$ratings = Rating::orderBy('priority', 'asc')->get()->pluck('name', 'id');
		$statuses = Status::orderBy('priority', 'asc')->get()->pluck('name', 'id');
		$languages = Language::orderBy('name', 'asc')->get()->pluck('name', 'id');
		
		return ['ratings' => $ratings, 'statuses' => $statuses, 'languages' => $languages];
	}
	
	private static function GetConfiguration()
	{
		$configurations = Auth::user()->placeholder_configuration()->where('key', 'like', 'collection%')->get();
		$keys = ['cover', 'name', 'description', 'parent', 'primaryArtists', 'secondaryArtists', 'primarySeries', 'secondarySeries', 'primaryCharacters', 'secondaryCharacters', 'primaryTags', 'secondaryTags', 'canonical', 'language', 'rating', 'status'];
		$configurationsArray = [];
		
		foreach ($keys as $key)
		{
			$value = $configurations->where('key', '=', Config::get('constants.keys.placeholders.collection.'.$key))->first();
			$configurationsArray = array_merge($configurationsArray, [$key => $value]);
		}
		
		return $configurationsArray;
	}
}

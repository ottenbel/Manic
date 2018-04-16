<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Auth;
use DB;
use Input;
use Config;
use Storage;
use MappingHelper;
use SearchParseHelper;
use ImageUploadHelper;
use FileExportHelper;
use LookupHelper;
use ConfigurationLookupHelper;
use App\Models\Collection;
use App\Models\Image;
use App\Models\Language;
use App\Models\Rating;
use App\Models\Status;
use App\Http\Requests\Collection\StoreCollectionRequest;
use App\Http\Requests\Collection\UpdateCollectionRequest;
use App\Models\Configuration\ConfigurationRatingRestriction;

class CollectionController extends WebController
{
	public function __construct()
    {
		parent::__construct();
		
		$this->paginationKey = "pagination_collections_per_page_index";
		$this->placeholderStub = "collection";
		$this->placeheldFields = array('cover', 'name', 'description', 'parent', 'primary_artists', 'secondary_artists', 'primary_series', 'secondary_series', 'primary_characters', 'secondary_characters', 'primary_tags', 'secondary_tags', 'canonical', 'language', 'rating', 'status');
		
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
		$this->GetFlashedMessages($request);
		$search_string = $request->query('search');
		
		$collections = $searchArtists = $searchCharacters = $searchScanalators = $searchSeries = $searchTags = $searchLanguages = $searchRatings = $searchStatuses = $searchCanonicity = $searchFavourites = $invalid_tokens = null; 
		
		$collections = new Collection();
		
		$ratingRestrictions = null;
		if(Auth::check())
		{
			//Remove all entries from the blacklist
			$blacklist = Auth::user()->blacklisted_collections()->pluck('collection_id')->toArray();
			$collections = $collections->whereNotIn('id', $blacklist);
			
			$ratingRestrictions = Auth::user()->rating_restriction_configuration->where('display', '=', false)->pluck('rating_id')->toArray();
		}
		else
		{
			$ratingRestrictions = ConfigurationRatingRestriction::where('user_id', '=', null)->where('display', '=', false)->pluck('rating_id')->toArray();
		}
		
		$collections = $collections->whereNotIn('rating_id', $ratingRestrictions);
		
		//No search is conducted
		if ($search_string ==  "")
		{
			//Get all relevant collections
			$paginationCollectionsPerPageIndexCount = ConfigurationLookupHelper::LookupPaginationConfiguration($this->paginationKey)->value;
		
			$collections = $collections->orderBy('updated_at', 'desc')->paginate($paginationCollectionsPerPageIndexCount);
			$collections->appends(Input::except('page'));
		}
		else //Filter the collections return based on the search string
		{
			SearchParseHelper::Search($search_string, $collections, $searchArtists, $searchCharacters, $searchScanalators, $searchSeries, $searchTags, $searchLanguages, $searchRatings, $searchStatuses, $searchCanonicity, $searchFavourites, $invalid_tokens, $this->paginationKey);
			$collections->appends(Input::except('page'));
		}
		
		return View('collections.index', array('collections' => $collections, 'search_artists_array' => $searchArtists, 'search_characters_array' => $searchCharacters, 'search_scanalators_array' => $searchScanalators, 'search_series_array' => $searchSeries, 'search_tags_array' => $searchTags, 'search_languages_array' => $searchLanguages, 'search_ratings_array' => $searchRatings, 'search_statues_array' => $searchStatuses, 'search_canonicity_array' => $searchCanonicity, 'search_favourites_array' => $searchFavourites, 'invalid_tokens_array' => $invalid_tokens, 'messages' => $this->messages));
    }

    public function create(Request $request)
    {
		$this->authorize(Collection::class);
		$this->GetFlashedMessages($request);
		$dropdowns = self::GetDropdowns();
		$configurations = $this->GetConfiguration();
		
		return View('collections.create', array('configurations' => $configurations, 'ratings' => $dropdowns['ratings'], 'statuses' => $dropdowns['statuses'], 'languages' => $dropdowns['languages'], 'messages' => $this->messages));
    }

    public function store(StoreCollectionRequest $request)
    {
		$collection = new Collection();
		return $this->InsertOrUpdate($request, $collection, 'created', 'create');
    }
	
    public function show(Request $request, Collection $collection)
    {	
		$this->GetFlashedMessages($request);
		$sibling_collections = null;
		
		$collection->load([
		'primary_artists' => function ($query) 
			{ $query->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc'); }, 
		'secondary_artists' => function ($query) 
			{ $query->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc'); },
		'primary_series' => function ($query)
			{ $query->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc'); },
		'secondary_series' => function ($query)
			{ $query->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc'); },
		'primary_characters' => function ($query)
			{ $query->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc'); },
		'secondary_characters' => function ($query)
			{ $query->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc'); },
		'primary_tags' => function ($query)
			{ $query->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc'); },
		'secondary_tags' => function ($query)
			{ $query->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc'); },
		'volumes' => function ($query)
			{ $query->orderBy('volume_number', 'asc'); }, 
		'volumes.chapters' => function ($query)
			{ $query->orderBy('chapter_number', 'asc'); },
		'volumes.chapters.primary_scanalators' => function ($query)
			{ $query->withCount('chapters')->orderBy('chapters_count', 'desc')->orderBy('name', 'asc'); },
		'volumes.chapters.secondary_scanalators' => function ($query)
			{ $query->withCount('chapters')->orderBy('chapters_count', 'desc')->orderBy('name', 'asc'); }, 
		'rating', 'status', 'language',  'parent_collection', 'child_collections']);
		
		if($collection->parent_collection != null)
		{
			$sibling_collections = $collection->parent_collection->child_collections()->where('id', '!=', $collection->id)->get();
		}
		
		$isFavourite = false;
		if (Auth::check())
		{
			$favouriteCollection = Auth::user()->favourite_collections()->where('collection_id', '=', $collection->id)->first();
			if ($favouriteCollection != null)
			{
				$isFavourite = true;
			}
		}
		
		$isBlacklisted = false;
		if (Auth::check())
		{
			$blacklistedCollection = Auth::user()->blacklisted_collections()->where('collection_id', '=', $collection->id)->first();
			if ($blacklistedCollection != null)
			{
				$isBlacklisted = true;
			}
		}
		
        return view('collections.show', array('collection' => $collection, 'sibling_collections' => $sibling_collections, 'isFavourite' => $isFavourite, 'isBlacklist' => $isBlacklisted, 'messages' => $this->messages));
    }

    public function edit(Request $request, Collection $collection)
    {
		$this->authorize($collection);
		$this->GetFlashedMessages($request);
		$configurations = $this->GetConfiguration();
        $dropdowns = self::GetDropdowns();
		$collection->load([
		'volumes' => function ($query)
			{ $query->orderBy('volume_number', 'asc'); }, 
		'volumes.chapters' => function ($query)
			{ $query->orderBy('chapter_number', 'asc'); },
		'volumes.chapters.primary_scanalators' => function ($query)
			{ $query->withCount('chapters')->orderBy('chapters_count', 'desc')->orderBy('name', 'asc'); },
		'volumes.chapters.secondary_scanalators' => function ($query)
			{ $query->withCount('chapters')->orderBy('chapters_count', 'desc')->orderBy('name', 'asc'); }, 
		'language', 'primary_artists', 'secondary_artists', 'primary_series', 'secondary_series', 'primary_characters', 'secondary_characters',  'primary_tags', 'secondary_tags', 'rating', 'status', 'parent_collection', 'child_collections']);
		
		$isFavourite = false;
		if (Auth::check())
		{
			$favouriteCollection = Auth::user()->favourite_collections()->where('collection_id', '=', $collection->id)->first();
			if ($favouriteCollection != null)
			{
				$isFavourite = true;
			}
		}
		
		return View('collections.edit', array('configurations' => $configurations, 'collection' => $collection, 'ratings' => $dropdowns['ratings'], 'statuses' => $dropdowns['statuses'], 'languages' => $dropdowns['languages'], 'isFavourite' =>$isFavourite, 'messages' => $this->messages));
    }

    public function update(UpdateCollectionRequest $request, Collection $collection)
    {
		$collection->updated_by = Auth::user()->id;
		return $this->InsertOrUpdate($request, $collection, 'updated', 'update');
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
			$this->AddWarningMessage("Unable to successfully delete collection $collectionName.", ['collection' => $collection->id, 'error' => $e]);
			return Redirect::back()->with(["messages" => $this->messages])->withInput();
		}
		DB::commit();
		
		$this->AddSuccessMessage("Successfully purged collection $collectionName from the database.");
		return redirect()->route('index_collection')->with("messages", $this->messages);
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
			$this->AddWarningMessage("Unable to export zipped collection file.", ['collection' => $collection->id]);
			//Return an error message saying that it couldn't create a collection export
			return Redirect::back()->with(["messages" => $this->messages]);
		}
	}
	
	private function InsertOrUpdate($request, $collection, $action, $errorAction)
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
			
			$this->AddWarningMessage("Unable to successfully $errorAction collection $collection->name.", ['collection' => $collection->id, 'error' => $e]);
			return Redirect::back()->with(["messages" => $this->messages])->withInput();
		}
		DB::commit();
		
		//Redirect to the collection that was created
		if (count($missingCharacters))
		{	
			if (count($missingPrimaryCharacters))
			{
				
				$missingPrimaryCharacters_string = "Missing primary characters were not attached to collection (appropriate series was not added to collection or character has not been defined): " . implode(", ", $missingPrimaryCharacters) . ".";
				
				$this->AddDataMessage($missingPrimaryCharacters_string);
			}
			
			if (count($missingSecondaryCharacters))
			{
				$missingSecondaryCharacters_string = "Missing secondary characters were not attached to collection (appropriate series was not added to collection or character has not been defined): " . implode(", ", $missingSecondaryCharacters) . ".";
				
				$this->AddDataMessage($missingSecondaryCharacters_string);
			}
			
			$this->AddDataMessage("Partially $action collection $collection->name.");
			return redirect()->route('show_collection', ['collection' => $collection])->with("messages", $this->messages);
		}
		else
		{
			$this->AddSuccessMessage("Successfully $action collection $collection->name.");
			return redirect()->route('show_collection', ['collection' => $collection])->with("messages", $this->messages);
		}
	}
	
	private static function GetDropdowns()
	{
		$ratings = Rating::orderBy('priority', 'asc')->get()->pluck('name', 'id');
		$statuses = Status::orderBy('priority', 'asc')->get()->pluck('name', 'id');
		$languages = Language::orderBy('name', 'asc')->get()->pluck('name', 'id');
		
		return ['ratings' => $ratings, 'statuses' => $statuses, 'languages' => $languages];
	}
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Auth;
use Input;
use Config;
use MappingHelper;
use SearchParseHelper;
use ImageUploadHelper;
use InterventionImage;
use FileExportHelper;
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

class CollectionController extends Controller
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
		$search_string = $request->query('search');
		
		$collections = null;
		$searchArtists = null;
		$searchCharacters = null;
		$searchScanalators = null; 
		$searchSeries = null; 
		$searchTags = null; 
		$searchLanguages = null;
		$searchRatings = null;
		$searchStatuses = null;
		$searchCanonicity = null;
		$invalid_tokens = null;
		
		//No search is conducted
		if ($search_string ==  "")
		{
			//Get all relevant collections
			$collections = Collection::with('language', 'primary_artists', 'secondary_artists', 'primary_series', 'secondary_series', 'primary_tags', 'secondary_tags', 'rating', 'status')->orderBy('updated_at', 'desc')->paginate(Config::get('constants.pagination.collectionsPerPageIndex'));
			$collections->appends(Input::except('page'));
		}
		else //Filter the collections return based on the search string
		{
			SearchParseHelper::Search($search_string, $collections, $searchArtists, $searchCharacters, $searchScanalators, $searchSeries, $searchTags, $searchLanguages, $searchRatings, $searchStatuses, $searchCanonicity, $invalid_tokens);
			$collections->appends(Input::except('page'));
		}
		
		return View('collections.index', array('collections' => $collections, 'search_artists_array' => $searchArtists, 'search_characters_array' => $searchCharacters, 'search_scanalators_array' => $searchScanalators, 'search_series_array' => $searchSeries, 'search_tags_array' => $searchTags, 'search_languages_array' => $searchLanguages, 'search_ratings_array' => $searchRatings, 'search_statues_array' => $searchStatuses, 'search_canonicity_array' => $searchCanonicity, 'invalid_tokens_array' => $invalid_tokens, 'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
		//Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize(Collection::class);
		
		$ratings = Rating::orderBy('priority', 'asc')->get()->pluck('name', 'id');
		$statuses = Status::orderBy('priority', 'asc')->get()->pluck('name', 'id');
		$languages = Language::orderBy('name', 'asc')->get()->pluck('name', 'id');
		
		return View('collections.create', array('ratings' => $ratings, 'statuses' => $statuses, 'languages' => $languages));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
		//Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize(Collection::class);
		
		$this->validate($request, [
			'name' => 'required|unique:collections,name',
			'parent_id' => 'nullable|exists:collections,id',
			'artist_primary' => 'regex:/^[^:-]+$/',
			'artist_secondary' => 'regex:/^[^:-]+$/',
			'series_primary' => 'regex:/^[^:-]+$/',
			'series_secondary' => 'regex:/^[^:-]+$/',
			'character_primary' => 'regex:/^[^:-]+$/',
			'character_secondary' => 'regex:/^[^:-]+$/',
			'tag_primary' => 'regex:/^[^:-]+$/',
			'tag_secondary' => 'regex:/^[^:-]+$/',
			'rating' => 'nullable|exists:ratings,id',
			'status' => 'nullable|exists:statuses,id',
			'language' => 'nullable|exists:languages,id',
			'image' => 'nullable|image'
		]);
		
		$collection = new Collection();
		$collection->name = trim(Input::get('name'));
		$collection->parent_id = trim(Input::get('parent_id'));
		$collection->description = trim(Input::get('description'));
		if (Input::has('canonical'))
		{
			$collection->canonical = true;
		}
		else
		{
			$collection->canonical = false;
		}
		$collection->status_id = Input::get('statuses');
		$collection->rating_id = Input::get('ratings');
		$collection->language_id = Input::get('language');
		
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
		$artist_primary_array = array_map('trim', explode(',', Input::get('artist_primary')));
		$artist_secondary_array = array_diff(array_map('trim', explode(',', Input::get('artist_secondary'))), $artist_primary_array);
	
		$collection->artists()->detach();
		MappingHelper::MapArtists($collection, $artist_primary_array, true);
		MappingHelper::MapArtists($collection, $artist_secondary_array, false);
		
		//Explode the series arrays to be processed (if commonalities exist force to primary)
		$series_primary_array = array_map('trim', explode(',', Input::get('series_primary')));
		$series_secondary_array = array_diff(array_map('trim', explode(',', Input::get('series_secondary'))), $series_primary_array);
	
		$collection->series()->detach();
		MappingHelper::MapSeries($collection, $series_primary_array, true);
		MappingHelper::MapSeries($collection, $series_secondary_array, false);

		//Explode the character arrays to be processed (if commonalities exist force to primary)
		$characters_primary_array = array_map('trim', explode(',', Input::get('character_primary')));
		$characters_secondary_array = array_diff(array_map('trim', explode(',', Input::get('character_secondary'))), $characters_primary_array);
		
		$collection->characters()->detach();
		$missing_primary_characters = MappingHelper::MapCharacters($collection, $characters_primary_array, true);
		$missing_secondary_characters = MappingHelper::MapCharacters($collection, $characters_secondary_array, false);
		
		$missing_characters = array_unique(array_merge($missing_primary_characters, $missing_secondary_characters));
		
		//Explode the tags array to be processed (if commonalities exist force to primary)
		$tags_primary_array = array_map('trim', explode(',', Input::get('tag_primary')));
		$tags_secondary_array = array_diff(array_map('trim', explode(',', Input::get('tag_secondary'))), $tags_primary_array);
		
		$collection->tags()->detach();
		MappingHelper::MapTags($collection, $tags_primary_array, true);
		MappingHelper::MapTags($collection, $tags_secondary_array, false);
		
		//Redirect to the collection that was created
		if (count($missing_characters))
		{
			$flashed_warning_array = array();
			
			if (count($missing_primary_characters))
			{
				$missing_primary_characters_string = "Missing primary characters were not attached to collection (appropriate series was not added to collection or character has not been defined): " . implode(", ", $missing_primary_characters) . ".";
				
				$flashed_warning_array = array_push($flashed_warning_array, $missing_primary_characters_string);
			}
			
			if (count($missing_secondary_characters))
			{
				$missing_secondary_characters_string = "Missing secondary characters were not attached to collection (appropriate series was not added to collection or character has not been defined): " . implode(", ", $missing_secondary_characters) . ".";
				
				$flashed_warning_array = array_push($flashed_warning_array, $missing_secondary_characters_string);
			}
			
			return redirect()->route('show_collection', ['collection' => $collection])->with("flashed_data", array("Partially created collection $collection->name."))->with("flashed_warning", $flashed_warning_array);
		}
		else
		{
			return redirect()->route('show_collection', ['collection' => $collection])->with("flashed_success", array("Successfully created collection $collection->name."));
		}
    }
	
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Request $request, Collection $collection)
    {	
		$flashed_success = $request->session()->get('flashed_success');
		$flashed_data = $request->session()->get('flashed_data');
		$flashed_warning = $request->session()->get('flashed_warning');
		
		$sibling_collections = null;
		
		if($collection->parent_collection != null)
		{
			$sibling_collections = $collection->parent_collection->child_collections()->where('id', '!=', $collection->id)->get();
		}
		
        return view('collections.show', array('collection' => $collection, 'sibling_collections' => $sibling_collections, 'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $request, Collection $collection)
    {
		//Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize($collection);
		
		$flashed_success = $request->session()->get('flashed_success');
		$flashed_data = $request->session()->get('flashed_data');
		$flashed_warning = $request->session()->get('flashed_warning');
		
        $ratings = Rating::orderBy('priority', 'asc')->get()->pluck('name', 'id');
		$statuses = Status::orderBy('priority', 'asc')->get()->pluck('name', 'id');
		$languages = Language::orderBy('name', 'asc')->get()->pluck('name', 'id');
		$collection->load('language', 'primary_artists', 'secondary_artists', 'primary_series', 'secondary_series', 'primary_tags', 'secondary_tags', 'rating', 'status');
		
		return View('collections.edit', array('collection' => $collection, 'ratings' => $ratings, 'statuses' => $statuses, 'languages' => $languages, 'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, Collection $collection)
    {
		//Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize($collection);
		
		$collection_id = $collection->id;
        $this->validate($request, [
			'name' => ['required',
						Rule::unique('collections')->ignore($collection->id)],
			'parent_id' => 'nullable|exists:collections,id',
			'artist_primary' => 'regex:/^[^:-]+$/',
			'artist_secondary' => 'regex:/^[^:-]+$/',
			'series_primary' => 'regex:/^[^:-]+$/',
			'series_secondary' => 'regex:/^[^:-]+$/',
			'character_primary' => 'regex:/^[^:-]+$/',
			'character_secondary' => 'regex:/^[^:-]+$/',
			'tag_primary' => 'regex:/^[^:-]+$/',
			'tag_secondary' => 'regex:/^[^:-]+$/',
			'rating' => 'nullable|exists:ratings,id',
			'status' => 'nullable|exists:statuses,id',
			'language' => 'nullable|exists:languages,id',
			'image' => 'nullable|image'
		]);
		
		$collection->name = trim(Input::get('name'));
		$collection->parent_id = trim(Input::get('parent_id'));
		$collection->description = trim(Input::get('description'));
		if (Input::has('canonical'))
		{
			$collection->canonical = true;
		}
		else
		{
			$collection->canonical = false;
		}
		$collection->status_id = Input::get('statuses');
		$collection->rating_id = Input::get('ratings');
		$collection->language_id = Input::get('language');
		$collection->updated_by = Auth::user()->id;
		
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
		$artist_primary_array = array_map('trim', explode(',', Input::get('artist_primary')));
		$artist_secondary_array = array_diff(array_map('trim', explode(',', Input::get('artist_secondary'))), $artist_primary_array);
	
		$collection->artists()->detach();
		MappingHelper::MapArtists($collection, $artist_primary_array, true);
		MappingHelper::MapArtists($collection, $artist_secondary_array, false);
		
		//Explode the series arrays to be processed (if commonalities exist force to primary)
		$series_primary_array = array_map('trim', explode(',', Input::get('series_primary')));
		$series_secondary_array = array_diff(array_map('trim', explode(',', Input::get('series_secondary'))), $series_primary_array);
	
		$collection->series()->detach();
		MappingHelper::MapSeries($collection, $series_primary_array, true);
		MappingHelper::MapSeries($collection, $series_secondary_array, false);
		
		//Explode the character arrays to be processed (if commonalities exist force to primary)
		$characters_primary_array = array_map('trim', explode(',', Input::get('character_primary')));
		$characters_secondary_array = array_diff(array_map('trim', explode(',', Input::get('character_secondary'))), $characters_primary_array);
		
		$collection->characters()->detach();
		$missing_primary_characters = MappingHelper::MapCharacters($collection, $characters_primary_array, true);
		$missing_secondary_characters = MappingHelper::MapCharacters($collection, $characters_secondary_array, false);
		
		$missing_characters = array_unique(array_merge($missing_primary_characters, $missing_secondary_characters));
		
		//Explode the tags array to be processed (if commonalities exist force to primary)
		$tags_primary_array = array_map('trim', explode(',', Input::get('tag_primary')));
		$tags_secondary_array = array_diff(array_map('trim', explode(',', Input::get('tag_secondary'))), $tags_primary_array);
		
		$collection->tags()->detach();
		MappingHelper::MapTags($collection, $tags_primary_array, true);
		MappingHelper::MapTags($collection, $tags_secondary_array, false);
		
		//Redirect to the collection that was created
		if (count($missing_characters))
		{
			$flashed_warning_array = array();
			
			if (count($missing_primary_characters))
			{
				$missing_primary_characters_string = "Missing primary characters were not attached to collection (appropriate series was not added to collection or character has not been defined): " . implode(", ", $missing_primary_characters) . ". ";
				
				array_push($flashed_warning_array, $missing_primary_characters_string);
			}
			
			if (count($missing_secondary_characters))
			{
				$missing_secondary_characters_string = "Missing secondary characters were not attached to collection (appropriate series was not added to collection or character has not been defined): " . implode(", ", $missing_secondary_characters) . ". ";
				
				array_push($flashed_warning_array, $missing_secondary_characters_string);
			}
		
			//Redirect to the collection that was created
			return redirect()->route('show_collection', ['collection' => $collection])->with("flashed_data", array("Partially updated collection $collection->name."))->with("flashed_warning", $flashed_warning_array);
		}
		else
		{
			return redirect()->route('show_collection', ['collection' => $collection])->with("flashed_success", array("Successfully updated collection $collection->name."));
		}
	}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Collection $collection)
    {
        //Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize($collection);
		
		$collectionName = $collection->name;
		
		//Force deleting for now, build out functionality for soft deleting later.
		$collection->forceDelete();
		
		return redirect()->route('index_collection')->with("flashed_success", array("Successfully purged collection $collectionName from the database."));
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
			//Return an error message saying that it couldn't create a collection export
			return Redirect::back()->with(["flashed_warning" => array("Unable to export zipped collection file.")]);
		}
	}
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Auth;
use Input;
use Config;
use MappingHelper;
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
		
        //Get all relevant collections
		$collections = Collection::with('language', 'primary_artists', 'secondary_artists', 'primary_series', 'secondary_series', 'primary_tags', 'secondary_tags', 'rating', 'status')->orderBy('updated_at', 'desc')->paginate(Config::get('constants.pagination.collectionsPerPageIndex'));
		
		return View('collections.index', array('collections' => $collections, 'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
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
		
		$ratings = Rating::orderBy('priority', 'asc')->get();
		$statuses = Status::orderBy('priority', 'asc')->get();
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
			
			//Calculate file hash
			$hash = hash_file("sha256", $file->getPathName());
			
			//Does the image already exist?
			$image = Image::where('hash', '=', $hash)->first();
			if (count($image))
			{
				//File already exists (use existing mapping)
				$collection->cover = $image->id;
			}
			else
			{
				$path = $file->store('public/images');
				$file_extension = $file->guessExtension();
				
				$image = new Image();
				$image->name = str_replace('public', 'storage', $path);
				$image->hash = $hash;
				$image->extension = $file_extension;
				$image->save();
				
				$collection->cover = $image->id;
			}
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
				$missing_primary_characters_string = "Missing primary characters were not attached to collection (appropriate series was not added to collection or character has not been defined): " + implode(", ", $missing_primary_characters) + ".";
				
				$flashed_warning_array = array_push($flashed_warning_array, $missing_primary_characters_string);
			}
			
			if (count($missing_secondary_characters))
			{
				$missing_secondary_characters_string = "Missing secondary characters were not attached to collection (appropriate series was not added to collection or character has not been defined): " + implode(", ", $missing_secondary_characters) + ".";
				
				$flashed_warning_array = array_push($flashed_warning_array, $missing_secondary_characters_string);
			}
			
			return redirect()->action('CollectionController@show', [$collection])->with("flashed_data", array("Partially created collection $collection->name."))->with("flashed_warning", $flashed_warning_array);
		}
		else
		{
			return redirect()->action('CollectionController@show', [$collection])->with("flashed_success", array("Successfully created collection $collection->name."));
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
		
        $ratings = Rating::orderBy('priority', 'asc')->get();
		$statuses = Status::orderBy('priority', 'asc')->get();
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
			
			//Calculate file hash
			$hash = hash_file("sha256", $file->getPathName());
			
			//Does the image already exist?
			$image = Image::where('hash', '=', $hash)->first();
			if (count($image))
			{
				//File already exists (use existing mapping)
				$collection->cover = $image->id;
			}
			else
			{
				$path = $file->store('public/images');
				$file_extension = $file->guessExtension();
				
				$image = new Image();
				$image->name = str_replace('public', 'storage', $path);
				$image->hash = $hash;
				$image->extension = $file_extension;
				$image->save();
				
				$collection->cover = $image->id;
			}
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
			return redirect()->action('CollectionController@show', [$collection])->with("flashed_data", array("Partially updated collection $collection->name."))->with("flashed_warning", $flashed_warning_array);
		}
		else
		{
			return redirect()->action('CollectionController@show', [$collection])->with("flashed_success", array("Successfully updated collection $collection->name."));
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
		
		
    }
}

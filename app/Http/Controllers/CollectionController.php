<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Auth;
use Input;
use App\Artist;
use App\Character;
use App\Collection;
use App\Image;
use App\Language;
use App\Rating;
use App\Series;
use App\Status;
use App\Tag;

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
		$collections = Collection::with('language', 'primary_artists', 'secondary_artists', 'primary_series', 'secondary_series', 'primary_tags', 'secondary_tags', 'rating', 'status')->orderBy('updated_at', 'desc')->paginate(10);
		
		return View('collections.index', array('collections' => $collections, 'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
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
		$collection->created_by = Auth::user()->id;
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
				$image->created_by = Auth::user()->id;
				$image->updated_by = Auth::user()->id;
				
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
		$this->map_artists($collection, $artist_primary_array, true);
		$this->map_artists($collection, $artist_secondary_array, false);
		
		//Explode the series arrays to be processed (if commonalities exist force to primary)
		$series_primary_array = array_map('trim', explode(',', Input::get('series_primary')));
		$series_secondary_array = array_diff(array_map('trim', explode(',', Input::get('series_secondary'))), $series_primary_array);
	
		$collection->series()->detach();
		$this->map_series($collection, $series_primary_array, true);
		$this->map_series($collection, $series_secondary_array, false);

		//Explode the character arrays to be processed (if commonalities exist force to primary)
		$characters_primary_array = array_map('trim', explode(',', Input::get('character_primary')));
		$characters_secondary_array = array_diff(array_map('trim', explode(',', Input::get('character_secondary'))), $characters_primary_array);
		
		$collection->characters()->detach();
		$missing_primary_characters = $this->map_characters($collection, $characters_primary_array, true);
		$missing_secondary_characters = $this->map_characters($collection, $characters_secondary_array, false);
		
		$missing_characters = array_unique(array_merge($missing_primary_characters, $missing_secondary_characters));
		
		//Explode the tags array to be processed (if commonalities exist force to primary)
		$tags_primary_array = array_map('trim', explode(',', Input::get('tag_primary')));
		$tags_secondary_array = array_diff(array_map('trim', explode(',', Input::get('tag_secondary'))), $tags_primary_array);
		
		$collection->tags()->detach();
		$this->map_tags($collection, $tags_primary_array, true);
		$this->map_tags($collection, $tags_secondary_array, false);
		
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
		$collection_id = $collection->id;
        $this->validate($request, [
			'name' => ['required',
						Rule::unique('collections')->where(function ($query){
							$query->where('id', '!=', trim(Input::get('collection_id')));
						})],
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
				$image->created_by = Auth::user()->id;
				$image->updated_by = Auth::user()->id;
				
				$image->save();
				
				$collection->cover = $image->id;
			}
		}
		
		$collection->save();
		
		//Explode the artists arrays to be processed (if commonalities exist force to primary)
		$artist_primary_array = array_map('trim', explode(',', Input::get('artist_primary')));
		$artist_secondary_array = array_diff(array_map('trim', explode(',', Input::get('artist_secondary'))), $artist_primary_array);
	
		$collection->artists()->detach();
		$this->map_artists($collection, $artist_primary_array, true);
		$this->map_artists($collection, $artist_secondary_array, false);
		
		//Explode the series arrays to be processed (if commonalities exist force to primary)
		$series_primary_array = array_map('trim', explode(',', Input::get('series_primary')));
		$series_secondary_array = array_diff(array_map('trim', explode(',', Input::get('series_secondary'))), $series_primary_array);
	
		$collection->series()->detach();
		$this->map_series($collection, $series_primary_array, true);
		$this->map_series($collection, $series_secondary_array, false);
		
		//Explode the character arrays to be processed (if commonalities exist force to primary)
		$characters_primary_array = array_map('trim', explode(',', Input::get('character_primary')));
		$characters_secondary_array = array_diff(array_map('trim', explode(',', Input::get('character_secondary'))), $characters_primary_array);
		
		$collection->characters()->detach();
		$missing_primary_characters = $this->map_characters($collection, $characters_primary_array, true);
		$missing_secondary_characters = $this->map_characters($collection, $characters_secondary_array, false);
		
		$missing_characters = array_unique(array_merge($missing_primary_characters, $missing_secondary_characters));
		
		//Explode the tags array to be processed (if commonalities exist force to primary)
		$tags_primary_array = array_map('trim', explode(',', Input::get('tag_primary')));
		$tags_secondary_array = array_diff(array_map('trim', explode(',', Input::get('tag_secondary'))), $tags_primary_array);
		
		$collection->tags()->detach();
		$this->map_tags($collection, $tags_primary_array, true);
		$this->map_tags($collection, $tags_secondary_array, false);
		
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
        $collection->delete();
		
		return redirect()->action()->with();
    }
	
	private function map_artists(&$collection, $artist_array, $isPrimary)
	{
		foreach ($artist_array as $artist_name)
		{
			if (trim($artist_name) != "")
			{
				$artist = Artist::where('name', '=', $artist_name)->first();			
				if (count($artist))
				{
					$collection->artists()->attach($artist, ['primary' => $isPrimary]);
				}
				else
				{
					//Create a new artist
					$artist = new Artist;
					$artist->name = $artist_name;
					$artist->created_by = Auth::user()->id;
					$artist->updated_by = Auth::user()->id;
					$artist->save();
					
					$collection->artists()->attach($artist, ['primary' => $isPrimary]);
				}
			}
		}
	}
	
	private function map_series(&$collection, $series_array, $isPrimary)
	{
		foreach ($series_array as $series_name)
		{
			if (trim($series_name) != "")
			{
				$series = Series::where('name', '=', $series_name)->first();
				if (count($series))
				{
					$collection->series()->attach($series, ['primary' => $isPrimary]);
				}
				else
				{
					//Create a new series
					$series = new Series;
					$series->name = $series_name;
					$series->created_by = Auth::user()->id;
					$series->updated_by = Auth::user()->id;
					$series->save();
					
					$collection->series()->attach($series, ['primary' => $isPrimary]);
				}
			}
		}
	}
	
	private function map_characters(&$collection, $characters_array, $isPrimary)
	{
		$missing_characters = array();
		
		foreach ($characters_array as $character_name)
		{
			if (trim($character_name) != "")
			{
				$character = Character::where('name', '=', $character_name)->first();
				if ($character != null)
				{
					$series = $collection->series->where('id', '=', $character->series_id)->first();
					if ($series != null)
					{
						$collection->characters()->attach($character, ['primary' => $isPrimary]);
					}
					else
					{
						array_push($missing_characters, trim($character_name));
					}
				}
				else
				{
					array_push($missing_characters, trim($character_name));
				}
			}
		}
		return $missing_characters;
	}
	
	private function map_tags(&$collection, $tags_array, $isPrimary)
	{
		foreach ($tags_array as $tag_name)
		{
			if (trim($tag_name) != "")
			{
				$tag = Tag::where('name', '=', $tag_name)->first();
				if (count($tag))
				{
					$collection->tags()->attach($tag, ['primary' => $isPrimary]);
				}
				else
				{
					//Create a new tag
					$tag = new Tag;
					$tag->name = $tag_name;
					$tag->created_by = Auth::user()->id;
					$tag->updated_by = Auth::user()->id;
					$tag->save();
					
					$collection->tags()->attach($tag, ['primary' => $isPrimary]);
				}
			}
		}
	}

}

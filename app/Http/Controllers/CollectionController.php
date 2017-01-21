<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Input;
use Auth;
use App\Collection;
use App\Status;
use App\Rating;
use App\Image;
use App\Language;
use App\Artist;
use App\Series;
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
        //Get all relevant collections
		$collections = Collection::with('language', 'primary_artists', 'secondary_artists', 'primary_series', 'secondary_series', 'primary_tags', 'secondary_tags', 'rating', 'status')->paginate(25);
		
		$flashed_data = $request->session()->get('flashed_data');
		
		return View('collections.index', array('collections' => $collections, 'flashed_data' => $flashed_data));
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
		
		return View('collections.create', array('ratings' => $ratings, 'statuses' => $statuses, 'languages' => $languages, ));
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
				
				$image = new Image;
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
		
		//Explode the tags array to be processed (if commonalities exist force to primary)
		$tags_primary_array = array_map('trim', explode(',', Input::get('tag_primary')));
		$tags_secondary_array = array_diff(array_map('trim', explode(',', Input::get('tag_secondary'))), $tags_primary_array);
		
		$collection->tags()->detach();
		$this->map_tags($collection, $tags_primary_array, true);
		$this->map_tags($collection, $tags_secondary_array, false);
		
		//Redirect to the collection that was created
		return redirect()->action('CollectionController@show', [$collection])->with('flashed_data', 'Successfully created new collection.');
    }
	
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Request $request, $id)
    {
		$collection = Collection::where('id', '=', $id)->first();
		
		$sibling_collections = null;
		
		if($collection->parent_collection != null)
		{
			$sibling_collections = $collection->parent_collection->child_collections()->where('id', '!=', $collection->id)->get();
		}
		
		$flashed_data = $request->session()->get('flashed_data');
		
        return view('collections.show', array('collection' => $collection, 'sibling_collections' => $sibling_collections, 'flashed_data' => $flashed_data));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $request, $id)
    {
		$collection = Collection::where('id', '=', $id)->get();
		
        $ratings = Rating::orderBy('priority', 'asc')->get();
		$statuses = Status::orderBy('priority', 'asc')->get();
		$languages = Language::orderBy('name', 'asc')->get()->pluck('name', 'id');
		$collection->load('language', 'primary_artists', 'secondary_artists', 'primary_series', 'secondary_series', 'primary_tags', 'secondary_tags', 'rating', 'status');
		
		$flashed_data = $request->session()->get('flashed_data');
		
		return View('collections.edit', array('collection' => $collection, 'ratings' => $ratings, 'statuses' => $statuses, 'languages' => $languages, 'flashed_data' => $flashed_data));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Collection $collection)
    {
        $this->validate($request, [
			'name' => 'required|unique:collections,name',
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
				
				$image = new Image;
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
		
		//Explode the tags array to be processed (if commonalities exist force to primary)
		$tags_primary_array = array_map('trim', explode(',', Input::get('tag_primary')));
		$tags_secondary_array = array_diff(array_map('trim', explode(',', Input::get('tag_secondary'))), $tags_primary_array);
		
		$collection->tags()->detach();
		$this->map_tags($collection, $tags_primary_array, true);
		$this->map_tags($collection, $tags_secondary_array, false);
		
		//Redirect to the collection that was created
		return redirect()->action('CollectionController@show', [$collection])->with('flashed_data', 'Successfully created new collection.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Collection $collection)
    {
        //
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

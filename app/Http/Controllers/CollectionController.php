<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Collection;
use App\Status;
use App\Rating;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //Get all relevant collections
		$collections = Collection::paginate(25);
		
		return View('collections.index', compact('collections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
		$ratings = Rating::all()->orderBy('priority', 'asc');
		$status = Status::all()->orderBy('priority', 'asc');
		
		return View('collections.create', array('ratings' => $ratings, 'statuses' => $status));
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
			'parent_collection' => 'nullable|exists:collections,id',
			'rating' => 'nullable|exists:ratings,id',
			'status' => 'nullable|exists:statuses,id'
		]);
		
		$collection = new Collection();
		$collection->name = Input::get('name')->trim();
		$collection->description = Input::get('description')->trim();
		$collection->canonical = Input::get('canonical');
		$collection->status = Input::get('status');
		$collection->rating = Input::get('rating');
		$collection->created_by = Auth::user()->id;
		$collection->updated_by = Auth::user()->id;
		
		//Handle uploading cover here
		
		//Explode the artists arrays to be processed (if commonalities exist force to primary)
		$artist_primary_array = array_map('trim', explode(',', Input::get('artist_primary')));
		$artist_secondary_array = array_diff(array_map('trim', explode(',', Input::get('artist_secondary'))), $artist_primary_array);
		
		map_artists($collection, $artist_primary_array, true);
		map_artists($collection, $artist_secondary_array, false);
		
		//Explode the series arrays to be processed (if commonalities exist force to primary)
		$series_primary_array = array_map('trim', explode(',', Input::get('series_primary')));
		$series_secondary_array = array_diff(array_map('trim', explode(',', Input::get('series_secondary'))), $series_primary_array);
		
		map_series($collection, $series_primary_array, true);
		map_series($collection, $series_secondary_array, false);
		
		//Explode the tags array to be processed (if commonalities exist force to primary)
		$tags_primary_array = array_map('trim', explode(',', Input::get('tags_primary')));
		$tags_secondary_array = array_diff(array_map('trim', explode(',', Input::get('tags_secondary'))), $tags_primary_array);
		
		map_tags($collection, $tags_primary_array, true);
		map_tags($collection, $tags_secondary_array, false);
		
		$collection->save();
    }
	
	private function map_artists(&$collection, $artist_array, $isPrimary)
	{
		$collection->artists()->detach();
		foreach ($artist_array as $artist_name)
		{
			$artist = Artist::where('name', '=', $artist_name)->first();
			
			if ($artist != null)
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
	
	private function map_series(&$collection, $series_array, $isPrimary)
	{
		$collection->series()->detach();
		foreach ($series_array as $series_name)
		{
			$series = Series::where('name', '=', $series_name)->first()
			if ($series != null)
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
	
	private function map_tags(&$collection, $tags_array, $isPrimary)
	{
		$collection->tags()->detach();
		foreach ($tags_array as $tag_name)
		{
			$tag = Tag::where('name', '=', $tag_name)->first()
			if ($tag != null)
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
	
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}

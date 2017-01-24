<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Input;
use App\Collection;
use App\Image;
use App\Volume;

class VolumeController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request, Collection $collection)
    {
		$flashed_data = $request->session()->get('flashed_data');
		
        return View('volumes.create', array('collection' => $collection, 'flashed_data' => $flashed_data));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
			'number' => ['required',
						'integer',
						'min:0',
						Rule::unique('volumes')->where(function ($query){
							$query->where('collection_id', Input::get('collection_id')));
						}],
			'collection_id' => 'required|exists:collections,id',
			'image' => 'nullable|image'
		]);
		
		$collection_id = trim(Input::get('collection_id'));
		
		$collection = Collection::where('id', '=', $collection_id)->first();
		
		$volume = new Volume();
		$volume->collection_id = $collection_id;
		$volume->number = trim(Input::get('number'));
		$volume->name = trim(Input::get('name'));
		$volume->created_by = Auth::user()->id;
		$volume->updated_by = Auth::user()->id;
		
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
				$volume->cover = $image->id;
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
				
				$volume->cover = $image->id;
			}
		}
		
		$volume->save();
		
		$collection->updated_by = Auth::user()->id;
		$collection->save();
		
		return redirect()->action('CollectionController@show', [$collection])->with("flashed_data", "Successfully created new volume #$volume->number on collection $collection->name.");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $request, Volume $volume)
    {
        $flashed_data = $request->session()->get('flashed_data');
		
        return View('volumes.edit', array('volume' => $volume, 'flashed_data' => $flashed_data));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, Volume $volume)
    {
        $this->validate($request, [
			'number' => ['required',
						'integer',
						'min:0',
						Rule::unique('volumes')->where(function ($query){
							$query->where('collection_id', Input::get('collection_id')));
						}],
			'collection_id' => 'required|exists:collections,id',
			'image' => 'nullable|image'
		]);
		
		$collection = $volume->collection;
		
		$volume->number = trim(Input::get('number'));
		$volume->name = trim(Input::get('name'));
		$volume->created_by = Auth::user()->id;
		$volume->updated_by = Auth::user()->id;
		
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
				$volume->cover = $image->id;
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
				
				$volume->cover = $image->id;
			}
		}
		else if (Input::has('delete_cover'))
		{
			$volume->cover = null;
		}
		
		$volume->save();
		
		$collection->updated_by = Auth::user()->id;
		$collection->save();
		
		return redirect()->action('CollectionController@show', [$collection])->with("flashed_data", "Successfully updated volume #$volume->number on collection $collection->name.");
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

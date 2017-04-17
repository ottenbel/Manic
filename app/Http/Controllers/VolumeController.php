<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Auth;
use Input;
use App\Models\Collection;
use App\Models\Image;
use App\Models\Volume;

class VolumeController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request, Collection $collection)
    {
		//Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize(Volume::class);
		
		$flashed_success = $request->session()->get('flashed_success');
		$flashed_data = $request->session()->get('flashed_data');
		$flashed_warning = $request->session()->get('flashed_warning');
		
        return View('volumes.create', array('collection' => $collection, 'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
		//Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize(Volume::class);
		
        $this->validate($request, [
			'collection_id' => 'required|exists:collections,id',
			'volume_number' => ['required',
						'integer',
						'min:0',
						Rule::unique('volumes')->where(function ($query){
							$query->where('collection_id', trim(Input::get('collection_id')));
						})],
			'image' => 'nullable|image'
		]);
		
		$collection_id = trim(Input::get('collection_id'));
		
		$collection = Collection::where('id', '=', $collection_id)->first();
		
		$volume = new Volume();
		$volume->collection_id = $collection_id;
		$volume->volume_number = trim(Input::get('volume_number'));
		$volume->name = trim(Input::get('name'));
		
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
				$image->save();
				
				$volume->cover = $image->id;
			}
		}
		
		$volume->save();
				
		return redirect()->action('CollectionController@show', [$collection])->with("flashed_success", array("Successfully created new volume #$volume->volume_number on collection $collection->name."));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $request, Volume $volume)
    {
		//Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize($volume);
		
        $flashed_success = $request->session()->get('flashed_success');
		$flashed_data = $request->session()->get('flashed_data');
		$flashed_warning = $request->session()->get('flashed_warning');
		
        return View('volumes.edit', array('volume' => $volume, 'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, Volume $volume)
    {
		//Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize($volume);
		
        $this->validate($request, [
			'collection_id' => 'required|exists:collections,id',
			'volume_number' => ['required',
						'integer',
						'min:0',
						Rule::unique('volumes')->where(function ($query){
							$query->where('collection_id', trim(Input::get('collection_id')))
							->where('id', '!=', trim(Input::get('volume_id')));
						})],
			'image' => 'nullable|image'
		]);
		
		$collection = $volume->collection;
		
		$volume->volume_number = trim(Input::get('volume_number'));
		$volume->name = trim(Input::get('name'));
		
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
				$image->save();
				
				$volume->cover = $image->id;
			}
		}
		else if (Input::has('delete_cover'))
		{
			$volume->cover = null;
		}
		
		$volume->save();
		
		return redirect()->action('CollectionController@show', [$collection])->with("flashed_success", array("Successfully updated volume #$volume->volume_number on collection $collection->name."));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Volume $volume)
    {
        //Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize($volume);
    }
}

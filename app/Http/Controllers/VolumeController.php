<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Auth;
use Config;
use Input;
use InterventionImage;
use ImageUploadHelper;
use FileExportHelper;
use App\Models\Collection;
use App\Models\Image;
use App\Models\Volume;

class VolumeController extends WebController
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
		
		$messages = self::GetFlashedMessages($request);
		$configurations = self::GetConfiguration($collection);
		
        return View('volumes.create', array('configurations' => $configurations, 'collection' => $collection, 'messages' => $messages));
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
			$image = ImageUploadHelper::UploadImage($file);
			$volume->cover = $image->id;
		} 
		
		$volume->save();
		
		$messages = self::BuildFlashedMessagesVariable(["Successfully created new volume #$volume->volume_number on collection $collection->name."], null, null);
		
		return redirect()->route('show_collection', ['collection' => $collection])->with("messages", $messages);
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
		
        $messages = self::GetFlashedMessages($request);
		$configurations = self::GetConfiguration();
		
        return View('volumes.edit', array('configurations' => $configurations, 'volume' => $volume, 'messages' => $messages));
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
		
		$volumeNumber = trim(Input::get('volume_number'));
		
		if ($volume->chapters()->count() > 0)
		{			
			if (($volume->next_volume()->first() != null) && ($volume->next_volume()->first()->chapters()->count() > 0) && ($volumeNumber > $volume->next_volume()->first()->volume_number))
			{
				$nextVolumeNumber = $volume->next_volume()->first()->volume_number;
				
				return Redirect::back()->withErrors(['volume_number' => "Volume number must be less than $nextVolumeNumber"])->withInput();
			}
			
			else if (($volume->previous_volume()->first() != null) && ($volume->previous_volume()->first()->chapters()->count() > 0) && ($volumeNumber < $volume->previous_volume()->first()->volume_number))
			{
				$previousVolumeNumber = $volume->previous_volume()->first()->volume_number;
				
				return Redirect::back()->withErrors(['volume_number' => "Volume number must be greater than $previousVolumeNumber"])->withInput();
			}
		}
		
		$collection = $volume->collection;
		
		$volume->volume_number = $volumeNumber;
		$volume->name = trim(Input::get('name'));
		
		//Handle uploading cover here
		if ($request->hasFile('image')) 
		{
			//Get posted image
			$file = $request->file('image');
			$image = ImageUploadHelper::UploadImage($file);
			$volume->cover = $image->id;
		}
		else if (Input::has('delete_cover'))
		{
			$volume->cover = null;
		}
		
		$volume->save();
		
		$messages = self::BuildFlashedMessagesVariable(["Successfully updated volume #$volume->volume_number on collection $collection->name."], null, null);
		return redirect()->route('show_collection', ['collection' => $collection])->with("messages", $messages);
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
		
		$collection = $volume->collection;
		$volumeName = $volume->name;
		if ($volumeName == null)
		{
			$volumeName = "";
		}
		
		//Force deleting for now, build out functionality for soft deleting later.
		$volume->forceDelete();
		
		$messages = self::BuildFlashedMessagesVariable(["Successfully purged volume $volumeName from the collection."], null, null);
		return redirect()->route('show_collection', ['collection' => $collection])->with("messages", $messages);
    }
	
	/**
     * Export the specified as a zip file.
     *
     * @param  int  $id
     * @return Response
     */
    public function export(Volume $volume)
    {
		//Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize($volume);
		
		$fileExport = FileExportHelper::ExportVolume($volume);
		
		if ($fileExport != null)
		{
			$volumeName = $volume->collection->name . " - Volume " . $volume->volume_number;
			if ($volume->name != null)
			{
				$volumeName = $volumeName . " - " .  $volume->name;
			}
			$volumeName = $volumeName . ".zip";
			
			return response()->download($fileExport->path, $volumeName);
		}
		else
		{
			$messages = self::BuildFlashedMessagesVariable(["Unable to export zipped volume file."], null, null);
			
			//Return an error message saying that it couldn't create a volume export
			return Redirect::back()->with(["messages" => $messages]);
		}
	}
	
	private static function GetConfiguration($collection = null)
	{
		$configurations = Auth::user()->placeholder_configuration()->where('key', 'like', 'volume%')->get();
		
		$cover = $configurations->where('key', '=', Config::get('constants.keys.placeholders.volume.cover'))->first();
		$number = $configurations->where('key', '=', Config::get('constants.keys.placeholders.volume.number'))->first();
		if (($collection != null) && ($collection->volumes->count() > 0))
		{
			$number->value = $collection->volumes()->last->volume_number + 1;
		}
		else
		{
			$number->value = 1;
		}
		$name = $configurations->where('key', '=', Config::get('constants.keys.placeholders.volume.name'))->first();
		
		$configurationsArray = array('cover' => $cover, 'number' => $number, 'name' => $name);
		
		return $configurationsArray;
	}
}

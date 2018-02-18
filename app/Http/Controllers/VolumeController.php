<?php

namespace App\Http\Controllers;

use Auth;
use App\Http\Requests\Volume\StoreVolumeRequest;
use App\Http\Requests\Volume\UpdateVolumeRequest;
use App\Models\Collection;
use App\Models\Image;
use App\Models\Volume;
use Config;
use DB;
use FileExportHelper;
use ImageUploadHelper;
use Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Storage;

class VolumeController extends WebController
{
	public function __construct()
    {
		$this->middleware('auth');
		$this->middleware('permission:Create Volume')->only(['create', 'store']);
		$this->middleware('canInteractWithCollection')->only('create');
		$this->middleware('canInteractWithVolume')->except(['create', 'store']);
		$this->middleware('permission:Edit Volume')->only(['edit', 'update']);
		$this->middleware('permission:Delete Volume')->only('destroy');
		$this->middleware('permission:Export Volume')->only('export');
	}
	
    public function create(Request $request, Collection $collection)
    {
		$this->authorize(Volume::class);
		
		$messages = self::GetFlashedMessages($request);
		$configurations = self::GetConfiguration($collection);
		
        return View('volumes.create', array('configurations' => $configurations, 'collection' => $collection, 'messages' => $messages));
    }

    public function store(StoreVolumeRequest $request)
    {
		$volume = new Volume();
		
		DB::beginTransaction();
		try
		{
			$collectionId = trim(Input::get('collection_id'));		
			$collection = Collection::where('id', '=', $collectionId)->firstOrFail();
			
			$volume->collection_id = $collection->id;
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
		}
		catch (\Exception $e)
		{
			$cover = $volume->cover_image;
			DB::rollBack();
			$coverImage= null;
			if ($cover != null)
			{
				$coverImage = Image::where('id', '=', $volume->cover_image->id)->first();
			}
			
			//Delete the cover image from the file system if the image isn't being used anywhere else
			if (($collection->cover != null) && ($coverImage == null)) 
			{
				Storage::delete($cover->name);
				Storage::delete($cover->thumbnail);
			}
			
			$messages = self::BuildFlashedMessagesVariable(null, null, ["Unable to successfully create volume $volume->name."]);
			return Redirect::back()->with(["messages" => $messages])->withInput();
		}
		DB::commit();
		
		$messages = self::BuildFlashedMessagesVariable(["Successfully created new volume #$volume->volume_number on collection $collection->name."], null, null);
		return redirect()->route('show_collection', ['collection' => $collection])->with("messages", $messages);
    }

    public function edit(Request $request, Volume $volume)
    {
		$this->authorize($volume);
		
        $messages = self::GetFlashedMessages($request);
		$configurations = self::GetConfiguration();
		
        return View('volumes.edit', array('configurations' => $configurations, 'volume' => $volume, 'messages' => $messages));
    }

    public function update(UpdateVolumeRequest $request, Volume $volume)
    {
		$collection = null;
		
		DB::beginTransaction();
		try
		{
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
		}
		catch (\Exception $e)
		{
			$cover = $volume->cover_image;
			DB::rollBack();
			
			$coverImage = Image::where('id', '=', $volume->cover_image->id)->first();
			//Delete the cover image from the file system if the image isn't being used anywhere else
			if (($collection->cover != null) && ($coverImage == null)) 
			{
				Storage::delete($cover->name);
				Storage::delete($cover->thumbnail);
			}
			
			$messages = self::BuildFlashedMessagesVariable(null, null, ["Unable to successfully update volume $volume->name."]);
			return Redirect::back()->with(["messages" => $messages])->withInput();
		}
		DB::commit();
		
		$messages = self::BuildFlashedMessagesVariable(["Successfully updated volume #$volume->volume_number on collection $collection->name."], null, null);
		return redirect()->route('show_collection', ['collection' => $collection])->with("messages", $messages);
    }

    public function destroy(Volume $volume)
    {
		$this->authorize($volume);
		$volumeName = "";
		
		DB::beginTransaction();
		try
		{
			$collection = $volume->collection;
			if ($volumeName != null)
			{
				$volumeName = $volume->name;
			}
			
			//Force deleting for now, build out functionality for soft deleting later.
			$volume->forceDelete();
		}
		catch (\Exception $e)
		{
			DB::rollBack();
			$messages = self::BuildFlashedMessagesVariable(null, null, ["Unable to successfully delete volume $volumeName."]);
			return Redirect::back()->with(["messages" => $messages])->withInput();
		}
		DB::commit();
		
		$messages = self::BuildFlashedMessagesVariable(["Successfully purged volume $volumeName from the collection."], null, null);
		return redirect()->route('show_collection', ['collection' => $collection])->with("messages", $messages);
    }
	
    public function export(Volume $volume)
    {
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
			$number->value = $collection->volumes->last()->volume_number + 1;
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

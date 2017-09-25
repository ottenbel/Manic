<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Auth;
use Config;
use Input;
use MappingHelper;
use ImageUploadHelper;
use InterventionImage;
use FileExportHelper;
use File;
use Storage;
use App\Models\Chapter;
use App\Models\Collection;
use App\Models\Image;
use App\Models\Page;
use App\Models\TagObjects\Scanalator\Scanalator;
use App\Models\TagObjects\Scanalator\ScanalatorAlias;
use App\Models\Volume;

class ChapterController extends WebController
{
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request, Collection $collection)
    {
		//Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize(Chapter::class);
		
        $messages = self::GetFlashedMessages($request);
		$configurations = self::GetConfiguration();
		
		$volumes = $collection->volumes()->orderBy('volume_number', 'asc')->get()->pluck('volume_number', 'id')->map(function($item, $key)
		{
			return "Volume $item";	
		});
		
		$volumes_array = json_encode($collection->volumes()->pluck('id'));
		
		if ($collection->volumes()->count() == 0)
		{
			//If collection doesn't have any associated volumes prompt the user to create a volume before they create a chapter.
			$missing_volume_warning = "Creating a chapter on a collection requires a volume for the chapter to belong to.  Create a volume to associate the chapter to before trying to create a chapter.";
			
			if ($messages['warning'] != null)
			{
				array_push($messages['warning'], $missing_volume_warning);
			}
			else
			{
				$messages['warning'] = array($missing_volume_warning);
			}
			
			return View('volumes.create', array('collection' => $collection, 'volumes_array' => $volumes_array, 'messages' => $messages));
		}
		else
		{
			return View('chapters.create', array('configurations' => $configurations, 'collection' => $collection, 'volumes' => $volumes, 'volumes_array' => $volumes_array, 'messages' => $messages));
		}
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
		//Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize(Chapter::class);
		
		$volume = Volume::where('id', '=', trim(Input::get('volume_id')))->first();
		
		$lower_chapter_limit = 0;
		$upper_chapter_limit = 0;
		
		if (($volume->previous_volume()->first() != null) && ($volume->previous_volume()->first()->last_chapter()->first() != null))
		{
			$lower_chapter_limit = $volume->previous_volume()->first()->last_chapter()->first()->chapter_number;
		}
		if (($volume->next_volume()->first() != null) && ($volume->next_volume()->first()->first_chapter()->first() != null))
		{
			$upper_chapter_limit = $volume->next_volume()->first()->first_chapter()->first()->chapter_number;
		}
		
		if (($lower_chapter_limit != 0) && ($upper_chapter_limit != 0))
		{
			$this->validate($request, [
			'volume_id' => 'required|exists:volumes,id',
			'chapter_number' => ['required',
						'integer',
						"between:$lower_chapter_limit,$upper_chapter_limit",
						Rule::unique('chapters')->where(function ($query){
							$query->where('volume_id', trim(Input::get('volume_id')));})
						],
			'scanalator_primary' => 'regex:/^[^:-]+$/',
			'scanalator_secondary' => 'regex:/^[^:-]+$/',
			'source' => 'URL',
			'images' => 'required',
			'images.*' => 'mimetypes:image/jpeg,image/bmp,image/png,zip']);
		}
		else if ($lower_chapter_limit != 0)
		{
			$lower_chapter_limit = $lower_chapter_limit + 1;
			$this->validate($request, [
			'volume_id' => 'required|exists:volumes,id',
			'chapter_number' => ['required',
						'integer',
						"min:$lower_chapter_limit",
						Rule::unique('chapters')->where(function ($query){
							$query->where('volume_id', trim(Input::get('volume_id')));})
						],
			'scanalator_primary' => 'regex:/^[^:-]+$/',
			'scanalator_secondary' => 'regex:/^[^:-]+$/',
			'source' => 'URL',
			'images' => 'required',
			'images.*' => 'mimes:jpeg,bmp,png,zip']);
		}
		else if ($upper_chapter_limit != 0)
		{
			$upper_chapter_limit = $upper_chapter_limit - 1;
			$this->validate($request, [
			'volume_id' => 'required|exists:volumes,id',
			'chapter_number' => ['required',
						'integer',
						"between:0,$upper_chapter_limit",
						Rule::unique('chapters')->where(function ($query){
							$query->where('volume_id', trim(Input::get('volume_id')));})
						],
			'scanalator_primary' => 'regex:/^[^:-]+$/',
			'scanalator_secondary' => 'regex:/^[^:-]+$/',
			'source' => 'URL',
			'images' => 'required',
			'images.*' => 'mimes:jpeg,bmp,png,zip']);
		}
		else
		{
			$this->validate($request, [
			'volume_id' => 'required|exists:volumes,id',
			'chapter_number' => ['required',
						'integer',
						'min:0',
						Rule::unique('chapters')->where(function ($query){
							$query->where('volume_id', trim(Input::get('volume_id')));})
						],
			'scanalator_primary' => 'regex:/^[^:-]+$/',
			'scanalator_secondary' => 'regex:/^[^:-]+$/',
			'source' => 'URL',
			'images' => 'required',
			'images.*' => 'mimes:jpeg,bmp,png,zip']);
		}
		
		$chapter = new Chapter();
		$chapter->volume_id = $volume->id;
		$chapter->chapter_number = trim(Input::get('chapter_number'));
		$chapter->name = trim(Input::get('name'));
		$chapter->source = trim(Input::get('source'));
		$chapter->save();
		
		//Explode the scanalators arrays to be processed (if commonalities exist force to primary)
		$scanalator_primary_array = array_map('trim', explode(',', Input::get('scanalator_primary')));
		$scanalator_secondary_array = array_diff(array_map('trim', explode(',', Input::get('scanalator_secondary'))), $scanalator_primary_array);
		
		$chapter->scanalators()->detach();
		MappingHelper::MapScanalators($chapter, $scanalator_primary_array, true);
		MappingHelper::MapScanalators($chapter, $scanalator_secondary_array, false);
		
		$page_number = 0;
		foreach(Input::file('images') as $file)
		{
			$fileExtension = File::mimeType($file);
			
			if($fileExtension == "application/zip")
			{
				ImageUploadHelper::UploadZip($chapter, $page_number, $file);
			}
			else
			{
				$image = ImageUploadHelper::UploadImage($file);
				$chapter->pages()->attach($image, ['page_number' => $page_number]);	
				$page_number++;
			}
		}
		
		$collection = $volume->collection;
		$messages = self::BuildFlashedMessagesVariable(["Successfully created new chapter #$chapter->chapter_number on collection $collection->name."], null, null);
		
		return redirect()->route('show_collection', ['collection' => $collection])->with("messages", $messages);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Request $request, Chapter $chapter, int $page = 0)
    {
        $messages = self::GetFlashedMessages($request);
		
		if (is_int($page))
		{
			if ($page < 0)
			{
				$page = 0;
			}
		}
		else
		{
			$page = 0;
		}
		
		$previous_chapter_id;
		$next_chapter_id;
		$last_page_of_previous_chapter;
		
		$collection = $chapter->collection()->first();
	
		$pages_array = json_encode($chapter->pages()->pluck('name'));
		
		if (count($chapter->previous_chapter()->first()))
		{
			$previous_chapter_id = $chapter->previous_chapter()->first()->id;
		}
		else
		{
			$previous_chapter_id = null;
		}
		
		if(count($chapter->next_chapter()->first()))
		{
			$next_chapter_id = $chapter->next_chapter()->first()->id;
		}
		else
		{
			$next_chapter_id = null;
		}
		
		if($previous_chapter_id != null)
		{
			$last_page_of_previous_chapter = count(Chapter::where('id', '=', $previous_chapter_id)->first()->pages) - 1;
		}
		else
		{
			$last_page_of_previous_chapter = null;
		}
		
		return view('chapters.show', array('collection' => $collection, 'chapter' => $chapter, 'page_number' => $page, 'pages_array' => $pages_array, 'previous_chapter_id' => $previous_chapter_id, 'next_chapter_id' => $next_chapter_id, 'last_page_of_previous_chapter' => $last_page_of_previous_chapter, 'messages' => $messages));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $request, Chapter $chapter)
    {
		//Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize($chapter);
		
        $messages = self::GetFlashedMessages($request);
		$configurations = self::GetConfiguration();
		
		$volumes_array = json_encode($chapter->volume->collection->volumes()->pluck('id'));
		
		$volumes = $chapter->volume->collection->volumes()->orderBy('volume_number', 'asc')->get()->pluck('volume_number', 'id')->map(function($item, $key)
		{
			return "Volume $item";
		});
		
        return View('chapters.edit', array('configurations' => $configurations, 'chapter' => $chapter, 'volumes' => $volumes, 'volumes_array' => $volumes_array, 'messages' => $messages));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, Chapter $chapter)
    {
		//Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize($chapter);
		
		$delete_pages_array = Input::get('delete_pages');
		$update_pages_array = Input::get('chapter_pages');	
		
		if(($update_pages_array != null) && (count(array_unique($update_pages_array)) != count($update_pages_array)))
		{
			//Duplicate page number provided throw an error
			return Redirect::back()->withErrors(['page_uniqueness' => 'All page numbers must be unique.'])->withInput();
		}
		
		if (($delete_pages_array != null) && (count($delete_pages_array) == count($update_pages_array)) && (count(Input::file('images')) == 0))
		{
			return Redirect::back()->withErrors(['page_requirement' => 'A chapter must have at least one page associated with it.'])->withInput();
		}
		
        $volume = Volume::where('id', '=', trim(Input::get('volume_id')))->firstOrFail();
		
		$lower_chapter_limit = 0;
		$upper_chapter_limit = 0;
		
		if (($volume->previous_volume()->first() != null) && ($volume->previous_volume()->first()->last_chapter()->first() != null))
		{
			$lower_chapter_limit = $volume->previous_volume()->first()->last_chapter()->first()->chapter_number;
		}
		if (($volume->next_volume()->first() != null) && ($volume->next_volume()->first()->first_chapter()->first() != null))
		{
			$upper_chapter_limit = $volume->next_volume()->first()->first_chapter()->first()->chapter_number;
		}
		
		if (($lower_chapter_limit != 0) && ($upper_chapter_limit != 0))
		{
			$this->validate($request, [
			'volume_id' => 'required|exists:volumes,id',
			'chapter_number' => ['required',
						'integer',
						"between:$lower_chapter_limit,$upper_chapter_limit",
						Rule::unique('chapters')->where(function ($query){
							$query->where('volume_id', trim(Input::get('volume_id')))
								->where('id', '!=', trim(Input::get('chapter_id')));
							})
						],
			'scanalator_primary' => 'regex:/^[^:-]+$/',
			'scanalator_secondary' => 'regex:/^[^:-]+$/',
			'source' => 'URL',
			'images.*' => 'mimes:jpeg,bmp,png,zip',
			'chapter_pages.*' => 'required|integer|min:0',
			'delete_pages.*' => 'boolean']);
		}
		else if ($lower_chapter_limit != 0)
		{
			$lower_chapter_limit = $lower_chapter_limit + 1;
			$this->validate($request, [
			'volume_id' => 'required|exists:volumes,id',
			'chapter_number' => ['required',
						'integer',
						"min:$lower_chapter_limit",
						Rule::unique('chapters')->where(function ($query){
							$query->where('volume_id', trim(Input::get('volume_id')))
								->where('id', '!=', trim(Input::get('chapter_id')));
							})
						],
			'scanalator_primary' => 'regex:/^[^:-]+$/',
			'scanalator_secondary' => 'regex:/^[^:-]+$/',
			'source' => 'URL',
			'images.*' => 'mimes:jpeg,bmp,png,zip',
			'chapter_pages.*' => 'required|integer|min:0',
			'delete_pages.*' => 'boolean']);
		}
		else if ($upper_chapter_limit != 0)
		{
			$upper_chapter_limit = $upper_chapter_limit - 1;
			$this->validate($request, [
			'volume_id' => 'required|exists:volumes,id',
			'chapter_number' => ['required',
						'integer',
						"between:0,$upper_chapter_limit",
						Rule::unique('chapters')->where(function ($query){
							$query->where('volume_id', trim(Input::get('volume_id')))
								->where('id', '!=', trim(Input::get('chapter_id')));
							})
						],
			'scanalator_primary' => 'regex:/^[^:-]+$/',
			'scanalator_secondary' => 'regex:/^[^:-]+$/',
			'source' => 'URL',
			'images.*' => 'mimes:jpeg,bmp,png,zip',
			'chapter_pages.*' => 'required|integer|min:0',
			'delete_pages.*' => 'boolean']);
		}
		else
		{
			$this->validate($request, [
			'volume_id' => 'required|exists:volumes,id',
			'chapter_number' => ['required',
						'integer',
						'min:0',
						Rule::unique('chapters')->where(function ($query){
							$query->where('volume_id', trim(Input::get('volume_id')))
								->where('id', '!=', trim(Input::get('chapter_id')));
							})
						],
			'scanalator_primary' => 'regex:/^[^:-]+$/',
			'scanalator_secondary' => 'regex:/^[^:-]+$/',
			'source' => 'URL',
			'images.*' => 'mimes:jpeg,bmp,png,zip',
			'chapter_pages.*' => 'required|integer|min:0',
			'delete_pages.*' => 'boolean']);
		}
		
		$chapter->volume_id = $volume->id;
		$chapter->chapter_number = trim(Input::get('chapter_number'));
		$chapter->name = trim(Input::get('name'));
		$chapter->source = trim(Input::get('source'));
		$chapter->save();
		
		//Explode the scanalators arrays to be processed (if commonalities exist force to primary)
		$scanalator_primary_array = array_map('trim', explode(',', Input::get('scanalator_primary')));
		$scanalator_secondary_array = array_diff(array_map('trim', explode(',', Input::get('scanalator_secondary'))), $scanalator_primary_array);
		
		$chapter->scanalators()->detach();
		MappingHelper::MapScanalators($chapter, $scanalator_primary_array, true);
		MappingHelper::MapScanalators($chapter, $scanalator_secondary_array, false);
		
		$pages = $chapter->pages;

		//Detach all existing pages
		$chapter->pages()->detach();
		
		$highest_page_number = 0;
		//Update the existing pages depending on user input
		foreach ($pages as $page)
		{
			//If page has not been marked for deletion re-add it to the chapter.
			if (($delete_pages_array == null) || (!(array_key_exists("$page->id", $delete_pages_array))))
			{
				$image = Image::where('id', '=', $page->id)->first();
				$page_number = $update_pages_array["$page->id"];
				
				$chapter->pages()->attach($image, ['page_number' => $page_number]);
				
				if ($page_number > $highest_page_number)
				{
					$highest_page_number = $page_number;
				}
			}
		}
		if (Input::file('images') != null)
		{
			$page_number = $highest_page_number + 1;
			foreach(Input::file('images') as $file)
			{
				$fileExtension = File::mimeType($file);
				
				if($fileExtension == "application/zip")
				{
					ImageUploadHelper::UploadZip($chapter, $page_number, $file);
				}
				else
				{				
					$image = ImageUploadHelper::UploadImage($file);
					$chapter->pages()->attach($image, ['page_number' => $page_number]);	
					$page_number++;
				}
			}
		}
		
		$collection = $volume->collection;
		$messages = self::BuildFlashedMessagesVariable(["Successfully updated chapter #$chapter->chapter_number on collection $collection->name."], null, null);
		
		return redirect()->route('show_collection', ['collection' => $collection])->with("messages", $messages);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Chapter $chapter)
    {
        //Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize($chapter);
		
		$collection = $chapter->collection;
		$chapterName = $chapter->name;
		if ($chapterName == null)
		{
			$chapterName = "";
		}
		
		//Force deleting for now, build out functionality for soft deleting later.
		$chapter->forceDelete();
		$messages = self::BuildFlashedMessagesVariable(["Successfully purged chapter $chapterName from the collection."], null, null);
		
		return redirect()->route('show_collection', ['collection' => $collection])->with("messages", $messages);
    }
	
	/**
     * Export the specified as a zip file.
     *
     * @param  int  $id
     * @return Response
     */
    public function export(Chapter $chapter)
    {
		//Define authorization in the controller as the show route can be viewed by guests. Authorizing the full resource conroller causes problems with that [requires the user to login])
		$this->authorize($chapter);
		
		$fileExport = FileExportHelper::ExportChapter($chapter);
		
		if ($fileExport != null)
		{
			$chapterName = $chapter->collection->name . " - Chapter " . $chapter->chapter_number;
			if ($chapter->name != null)
			{
				$chapterName = $chapterName . " - " .  $chapter->name;
			}
			$chapterName = $chapterName . ".zip";
			
			return response()->download($fileExport->path, $chapterName);
		}
		else
		{
			//Return an error message saying that it couldn't create a chapter export
			$messages = self::BuildFlashedMessagesVariable(null, null, ["Unable to export zipped chapter file."]); 
			
			return Redirect::back()->with(["messages" => $messages]);
		}
	}
	
	private static function GetConfiguration()
	{
		$configurations = Auth::user()->placeholder_configuration()->where('key', 'like', 'chapter%')->get();
		
		$volume = $configurations->where('key', '=', Config::get('constants.keys.placeholders.chapter.volume'))->first();
		$number = $configurations->where('key', '=', Config::get('constants.keys.placeholders.chapter.number'))->first();
		$name = $configurations->where('key', '=', Config::get('constants.keys.placeholders.chapter.name'))->first();
		$scanalatorPrimary = $configurations->where('key', '=', Config::get('constants.keys.placeholders.chapter.scanalatorPrimary'))->first();
		$scanalatorSecondary = $configurations->where('key', '=', Config::get('constants.keys.placeholders.chapter.scanalatorSecondary'))->first();
		$source = $configurations->where('key', '=', Config::get('constants.keys.placeholders.chapter.source'))->first();
		$images = $configurations->where('key', '=', Config::get('constants.keys.placeholders.chapter.images'))->first();
		
		$configurationsArray = array('volume' => $volume, 'number' => $number, 'name' => $name, 'scanalatorPrimary' => $scanalatorPrimary, 'scanalatorSecondary' => $scanalatorSecondary, 'source' => $source, 'images' => $images);
		
		return $configurationsArray;
	}
}
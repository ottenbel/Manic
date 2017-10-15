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
use App\Http\Requests\Chapter\StoreChapterRequest;
use App\Http\Requests\Chapter\UpdateChapterRequest;

class ChapterController extends WebController
{
    public function create(Request $request, Collection $collection)
    {
		$this->authorize(Chapter::class);
		
        $messages = self::GetFlashedMessages($request);
		$configurations = self::GetConfiguration();
		
		$volumes = $collection->volumes()->orderBy('volume_number', 'asc')->get()->pluck('volume_number', 'id')->map(function($item, $key)
		{
			return "Volume $item";	
		});
		
		$volumesArray = json_encode($collection->volumes()->pluck('id'));
		
		if ($collection->volumes()->count() == 0)
		{
			//If collection doesn't have any associated volumes prompt the user to create a volume before they create a chapter.
			$missingVolumeWarning = "Creating a chapter on a collection requires a volume for the chapter to belong to.  Create a volume to associate the chapter to before trying to create a chapter.";
			
			if ($messages['warning'] != null)
			{
				array_push($messages['warning'], $missingVolumeWarning);
			}
			else
			{
				$messages['warning'] = array($missingVolumeWarning);
			}
			
			return View('volumes.create', array('collection' => $collection, 'volumes_array' => $volumesArray, 'messages' => $messages));
		}
		else
		{
			return View('chapters.create', array('configurations' => $configurations, 'collection' => $collection, 'volumes' => $volumes, 'volumes_array' => $volumesArray, 'messages' => $messages));
		}
    }

    public function store(StoreChapterRequest $request)
    {		
		$volume = Volume::where('id', '=', trim(Input::get('volume_id')))->first();	
		
		$chapter = new Chapter();
		$chapter->volume_id = $volume->id;
		$chapter->fill($request->only(['chapter_number', 'name', 'source']));
		$chapter->save();
		
		//Explode the scanalators arrays to be processed (if commonalities exist force to primary)
		$primaryScanalators = array_map('trim', explode(',', Input::get('scanalator_primary')));
		$secondaryScanalators = array_diff(array_map('trim', explode(',', Input::get('scanalator_secondary'))), $primaryScanalators);
		
		$chapter->scanalators()->detach();
		MappingHelper::MapScanalators($chapter, $primaryScanalators, true);
		MappingHelper::MapScanalators($chapter, $secondaryScanalators, false);
		
		$pageNumber = 0;
		foreach(Input::file('images') as $file)
		{
			$fileExtension = File::mimeType($file);
			
			if($fileExtension == "application/zip")
			{
				ImageUploadHelper::UploadZip($chapter, $pageNumber, $file);
			}
			else
			{
				$image = ImageUploadHelper::UploadImage($file);
				$chapter->pages()->attach($image, ['page_number' => $pageNumber]);	
				$pageNumber++;
			}
		}
		
		$collection = $volume->collection;
		$messages = self::BuildFlashedMessagesVariable(["Successfully created new chapter #$chapter->chapter_number on collection $collection->name."], null, null);
		
		return redirect()->route('show_collection', ['collection' => $collection])->with("messages", $messages);
    }

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
		
		$previousChapterID;
		$nextChapterID;
		$lastPageOfPreviousChapter;
		
		$collection = $chapter->collection()->first();
	
		$pagesArray = json_encode($chapter->pages()->pluck('name'));
		
		if (count($chapter->previous_chapter()->first()))
		{
			$previousChapterID = $chapter->previous_chapter()->first()->id;
		}
		else
		{
			$previousChapterID = null;
		}
		
		if(count($chapter->next_chapter()->first()))
		{
			$nextChapterID = $chapter->next_chapter()->first()->id;
		}
		else
		{
			$nextChapterID = null;
		}
		
		if($previousChapterID != null)
		{
			$lastPageOfPreviousChapter = count(Chapter::where('id', '=', $previousChapterID)->first()->pages) - 1;
		}
		else
		{
			$lastPageOfPreviousChapter = null;
		}
		
		return view('chapters.show', array('collection' => $collection, 'chapter' => $chapter, 'page_number' => $page, 'pages_array' => $pagesArray, 'previous_chapter_id' => $previousChapterID, 'next_chapter_id' => $nextChapterID, 'last_page_of_previous_chapter' => $lastPageOfPreviousChapter, 'messages' => $messages));
    }

    public function edit(Request $request, Chapter $chapter)
    {
		$this->authorize($chapter);
		
        $messages = self::GetFlashedMessages($request);
		$configurations = self::GetConfiguration();
		
		$volumesArray = json_encode($chapter->volume->collection->volumes()->pluck('id'));
		
		$volumes = $chapter->volume->collection->volumes()->orderBy('volume_number', 'asc')->get()->pluck('volume_number', 'id')->map(function($item, $key)
		{
			return "Volume $item";
		});
		
        return View('chapters.edit', array('configurations' => $configurations, 'chapter' => $chapter, 'volumes' => $volumes, 'volumes_array' => $volumesArray, 'messages' => $messages));
    }

    
    public function update(UpdateChapterRequest $request, Chapter $chapter)
    {
		
		$deletePagesArray = Input::get('delete_pages');
		$updatePagesArray = Input::get('chapter_pages');	
		
		if(($updatePagesArray != null) && (count(array_unique($updatePagesArray)) != count($updatePagesArray)))
		{
			//Duplicate page number provided throw an error
			return Redirect::back()->withErrors(['page_uniqueness' => 'All page numbers must be unique.'])->withInput();
		}
		
		if (($deletePagesArray != null) && (count($deletePagesArray) == count($updatePagesArray)) && (count(Input::file('images')) == 0))
		{
			return Redirect::back()->withErrors(['page_requirement' => 'A chapter must have at least one page associated with it.'])->withInput();
		}
		
        $volume = Volume::where('id', '=', trim(Input::get('volume_id')))->firstOrFail();
		$chapter->volume_id = $volume->id;
		$chapter->fill($request->only(['chapter_number', 'name', 'source']));
		$chapter->save();
		
		//Explode the scanalators arrays to be processed (if commonalities exist force to primary)
		$primaryScanalators = array_map('trim', explode(',', Input::get('scanalator_primary')));
		$secondaryScanalators = array_diff(array_map('trim', explode(',', Input::get('scanalator_secondary'))), $primaryScanalators);
		
		$chapter->scanalators()->detach();
		MappingHelper::MapScanalators($chapter, $primaryScanalators, true);
		MappingHelper::MapScanalators($chapter, $secondaryScanalators, false);
		
		$pages = $chapter->pages;

		//Detach all existing pages
		$chapter->pages()->detach();
		
		$highest_page_number = 0;
		//Update the existing pages depending on user input
		foreach ($pages as $page)
		{
			//If page has not been marked for deletion re-add it to the chapter.
			if (($deletePagesArray == null) || (!(array_key_exists("$page->id", $deletePagesArray))))
			{
				$image = Image::where('id', '=', $page->id)->first();
				$pageNumber = $updatePagesArray["$page->id"];
				
				$chapter->pages()->attach($image, ['page_number' => $pageNumber]);
				
				if ($pageNumber > $highest_page_number)
				{
					$highest_page_number = $pageNumber;
				}
			}
		}
		if (Input::file('images') != null)
		{
			$pageNumber = $highest_page_number + 1;
			foreach(Input::file('images') as $file)
			{
				$fileExtension = File::mimeType($file);
				
				if($fileExtension == "application/zip")
				{
					ImageUploadHelper::UploadZip($chapter, $pageNumber, $file);
				}
				else
				{				
					$image = ImageUploadHelper::UploadImage($file);
					$chapter->pages()->attach($image, ['page_number' => $pageNumber]);	
					$pageNumber++;
				}
			}
		}
		
		$collection = $volume->collection;
		$messages = self::BuildFlashedMessagesVariable(["Successfully updated chapter #$chapter->chapter_number on collection $collection->name."], null, null);
		
		return redirect()->route('show_collection', ['collection' => $collection])->with("messages", $messages);
    }

    public function destroy(Chapter $chapter)
    {
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
	
    public function export(Chapter $chapter)
    {
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
		
		$keys = ['volume', 'number', 'name', 'scanalatorPrimary', 'scanalatorSecondary', 'source', 'images'];
		$configurationsArray = [];
		
		foreach ($keys as $key)
		{
			$value = $configurations->where('key', '=', Config::get('constants.keys.placeholders.chapter.'.$key))->first();
			$configurationsArray = array_merge($configurationsArray, [$key => $value]);
		}
		
		return $configurationsArray;
	}
}
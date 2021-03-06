<?php

namespace App\Http\Controllers;

use App\Models\Chapter\Chapter;
use App\Models\Collection\Collection;
use App\Models\Image;
use App\Models\Volume\Volume;
use App\Http\Requests\Chapter\StoreChapterRequest;
use App\Http\Requests\Chapter\UpdateChapterRequest;
use Auth;
use Config;
use DB;
use File;
use FileExportHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Input;
use ImageUploadHelper;
use LookupHelper;
use MappingHelper;
use Storage;

class ChapterController extends WebController
{
	public function __construct()
    {
		parent::__construct();
		
		$this->placeholderStub = "chapter";
		$this->placeheldFields = array('volume', 'number', 'name', 'primary_scanalators', 'secondary_scanalators', 'source', 'images');
		
		$this->middleware('auth')->except(['show', 'overview']);
		$this->middleware('permission:Create Chapter')->only(['create', 'store']);
		$this->middleware('canInteractWithCollection')->only('create');
		$this->middleware('canInteractWithChapter')->except(['create', 'store']);
		$this->middleware('permission:Edit Chapter')->only(['edit', 'update']);
		$this->middleware('permission:Delete Chapter')->only('destroy');
		$this->middleware('permission:Export Chapter')->only('export');
	}
	
    public function create(Request $request, Collection $collection)
    {
		$this->authorize(Chapter::class);
		
        $this->GetFlashedMessages($request);
		$configurations = $this->GetConfiguration();
		
		$collection->load([
		'volumes' => function ($query)
			{ $query->orderBy('volume_number', 'asc'); },
		'volumes.chapters' => function ($query)
			{ $query->orderBy('chapter_number', 'asc'); },
		'volumes.chapters.primary_scanalators' => function ($query)
			{ $query->withCount('chapters')->orderBy('chapters_count', 'desc')->orderBy('name', 'asc'); },
		'volumes.chapters.secondary_scanalators' => function ($query)
			{ $query->withCount('chapters')->orderBy('chapters_count', 'desc')->orderBy('name', 'asc'); }, 
		]);
		
		$volumes = $collection->volumes->pluck('volume_number', 'id')->map(function($item, $key)
		{
			return "Volume $item";	
		});
		
		$volumesArray = json_encode($collection->volumes->pluck('id'));
		
		$highestVolume = null;
		$newChapter = 1;
		
		if ($collection->volumes->count() == 0)
		{
			$this->AddDataMessage("Creating a chapter on a collection requires a volume for the chapter to belong to.");
			return Redirect()->route('create_volume', ['collection' => $collection])->with(['messages' => $this->messages]);
		}
		else
		{
			$highestVolume = $collection->unsorted_volumes()->orderby('volume_number', 'desc')->first()->id;
			if ($collection->chapters()->count() > 0)
			{
				$newChapter = $collection->chapters()->orderby('chapter_number', 'desc')->first()->chapter_number + 1;
			}
			
			return View('chapters.create', array('configurations' => $configurations, 'collection' => $collection, 'volumes' => $volumes, 'volumes_array' => $volumesArray, 'highestVolume' => $highestVolume, 'newChapter' => $newChapter, 'messages' => $this->messages));
		}
    }

    public function store(StoreChapterRequest $request)
    {
		$chapter = null;
		$collection = null;
		
		DB::beginTransaction();
		try
		{
			$volume = Volume::where('id', '=', trim(Input::get('volume_id')))->first();	
		
			$chapter = new Chapter();
			$chapter->volume_id = $volume->id;
			$chapter->fill($request->only(['chapter_number', 'name', 'source']));
			$chapter->save();
			
			//Explode the scanalators arrays to be processed (if commonalities exist force to primary)
			$primaryScanalators = array_map('LookupHelper::GetScanalatorName', array_map('trim', explode(',', Input::get('scanalator_primary'))));
			$secondaryScanalators = array_diff(array_map('LookupHelper::GetScanalatorName', array_map('trim', explode(',', Input::get('scanalator_secondary')))), $primaryScanalators);
			
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
		}
		catch (\Exception $e)
		{
			$pages = $chapter->pages()->get();
			DB::rollBack();
			
			foreach ($pages as $page)
			{
				$pageImage = Image::where('id', '=', $page->id)->first();
				
				if (($page != null) && ($pageImage == null)) 
				{
					Storage::delete($page->name);
					Storage::delete($page->thumbnail);
				}
			}
			
			$this->AddWarningMessage("Unable to successfully create chapter $chapter->name.", ['collection' => $collection->id, 'error' => $e]);
			return Redirect::back()->with(["messages" => $this->messages])->withInput();
		}
		DB::commit();
		
		$collection = $volume->collection;
		$this->AddSuccessMessage("Successfully created new chapter #$chapter->chapter_number on collection $collection->name.");
		return redirect()->route('show_collection', ['collection' => $collection])->with("messages", $this->messages);
    }

    public function show(Request $request, Chapter $chapter, int $page = 0)
    {
        $this->GetFlashedMessages($request);
		
		if (is_int($page))
		{
			if ($page < 0) { $page = 0; }
		}
		else
			{ $page = 0; }
		
		$previousChapterID = $nextChapterID = $lastPageOfPreviousChapter = null;
		
		$collection = $chapter->collection()->first();
	
		$pagesArray = json_encode($chapter->pages()->pluck('name'));
		
		if ($chapter->previous_chapter() != null)
		{
			$previousChapterID = $chapter->previous_chapter()->id;
		}
		
		if($chapter->next_chapter() != null)
		{
			$nextChapterID = $chapter->next_chapter()->id;
		}
		
		if($previousChapterID != null)
		{
			$lastPageOfPreviousChapter = count(Chapter::where('id', '=', $previousChapterID)->first()->pages) - 1;
		}
		
		$isFavourite = false;
		if (Auth::check())
		{
			$favouriteCollection = Auth::user()->favourite_collections()->where('collection_id', '=', $collection->id)->first();
			if ($favouriteCollection != null)
			{
				$isFavourite = true;
			}
		}
		
		return view('chapters.show', array('collection' => $collection, 'chapter' => $chapter, 'page_number' => $page, 'pages_array' => $pagesArray, 'previous_chapter_id' => $previousChapterID, 'next_chapter_id' => $nextChapterID, 'last_page_of_previous_chapter' => $lastPageOfPreviousChapter, 'isFavourite' =>$isFavourite, 'messages' => $this->messages));
    }
	
	public function overview(Request $request, Chapter $chapter)
	{
		$this->GetFlashedMessages($request);
		$previousChapterID = $nextChapterID = null;
		$collection = $chapter->collection()->first();
		
		if ($chapter->previous_chapter() != null)
		{
			$previousChapterID = $chapter->previous_chapter()->id;
		}
		
		if($chapter->next_chapter() != null)
		{
			$nextChapterID = $chapter->next_chapter()->id;
		}
		
		$isFavourite = false;
		if (Auth::check())
		{
			$favouriteCollection = Auth::user()->favourite_collections()->where('collection_id', '=', $collection->id)->first();
			if ($favouriteCollection != null)
			{
				$isFavourite = true;
			}
		}
		
		return view('chapters.overview', array('collection' => $collection, 'chapter' => $chapter, 'previous_chapter_id' => $previousChapterID, 'next_chapter_id' => $nextChapterID, 'isFavourite' =>$isFavourite, 'messages' => $this->messages));
	}

    public function edit(Request $request, Chapter $chapter)
    {
		$this->authorize($chapter);
		
        $this->GetFlashedMessages($request);
		$configurations = $this->GetConfiguration();
		
		$collection = $chapter->collection;
		$collection->load([
		'volumes' => function ($query)
			{ $query->orderBy('volume_number', 'asc'); },
		'volumes.chapters' => function ($query)
			{ $query->orderBy('chapter_number', 'asc'); },
		'volumes.chapters.primary_scanalators' => function ($query)
			{ $query->withCount('chapters')->orderBy('chapters_count', 'desc')->orderBy('name', 'asc'); },
		'volumes.chapters.secondary_scanalators' => function ($query)
			{ $query->withCount('chapters')->orderBy('chapters_count', 'desc')->orderBy('name', 'asc'); }
		]);
		
		$volumesArray = json_encode($collection->volumes->pluck('id'));
		
		$volumes = $collection->volumes->pluck('volume_number', 'id')->map(function($item, $key)
		{
			return "Volume $item";
		});
		
		$isFavourite = false;
		if (Auth::check())
		{
			$favouriteCollection = Auth::user()->favourite_collections()->where('collection_id', '=', $chapter->collection->id)->first();
			if ($favouriteCollection != null)
			{
				$isFavourite = true;
			}
		}
		
        return View('chapters.edit', array('configurations' => $configurations, 'chapter' => $chapter, 'volumes' => $volumes, 'volumes_array' => $volumesArray, 'isFavourite' =>$isFavourite, 'highestVolume' => null, 'newChapter' => 1, 'messages' => $this->messages));
    }

    
    public function update(UpdateChapterRequest $request, Chapter $chapter)
    {
		$collection = null;
		
		DB::beginTransaction();
		try
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
			$primaryScanalators = array_map('LookupHelper::GetScanalatorName', array_map('trim', explode(',', Input::get('scanalator_primary'))));
			$secondaryScanalators = array_diff(array_map('LookupHelper::GetScanalatorName', array_map('trim', explode(',', Input::get('scanalator_secondary')))), $primaryScanalators);
			
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
		}
		catch (\Exception $e)
		{
			$pages = $chapter->pages()->get();
			DB::rollBack();
			
			foreach ($pages as $page)
			{
				$pageImage = Image::where('id', '=', $page->id)->first();
				
				if (($page != null) && ($pageImage == null)) 
				{
					Storage::delete($page->name);
					Storage::delete($page->thumbnail);
				}
			}
			
			$this->AddWarningMessage("Unable to successfully update chapter $chapter->name.", ['chapter' => $chapter->id, 'error' => $e]);
			return Redirect::back()->with(["messages" => $this->messages])->withInput();
		}
		DB::commit();
		
		$this->AddSuccessMessage("Successfully updated chapter #$chapter->chapter_number on collection $collection->name.");
		return redirect()->route('show_collection', ['collection' => $collection])->with("messages", $this->messages);
    }

    public function destroy(Chapter $chapter)
    {
		$this->authorize($chapter);
		
		$collection = null;
		$chapterName = "";
		
		DB::beginTransaction();
		try
		{
			$collection = $chapter->collection;
			if ($chapter->name = null)
			{
				$chapterName = $chapter->name;
			}
			
			//Force deleting for now, build out functionality for soft deleting later.
			$chapter->forceDelete();
		}
		catch (\Exception $e)
		{
			DB::rollBack();
			$this->AddWarningMessage("Unable to successfully delete chapter $chapterName.", ['chapter' => $chapter->id, 'error' => $e]);
			return Redirect::back()->with(["messages" => $this->messages])->withInput();
		}
		DB::commit();
		
		$this->AddSuccessMessage("Successfully purged chapter $chapterName from the collection.");
		return redirect()->route('show_collection', ['collection' => $collection])->with("messages", $this->messages);
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
			$this->AddWarningMessage("Unable to export zipped chapter file.", ['chapter' => $chapter->id]);
			return Redirect::back()->with(["messages" => $this->messages]);
		}
	}
}
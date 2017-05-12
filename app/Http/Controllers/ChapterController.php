<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Auth;
use Input;
use MappingHelper;
use ImageUploadHelper;
use InterventionImage;
use App\Models\Chapter;
use App\Models\Collection;
use App\Models\Image;
use App\Models\Page;
use App\Models\TagObjects\Scanalator\Scanalator;
use App\Models\TagObjects\Scanalator\ScanalatorAlias;
use App\Models\Volume;

class ChapterController extends Controller
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
		
        $flashed_success = $request->session()->get('flashed_success');
		$flashed_data = $request->session()->get('flashed_data');
		$flashed_warning = $request->session()->get('flashed_warning');
		
		$volumes = $collection->volumes()->orderBy('volume_number', 'asc')->get()->pluck('volume_number', 'id')->map(function($item, $key)
		{
			return "Volume $item";	
		});
		
		$volumes_array = json_encode($collection->volumes()->pluck('id'));
		
		if ($collection->volumes()->count() == 0)
		{
			//If collection doesn't have any associated volumes prompt the user to create a volume before they create a chapter.
			$missing_volume_warning = "Creating a chapter on a collection requires a volume for the chapter to belong to.  Create a volume to associate the chapter to before trying to create a chapter.";
			
			if ($flashed_warning != null)
			{
				array_push($flashed_warning, $missing_volume_warning);
			}
			else
			{
				$flashed_warning = array($missing_volume_warning);
			}
			
			return View('volumes.create', array('collection' => $collection, 'volumes_array' => $volumes_array,  'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
		}
		else
		{
			return View('chapters.create', array('collection' => $collection, 'volumes' => $volumes, 'volumes_array' => $volumes_array, 'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
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
			'source' => 'URL',
			'images' => 'required',
			'images.*' => 'image']);
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
			'source' => 'URL',
			'images' => 'required',
			'images.*' => 'image']);
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
			'source' => 'URL',
			'images' => 'required',
			'images.*' => 'image']);
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
			'source' => 'URL',
			'images' => 'required',
			'images.*' => 'image']);
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
			$image = ImageUploadHelper::UploadImage($file);
			$chapter->pages()->attach($image, ['page_number' => $page_number]);	
			$page_number++;
		}
		
		$collection = $volume->collection;
		
		return redirect()->route('show_collection', ['collection' => $collection])->with("flashed_success", array("Successfully created new chapter #$chapter->chapter_number on collection $collection->name."));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Request $request, Chapter $chapter, int $page = 0)
    {
        $flashed_success = $request->session()->get('flashed_success');
		$flashed_data = $request->session()->get('flashed_data');
		$flashed_warning = $request->session()->get('flashed_warning');
		
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
		
		return view('chapters.show', array('collection' => $collection, 'chapter' => $chapter, 'page_number' => $page, 'pages_array' => $pages_array, 'previous_chapter_id' => $previous_chapter_id, 'next_chapter_id' => $next_chapter_id, 'last_page_of_previous_chapter' => $last_page_of_previous_chapter, 'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
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
		
        $flashed_success = $request->session()->get('flashed_success');
		$flashed_data = $request->session()->get('flashed_data');
		$flashed_warning = $request->session()->get('flashed_warning');
		
		$volumes_array = json_encode($chapter->volume->collection->volumes()->pluck('id'));
		
		$volumes = $chapter->volume->collection->volumes()->orderBy('volume_number', 'asc')->get()->pluck('volume_number', 'id')->map(function($item, $key)
		{
			return "Volume $item";
		});
		
        return View('chapters.edit', array('chapter' => $chapter, 'volumes' => $volumes, 'volumes_array' => $volumes_array, 'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
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
			'source' => 'URL',
			'images.*' => 'image',
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
			'source' => 'URL',
			'images.*' => 'image',
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
			'source' => 'URL',
			'images.*' => 'image',
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
			'source' => 'URL',
			'images.*' => 'image',
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
				$image = ImageUploadHelper::UploadImage($file);
				$chapter->pages()->attach($image, ['page_number' => $page_number]);	
				$page_number++;
			}
		}
		
		$collection = $volume->collection;
		
		return redirect()->route('show_collection', ['collection' => $collection])->with("flashed_success", array("Successfully updated  chapter #$chapter->chapter_number on collection $collection->name."));
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
		
		return redirect()->route('show_collection', ['collection' => $collection])->with("flashed_success", array("Successfully purged chapter $chapterName from the collection."));
    }
}
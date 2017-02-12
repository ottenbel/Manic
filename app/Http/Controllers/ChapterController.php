<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Auth;
use Input;
use App\Chapter;
use App\Collection;
use App\Image;
use App\Page;
use App\Scanalator;
use App\Volume;

class ChapterController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request, Collection $collection)
    {
        $flashed_data = $request->session()->get('flashed_data');
		$volumes = $collection->volumes()->orderBy('volume_number', 'asc')->get()->pluck('volume_number', 'id')->map(function($item, $key)
		{
			return "Volume $item";
		});
		
        return View('chapters.create', array('collection' => $collection, 'volumes' => $volumes, 'flashed_data' => $flashed_data));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
		$volume = Volume::where('id', '=', trim(Input::get('volume_id')))->first();
		
		$lower_chapter_limit = 0;
		$upper_chapter_limit = 0;
		
		if (count($volume) && count($volume->previous_volume()->first()) && count($volume->previous_volume()->first()->last_chapter()))
		{
			$lower_chapter_limit = $volume->previous_volume()->first()->last_chapter()->first()->chapter_number;
		}
		if (count($volume) && count($volume->next_volume()->first()) && count($volume->next_volume()->first()->first_chapter()))
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
		$chapter->created_by = Auth::user()->id;
		$chapter->updated_by = Auth::user()->id;
		
		$chapter->save();
		
		//Explode the scanalators arrays to be processed (if commonalities exist force to primary)
		$scanalator_primary_array = array_map('trim', explode(',', Input::get('scanalator_primary')));
		$scanalator_secondary_array = array_diff(array_map('trim', explode(',', Input::get('scanalator_secondary'))), $scanalator_primary_array);
		
		$chapter->scanalators()->detach();
		$this->map_scanalators($chapter, $scanalator_primary_array, true);
		$this->map_scanalators($chapter, $scanalator_secondary_array, false);
		
		$page_number = 0;
		foreach(Input::file('images') as $file)
		{
			//Calculate file hash
			$hash = hash_file("sha256", $file->getPathName());
			
			//Does the image already exist?
			$image = Image::where('hash', '=', $hash)->first();
			if (!count($image))
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
			}
			
			$chapter->pages()->attach($image, ['page_number' => $page_number]);
			
			$page_number++;
		}
		
		$collection = $volume->collection;
		
		$volume->updated_by = Auth::user()->id;
		$volume->save();
		
		$collection->updated_by = Auth::user()->id;
		$collection->save();
		
		return redirect()->action('CollectionController@show', [$collection])->with("flashed_data", "Successfully created new chapter #$chapter->chapter_number on collection $collection->name.");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Request $request, Chapter $chapter, int $page = 0)
    {
        $flashed_data = $request->session()->get('flashed_data');
		
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
		
		return view('chapters.show', array('collection' => $collection, 'chapter' => $chapter, 'page_number' => $page, 'pages_array' => $pages_array, 'previous_chapter_id' => $previous_chapter_id, 'next_chapter_id' => $next_chapter_id, 'last_page_of_previous_chapter' => $last_page_of_previous_chapter, 'flashed_data' => $flashed_data));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $request, Chapter $chapter)
    {
        $flashed_data = $request->session()->get('flashed_data');
		$volumes = $chapter->volume->collection->volumes()->orderBy('volume_number', 'asc')->get()->pluck('volume_number', 'id')->map(function($item, $key)
		{
			return "Volume $item";
		});
		
        return View('chapters.edit', array('chapter' => $chapter, 'volumes' => $volumes, 'flashed_data' => $flashed_data));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, Chapter $chapter)
    {
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
		
        $volume = Volume::where('id', '=', trim(Input::get('volume_id')))->first();
		
		$lower_chapter_limit = 0;
		$upper_chapter_limit = 0;
		
		if (count($volume) && count($volume->previous_volume()->first()) && count($volume->previous_volume()->first()->last_chapter()))
		{
			$lower_chapter_limit = $volume->previous_volume()->first()->last_chapter()->first()->chapter_number;
		}
		if (count($volume) && count($volume->next_volume()->first()) && count($volume->next_volume()->first()->first_chapter()))
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
		$chapter->created_by = Auth::user()->id;
		$chapter->updated_by = Auth::user()->id;
		
		$chapter->save();
		
		//Explode the scanalators arrays to be processed (if commonalities exist force to primary)
		$scanalator_primary_array = array_map('trim', explode(',', Input::get('scanalator_primary')));
		$scanalator_secondary_array = array_diff(array_map('trim', explode(',', Input::get('scanalator_secondary'))), $scanalator_primary_array);
		
		$chapter->scanalators()->detach();
		$this->map_scanalators($chapter, $scanalator_primary_array, true);
		$this->map_scanalators($chapter, $scanalator_secondary_array, false);
		
		$pages = $chapter->pages;

		//Detach all existing pages
		$chapter->pages()->detach();
		
		$highest_page_number = 0;
		//Update the existing pages depending on user input
		foreach ($pages as $page)
		{
			//If page has not been marked for deletion re-add it to the chapter.
			if (array_key_exists("$page->id", $delete_pages_array))
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
				//Calculate file hash
				$hash = hash_file("sha256", $file->getPathName());
				
				//Does the image already exist?
				$image = Image::where('hash', '=', $hash)->first();
				if (!count($image))
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
				}
				
				$chapter->pages()->attach($image, ['page_number' => $page_number]);
				
				$page_number++;
			}
		}
		
		$collection = $volume->collection;
		
		$volume->updated_by = Auth::user()->id;
		$volume->save();
		
		$collection->updated_by = Auth::user()->id;
		$collection->save();
		
		return redirect()->action('CollectionController@show', [$collection])->with("flashed_data", "Successfully updated  chapter #$chapter->chapter_number on collection $collection->name.");
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
	
	private function map_scanalators(&$chapter, $scanalator_array, $isPrimary)
	{
		foreach ($scanalator_array as $scanalator_name)
		{
			if (trim($scanalator_name) != "")
			{
				$scanalator = Scanalator::where('name', '=', $scanalator_name)->first();			
				if (count($scanalator))
				{
					$chapter->scanalators()->attach($scanalator, ['primary' => $isPrimary]);
				}
				else
				{
					//Create a new scanalator
					$scanalator = new Scanalator;
					$scanalator->name = $scanalator_name;
					$scanalator->created_by = Auth::user()->id;
					$scanalator->updated_by = Auth::user()->id;
					$scanalator->save();
					
					$chapter->scanalators()->attach($scanalator, ['primary' => $isPrimary]);
				}
			}
		}
	}
}
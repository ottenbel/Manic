<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
		$volumes = $collection->volumes()->orderBy('number', 'asc')->get()->pluck('number', 'id')->map(function($item, $key)
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
        $this->validate($request, [
			'volume_id' => 'required|exists:volumes,id',
			'number' => ['required',
						'integer',
						'min:0',
						Rule::unique('chapters')->where(function ($query){
							$query->where('volume_id', trim(Input::get('volume_id')));})
						],
				'source' => 'URL',
				'images' => 'required',
				'images.*' => 'image']);
		
		$volume = Volume::where('id', '=', trim(Input::get('volume_id')))->first();
		
		$lower_chapter_limit = 0;
		$upper_chapter_limit = 0;
		
		if (count($volume->previous_volume()->last_chapter))
		{
			$lower_chapter_limit = $volume->previous_volume()->last_chapter->number;
		}
		if (count($volume->next_volume()->first_chapter))
		{
			$upper_chapter_limit = $volume->next_volume()->first_chapter->number;
		}
		
		if (($lower_chapter_limit != 0) && ($upper_chapter_limit != 0))
		{
			$this->validate($request, [
				'number' => 'required|integer|between:$lower_chapter_limit,$upper_chapter_limit'
			]);
		}
		else if ($lower_chapter_limit != 0)
		{
			$lower_chapter_limit = $lower_chapter_limit + 1;
			$this->validate($request, [
				'number' => 'required|integer|min:$lower_chapter_limit'
			]);
		}
		else if ($upper_chapter_limit != 0)
		{
			$upper_chapter_limit = $upper_chapter_limit - 1;
			$this->validate($request, [
				'number' => 'required|integer|max:$upper_chapter_limit'
			]);
		}
		
		$chapter = new Chapter();
		$chapter->volume_id = $volume->id;
		$chapter->number = trim(Input::get('number'));
		$chapter->name = trim(Input::get('name'));
		$chapter->source = trim(Input::get('source'));
		$chapter->created_by = Auth::user()->id;
		$chapter->updated_by = Auth::user()->id;
		
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
			
			$this->pages()->attach($image, ['page_number' => $page_number]);
			
			$page_number++;
		}
		
		$collection = $volume->collection;
		
		return redirect()->action('CollectionController@show', [$collection])->with("flashed_data", "Successfully created new chapter #$chapter->number on collection $collection->name.");
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
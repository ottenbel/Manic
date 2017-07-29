<?php

namespace App\Observers;

use App\Models\Chapter;
use Auth;
use Storage;

class ChapterObserver Extends BaseManicModelObserver
{
	/**
     * Listen to the chapter creating event.
     *
     * @param  $chapter
     * @return void
     */
    public function creating($chapter)
    {
        parent::creating($chapter);
			
		$volume = $chapter->volume()->first();
		$volume->updated_by = Auth::user()->id;
		$volume->save();
		$volume->touch();
		
		$collection = $volume->collection()->first();
		$collection->updated_by = Auth::user()->id;
		$collection->save();
		$collection->touch();
    }
	
    /**
     * Listen to the chapter created event.
     *
     * @param  $chapter
     * @return void
     */
    public function created($chapter)
    {
        parent::created($chapter);
    }

	/**
     * Listen to the chapter updating event.
     *
     * @param  $chapter
     * @return void
     */
    public function updating($chapter)
    {
        parent::updating($chapter);
			
		//Delete the relevant file corresponding to the entry from the chapter export table.
		$export = $chapter->export;
		if ($export != null)
		{
			Storage::Delete($export->path);
			$chapter->export->forceDelete();
		}
    }
	
    /**
     * Listen to the chapter updated event.
     *
     * @param  $chapter
     * @return void
     */
    public function updated($chapter)
    {
        parent::updated($chapter);
    }
	
	/**
     * Listen to the chapter saving event.
     *
     * @param  $chapter
     * @return void
     */
    public function saving($chapter)
    {
        parent::saving($chapter);
    }
	
    /**
     * Listen to the chapter saved event.
     *
     * @param  $chapter
     * @return void
     */
    public function saved($chapter)
    {
        parent::saved($chapter);
    }
	
    /**
     * Listen to the chapter deleting event.
     *
     * @param  $chapter
     * @return void
     */
    public function deleting($chapter)
    {
        parent::deleting($chapter);
			
		$volume = $chapter->volume()->first();
		$volume->updated_by = Auth::user()->id;
		$volume->save();
		$volume->touch();
		
		$collection = $volume->collection()->first();
		$collection->updated_by = Auth::user()->id;
		$collection->save();
		$collection->touch();
		
		//Delete the relevant file corresponding to the entry from the chapter export table.
		$export = $chapter->export;
		if ($export != null)
		{
			Storage::Delete($export->path);
		}
    }
	
	/**
     * Listen to the chapter deleted event.
     *
     * @param  $chapter
     * @return void
     */
    public function deleted($chapter)
    {
        parent::deleted($chapter);
    }
	
	/**
     * Listen to the chapter restoring event.
     *
     * @param  $chapter
     * @return void
     */
    public function restoring($chapter)
    {
        parent::restoring($chapter);
    }
	
	/**
     * Listen to the chapter restored event.
     *
     * @param  Chapter  $chapter
     * @return void
     */
    public function restored($chapter)
    {
        parent::restored($chapter);
    }
}
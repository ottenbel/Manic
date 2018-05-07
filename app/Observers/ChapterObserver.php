<?php

namespace App\Observers;

use App\Models\Chapter\Chapter;
use Auth;
use Storage;
use Log;

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

        Log::Debug("Creating chapter", ['chapter' => $chapter->id, 'volume' => $chapter->volume->id, 'collection' => $chapter->collection->id]);

        $volume = $chapter->volume;
        $volume->updated_by = Auth::user()->id;
		$volume->save();
		$volume->touch();
		
        $collection = $volume->collection;
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

        Log::Info("Successfully created chapter", ['chapter' => $chapter->id, 'volume' => $chapter->volume->id, 'collection' => $chapter->collection->id]);
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
		
        Log::Debug("Updating chapter", ['chapter' => $chapter->id, 'volume' => $chapter->volume->id, 'collection' => $chapter->collection->id]);

		//Delete the relevant file corresponding to the entry from the chapter export table.
		$export = $chapter->export;
		if ($export != null)
		{
			Storage::Delete($export->path);
			$export->forceDelete();
		}
		
		$volume = $chapter->volume;
		$volumeExport = $volume->export;
		
		if ($volumeExport != null)
		{
			Storage::Delete($volumeExport->path);
			$volumeExport->forceDelete();
		}
		
		$collection = $chapter->collection;
		$collectionExport = $collection->export;
		
		if ($collectionExport != null)
		{
			Storage::Delete($collectionExport->path);
			$collectionExport->forceDelete();
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

        Log::Info("Updated chapter", ['chapter' => $chapter->id, 'volume' => $chapter->volume->id, 'collection' => $chapter->collection->id]);
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

        Log::Debug("Saving chapter", ['chapter' => $chapter->id, 'volume' => $chapter->volume->id, 'collection' => $chapter->collection->id]);
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

        Log::Debug("Saved chapter", ['chapter' => $chapter->id, 'volume' => $chapter->volume->id, 'collection' => $chapter->collection->id]);
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
		
        Log::Debug("Deleting chapter", ['chapter' => $chapter->id, 'volume' => $chapter->volume->id, 'collection' => $chapter->collection->id]);

		$volume = $chapter->volume;
		$volume->updated_by = Auth::user()->id;
		$volume->save();
		$volume->touch();
		
		$collection = $volume->collection;
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

        Log::Info("Deleted chapter", ['chapter' => $chapter->id, 'volume' => $chapter->volume->id, 'collection' => $chapter->collection->id]);
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

        Log::Debug("Restoring chapter", ['chapter' => $chapter->id, 'volume' => $chapter->volume->id, 'collection' => $chapter->collection->id]);
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

        Log::Info("Restored chapter", ['chapter' => $chapter->id, 'volume' => $chapter->volume->id, 'collection' => $chapter->collection->id]);
    }
}
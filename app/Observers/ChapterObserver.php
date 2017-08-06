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
     * @param  $model
     * @return void
     */
    public function creating($model)
    {
        parent::creating($model);
			
		$volume = $model->volume()->first();
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
     * @param  $model
     * @return void
     */
    public function created($model)
    {
        parent::created($model);
    }

	/**
     * Listen to the chapter updating event.
     *
     * @param  $model
     * @return void
     */
    public function updating($model)
    {
        parent::updating($model);
			
		//Delete the relevant file corresponding to the entry from the chapter export table.
		$export = $model->export;
		if ($export != null)
		{
			Storage::Delete($export->path);
			$export->forceDelete();
		}
		
		$volume = $model->volume()->first();
		$volumeExport = $volume->export;
		
		if ($volumeExport != null)
		{
			Storage::Delete($volumeExport->path);
			$volumeExport->forceDelete();
		}
		
		$collection = $model->collection()->first();
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
     * @param  $model
     * @return void
     */
    public function updated($model)
    {
        parent::updated($model);
    }
	
	/**
     * Listen to the chapter saving event.
     *
     * @param  $model
     * @return void
     */
    public function saving($model)
    {
        parent::saving($model);
    }
	
    /**
     * Listen to the chapter saved event.
     *
     * @param  $model
     * @return void
     */
    public function saved($model)
    {
        parent::saved($model);
    }
	
    /**
     * Listen to the chapter deleting event.
     *
     * @param  $model
     * @return void
     */
    public function deleting($model)
    {
        parent::deleting($model);
			
		$volume = $model->volume()->first();
		$volume->updated_by = Auth::user()->id;
		$volume->save();
		$volume->touch();
		
		$collection = $volume->collection()->first();
		$collection->updated_by = Auth::user()->id;
		$collection->save();
		$collection->touch();
		
		//Delete the relevant file corresponding to the entry from the chapter export table.
		$export = $model->export;
		if ($export != null)
		{
			Storage::Delete($export->path);
		}
    }
	
	/**
     * Listen to the chapter deleted event.
     *
     * @param  $model
     * @return void
     */
    public function deleted($model)
    {
        parent::deleted($model);
    }
	
	/**
     * Listen to the chapter restoring event.
     *
     * @param  $model
     * @return void
     */
    public function restoring($model)
    {
        parent::restoring($model);
    }
	
	/**
     * Listen to the chapter restored event.
     *
     * @param  Chapter  $model
     * @return void
     */
    public function restored($model)
    {
        parent::restored($model);
    }
}
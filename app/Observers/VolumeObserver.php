<?php

namespace App\Observers;

use App\Models\Volume;
use Auth;
use Storage;

class VolumeObserver Extends BaseManicModelObserver
{
	/**
     * Listen to the volume creating event.
     *
     * @param  $model
     * @return void
     */
    public function creating($model)
    {	
		parent::creating($model);
			
		$collection = $model->collection()->first();
		$collection->updated_by = Auth::user()->id;
		$collection->save();
		$collection->touch();
    }
	
    /**
     * Listen to the volume created event.
     *
     * @param  $model
     * @return void
     */
    public function created($model)
    {
        parent::created($model);
    }

	/**
     * Listen to the volume updating event.
     *
     * @param  $model
     * @return void
     */
    public function updating($model)
    {
        parent::updating($model);
			
		//Delete the relevant file corresponding to the entry from the volume export table.
		$export = $model->export;
		if ($export != null)
		{
			Storage::Delete($export->path);
			$model->export->forceDelete();
		}
    }
	
    /**
     * Listen to the volume updated event.
     *
     * @param  $model
     * @return void
     */
    public function updated($model)
    {
        parent::updated($model);
    }
	
	/**
     * Listen to the volume saving event.
     *
     * @param  $model
     * @return void
     */
    public function saving($model)
    {
        parent::saving($model);
    }
	
    /**
     * Listen to the volume saved event.
     *
     * @param  $model
     * @return void
     */
    public function saved($model)
    {
        parent::saved($model);
    }
	
    /**
     * Listen to the volume deleting event.
     *
     * @param  $model
     * @return void
     */
    public function deleting($model)
    {
        parent::deleting($model);
			
		$collection = $model->collection()->first();
		$collection->updated_by = Auth::user()->id;
		$collection->save();
		$collection->touch();
    }
	
	/**
     * Listen to the volume deleted event.
     *
     * @param  $model
     * @return void
     */
    public function deleted($model)
    {
        parent::deleted($model);
    }
	
	/**
     * Listen to the volume restoring event.
     *
     * @param  $model
     * @return void
     */
    public function restoring($model)
    {
        parent::restoring($model);
    }
	
	/**
     * Listen to the volume restored event.
     *
     * @param  volume  $model
     * @return void
     */
    public function restored($model)
    {
        parent::restored($model);
    }
}
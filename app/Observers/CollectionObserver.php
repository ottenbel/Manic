<?php

namespace App\Observers;

use App\Models\Collection;
use Auth;
use Storage;

class CollectionObserver Extends BaseManicModelObserver
{
	/**
     * Listen to the collection creating event.
     *
     * @param  $model
     * @return void
     */
    public function creating($model)
    {	
		parent::creating($model);
    }
	
    /**
     * Listen to the collection created event.
     *
     * @param  $model
     * @return void
     */
    public function created($model)
    {
        parent::created($model);
    }

	/**
     * Listen to the collection updating event.
     *
     * @param  $model
     * @return void
     */
    public function updating($model)
    {
        parent::updating($model);
			
		//Delete the relevant file corresponding to the entry from the collection export table.
		$export = $model->export;
		if ($export != null)
		{
			Storage::Delete($export->path);
			$model->export->forceDelete();
		}
    }
	
    /**
     * Listen to the collection updated event.
     *
     * @param  $model
     * @return void
     */
    public function updated($model)
    {
        parent::updated($model);
    }
	
	/**
     * Listen to the collection saving event.
     *
     * @param  $model
     * @return void
     */
    public function saving($model)
    {
        parent::saving($model);
    }
	
    /**
     * Listen to the collection saved event.
     *
     * @param  $model
     * @return void
     */
    public function saved($model)
    {
        parent::saved($model);
    }
	
    /**
     * Listen to the collection deleting event.
     *
     * @param  $model
     * @return void
     */
    public function deleting($model)
    {
        parent::deleting($model);
    }
	
	/**
     * Listen to the collection deleted event.
     *
     * @param  $model
     * @return void
     */
    public function deleted($model)
    {
        parent::deleted($model);
    }
	
	/**
     * Listen to the collection restoring event.
     *
     * @param  $model
     * @return void
     */
    public function restoring($model)
    {
        parent::restoring($model);
    }
	
	/**
     * Listen to the collection restored event.
     *
     * @param  $model
     * @return void
     */
    public function restored($model)
    {
        parent::restored($model);
    }
}
<?php

namespace App\Observers;

use App\Models\Collection;
use Auth;
use Storage;
use Log;

class CollectionObserver Extends BaseManicModelObserver
{
	/**
     * Listen to the collection creating event.
     *
     * @param  $collection
     * @return void
     */
    public function creating($collection)
    {	
		parent::creating($collection);

        Log::Debug("Creating collection", ['collection' => $collection->id]);
    }
	
    /**
     * Listen to the collection created event.
     *
     * @param  $collection
     * @return void
     */
    public function created($collection)
    {
        parent::created($collection);

        Log::Info("Created collection", ['collection' => $collection->id]);
    }

	/**
     * Listen to the collection updating event.
     *
     * @param  $collection
     * @return void
     */
    public function updating($collection)
    {
        parent::updating($collection);
		
        Log::Debug("Updating collection", ['collection' => $collection->id]);

		//Delete the relevant file corresponding to the entry from the collection export table.
		$export = $collection->export;
		if ($export != null)
		{
			Storage::Delete($export->path);
			$collection->export->forceDelete();
		}
    }
	
    /**
     * Listen to the collection updated event.
     *
     * @param  $collection
     * @return void
     */
    public function updated($collection)
    {
        parent::updated($collection);

        Log::Info("Updated collection", ['collection' => $collection->id]);
    }
	
	/**
     * Listen to the collection saving event.
     *
     * @param  $collection
     * @return void
     */
    public function saving($collection)
    {
        parent::saving($collection);

        Log::Debug("Saving collection", ['collection' => $collection->id]);
    }
	
    /**
     * Listen to the collection saved event.
     *
     * @param  $collection
     * @return void
     */
    public function saved($collection)
    {
        parent::saved($collection);

        Log::Debug("Saved collection", ['collection' => $collection->id]);
    }
	
    /**
     * Listen to the collection deleting event.
     *
     * @param  $collection
     * @return void
     */
    public function deleting($collection)
    {
        parent::deleting($collection);

        Log::Debug("Deleting collection", ['collection' => $collection->id]);
    }
	
	/**
     * Listen to the collection deleted event.
     *
     * @param  $collection
     * @return void
     */
    public function deleted($collection)
    {
        parent::deleted($collection);

        Log::Info("Deleted collection", ['collection' => $collection->id]);
    }
	
	/**
     * Listen to the collection restoring event.
     *
     * @param  $collection
     * @return void
     */
    public function restoring($collection)
    {
        parent::restoring($collection);

        Log::Debug("Restoring collection", ['collection' => $collection->id]);
    }
	
	/**
     * Listen to the collection restored event.
     *
     * @param  $collection
     * @return void
     */
    public function restored($collection)
    {
        parent::restored($collection);

        Log::Info("Restored collection", ['collection' => $collection->id]);
    }
}
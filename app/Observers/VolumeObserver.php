<?php

namespace App\Observers;

use App\Models\Volume;
use Auth;
use Storage;
use Log;

class VolumeObserver Extends BaseManicModelObserver
{
	/**
     * Listen to the volume creating event.
     *
     * @param  $volume
     * @return void
     */
    public function creating($volume)
    {	
		parent::creating($volume);
			
		$collection = $volume->collection;

        Log::Debug("Creating volume", ['Volume' => $volume->id, 'collection' => $collection->id]);        

		$collection->updated_by = Auth::user()->id;
		$collection->save();
		$collection->touch();
    }
	
    /**
     * Listen to the volume created event.
     *
     * @param  $volume
     * @return void
     */
    public function created($volume)
    {
        parent::created($volume);

        Log::Info("Created volume", ['Volume' => $volume->id]);
    }

	/**
     * Listen to the volume updating event.
     *
     * @param  $volume
     * @return void
     */
    public function updating($volume)
    {
        parent::updating($volume);
		
        Log::Debug("Updating volume", ['Volume' => $volume->id]);

		//Delete the relevant file corresponding to the entry from the volume export table.
		$export = $volume->export;
		if ($export != null)
		{
			Storage::Delete($export->path);
			$volume->export->forceDelete();
		}
		
		$collection = $volume->collection;
		$collectionExport = $collection->export;
		
		if ($collectionExport != null)
		{
			Storage::Delete($collectionExport->path);
			$collectionExport->forceDelete();
		}
    }
	
    /**
     * Listen to the volume updated event.
     *
     * @param  $volume
     * @return void
     */
    public function updated($volume)
    {
        parent::updated($volume);

        Log::Info("Updated volume", ['Volume' => $volume->id]);
    }
	
	/**
     * Listen to the volume saving event.
     *
     * @param  $volume
     * @return void
     */
    public function saving($volume)
    {
        parent::saving($volume);

        Log::Debug("Saving volume", ['Volume' => $volume->id]);
    }
	
    /**
     * Listen to the volume saved event.
     *
     * @param  $volume
     * @return void
     */
    public function saved($volume)
    {
        parent::saved($volume);

        Log::Info("Saved volume", ['Volume' => $volume->id]);
    }
	
    /**
     * Listen to the volume deleting event.
     *
     * @param  $volume
     * @return void
     */
    public function deleting($volume)
    {
        parent::deleting($volume);
		
        Log::Debug("Deleting volume", ['Volume' => $volume->id]);

		$collection = $volume->collection;
		$collection->updated_by = Auth::user()->id;
		$collection->save();
		$collection->touch();
    }
	
	/**
     * Listen to the volume deleted event.
     *
     * @param  $volume
     * @return void
     */
    public function deleted($volume)
    {
        parent::deleted($volume);

        Log::Info("Deleted volume", ['Volume' => $volume->id]);
    }
	
	/**
     * Listen to the volume restoring event.
     *
     * @param  $volume
     * @return void
     */
    public function restoring($volume)
    {
        parent::restoring($volume);

        Log::Debug("Restoring volume", ['Volume' => $volume->id]);
    }
	
	/**
     * Listen to the volume restored event.
     *
     * @param  volume  $volume
     * @return void
     */
    public function restored($volume)
    {
        parent::restored($volume);

        Log::Info("Restored volume", ['Volume' => $volume->id]);
    }
}
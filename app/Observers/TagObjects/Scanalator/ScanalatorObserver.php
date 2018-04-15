<?php

namespace App\Observers\TagObjects\Scanalator;

use App\Models\TagObjects\Scanalator\Scanalator;
use App\Observers\BaseManicModelObserver;
use Auth;
use Log;

class ScanalatorObserver Extends BaseManicModelObserver
{
	/**
     * Listen to the scanalator creating event.
     *
     * @param  $scanalator
     * @return void
     */
    public function creating($scanalator)
    {	
		parent::creating($scanalator);

        Log::Debug("Creating scanalator", ['scanalator' => $scanalator->id]);
    }
	
    /**
     * Listen to the scanalator created event.
     *
     * @param  $scanalator
     * @return void
     */
    public function created($scanalator)
    {
        parent::created($scanalator);

        Log::Info("Created scanalator", ['scanalator' => $scanalator->id]);
    }

	/**
     * Listen to the scanalator updating event.
     *
     * @param  $scanalator
     * @return void
     */
    public function updating($scanalator)
    {
        parent::updating($scanalator);

        Log::Debug("Updating scanalator", ['scanalator' => $scanalator->id]);
    }
	
    /**
     * Listen to the scanalator updated event.
     *
     * @param  $scanalator
     * @return void
     */
    public function updated($scanalator)
    {
        parent::updated($scanalator);

        Log::Info("Updated scanalator", ['scanalator' => $scanalator->id]);
    }
	
	/**
     * Listen to the scanalator saving event.
     *
     * @param  $scanalator
     * @return void
     */
    public function saving($scanalator)
    {
        parent::saving($scanalator);

        Log::Debug("Saving scanalator", ['scanalator' => $scanalator->id]);
    }
	
    /**
     * Listen to the scanalator saved event.
     *
     * @param  $scanalator
     * @return void
     */
    public function saved($scanalator)
    {
        parent::saved($scanalator);

        Log::Debug("Saved scanalator", ['scanalator' => $scanalator->id]);
    }
	
    /**
     * Listen to the scanalator deleting event.
     *
     * @param  $scanalator
     * @return void
     */
    public function deleting($scanalator)
    {
        parent::deleting($scanalator);

        Log::Debug("Deleting scanalator", ['scanalator' => $scanalator->id]);
    }
	
	/**
     * Listen to the scanalator deleted event.
     *
     * @param  $scanalator
     * @return void
     */
    public function deleted($scanalator)
    {
        parent::deleted($scanalator);

        Log::Info("Deleted scanalator", ['scanalator' => $scanalator->id]);
    }
	
	/**
     * Listen to the scanalator restoring event.
     *
     * @param  $scanalator
     * @return void
     */
    public function restoring($scanalator)
    {
        parent::restoring($scanalator);

        Log::Debug("Restoring scanalator", ['scanalator' => $scanalator->id]);
    }
	
	/**
     * Listen to the scanalator restored event.
     *
     * @param  scanalator  $scanalator
     * @return void
     */
    public function restored($scanalator)
    {
        parent::restored($scanalator);

        Log::Info("Restored scanalator", ['scanalator' => $scanalator->id]);
    }
}
<?php

namespace App\Observers\TagObjects\Scanalator;

use App\Models\TagObjects\Scanalator\ScanalatorAlias;
use App\Observers\BaseManicModelObserver;
use Auth;
use Log;

class ScanalatorAliasObserver Extends BaseManicModelObserver
{
	/**
     * Listen to the scanalator alias creating event.
     *
     * @param  $scanalatorAlias
     * @return void
     */
    public function creating($scanalatorAlias)
    {	
		parent::creating($scanalatorAlias);
		
		$scanalator = $scanalatorAlias->scanalator;

        Log::Debug("Creating scanalator alias", ['scanalator alias' => $scanalatorAlias->id, 'scanalator' => $scanalator->id]);

		$scanalator->updated_by = Auth::user()->id;
		$scanalator->save();
		$scanalator->touch();
    }
	
    /**
     * Listen to the scanalator alias created event.
     *
     * @param  $scanalatorAlias
     * @return void
     */
    public function created($scanalatorAlias)
    {
        parent::created($scanalatorAlias);

        Log::Info("Created scanalator alias", ['scanalator alias' => $scanalatorAlias->id]);
    }

	/**
     * Listen to the scanalator alias updating event.
     *
     * @param  $scanalatorAlias
     * @return void
     */
    public function updating($scanalatorAlias)
    {
        parent::updating($scanalatorAlias);

        Log::Debug("Updating scanalator alias", ['scanalator alias' => $scanalatorAlias->id]);
    }
	
    /**
     * Listen to the scanalator alias updated event.
     *
     * @param  $scanalatorAlias
     * @return void
     */
    public function updated($scanalatorAlias)
    {
        parent::updated($scanalatorAlias);

        Log::Info("Updated scanalator alias", ['scanalator alias' => $scanalatorAlias->id]);
    }
	
	/**
     * Listen to the scanalator alias saving event.
     *
     * @param  $scanalatorAlias
     * @return void
     */
    public function saving($scanalatorAlias)
    {
        parent::saving($scanalatorAlias);

        Log::Debug("Saving scanalator alias", ['scanalator alias' => $scanalatorAlias->id]);
    }
	
    /**
     * Listen to the scanalator alias saved event.
     *
     * @param  $scanalatorAlias
     * @return void
     */
    public function saved($scanalatorAlias)
    {
        parent::saved($scanalatorAlias);

        Log::Debug("Saved scanalator alias", ['scanalator alias' => $scanalatorAlias->id]);
    }
	
    /**
     * Listen to the scanalator alias deleting event.
     *
     * @param  $scanalatorAlias
     * @return void
     */
    public function deleting($scanalatorAlias)
    {
        parent::deleting($scanalatorAlias);
		
        Log::Debug("Deleting scanalator alias", ['scanalator alias' => $scanalatorAlias->id]);

		$scanalator = $scanalatorAlias->scanalator;
		$scanalator->updated_by = Auth::user()->id;
		$scanalator->save();
		$scanalator->touch();
    }
	
	/**
     * Listen to the scanalator alias deleted event.
     *
     * @param  $scanalatorAlias
     * @return void
     */
    public function deleted($scanalatorAlias)
    {
        parent::deleted($scanalatorAlias);

        Log::Info("Deleted scanalator alias", ['scanalator alias' => $scanalatorAlias->id]);
    }
	
	/**
     * Listen to the scanalator alias restoring event.
     *
     * @param  $scanalatorAlias
     * @return void
     */
    public function restoring($scanalatorAlias)
    {
        parent::restoring($scanalatorAlias);

        Log::Debug("Restoring scanalator alias", ['scanalator alias' => $scanalatorAlias->id]);
    }
	
	/**
     * Listen to the scanalator alias restored event.
     *
     * @param  scanalator alias  $scanalatorAlias
     * @return void
     */
    public function restored($scanalatorAlias)
    {
        parent::restored($scanalatorAlias);

        Log::Info("Restored scanalator alias", ['scanalator alias' => $scanalatorAlias->id]);
    }
}
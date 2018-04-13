<?php

namespace App\Observers\User;

use App\Models\User\CollectionBlacklist;
use App\Observers\BaseManicModelObserver;
use Log;

class CollectionBlacklistObserver Extends BaseManicModelObserver
{
	/**
     * Listen to the collection creating event.
     *
     * @param  $collectionBlacklist
     * @return void
     */
    public function creating($collectionBlacklist)
    {	
		parent::creating($collectionBlacklist);

        $collection = $collectionBlacklist->collection;

        Log::Debug("Creating collection blacklist entry", ['collection blacklist' => $collectionBlacklist->id, 'collection' => $collection->id]);
    }
	
    /**
     * Listen to the collection created event.
     *
     * @param  $collectionBlacklist
     * @return void
     */
    public function created($collectionBlacklist)
    {
        parent::created($collectionBlacklist);

        Log::Info("Created collection blacklist entry", ['collection blacklist' => $collectionBlacklist->id]);
    }

	/**
     * Listen to the collection updating event.
     *
     * @param  $collectionBlacklist
     * @return void
     */
    public function updating($collectionBlacklist)
    {
        parent::updating($collectionBlacklist);

        Log::Debug("Updating collection blacklist entry", ['collection blacklist' => $collectionBlacklist->id]);
    }
	
    /**
     * Listen to the collection updated event.
     *
     * @param  $collectionBlacklist
     * @return void
     */
    public function updated($collectionBlacklist)
    {
        parent::updated($collectionBlacklist);

        Log::Info("Updated collection blacklist entry", ['collection blacklist' => $collectionBlacklist->id]);
    }
	
	/**
     * Listen to the collection saving event.
     *
     * @param  $collectionBlacklist
     * @return void
     */
    public function saving($collectionBlacklist)
    {
        parent::saving($collectionBlacklist);

        Log::Debug("Saving collection blacklist entry", ['collection blacklist' => $collectionBlacklist->id]);
    }
	
    /**
     * Listen to the collection saved event.
     *
     * @param  $collectionBlacklist
     * @return void
     */
    public function saved($collectionBlacklist)
    {
        parent::saved($collectionBlacklist);

        Log::Info("Saved collection blacklist entry", ['collection blacklist' => $collectionBlacklist->id]);
    }
	
    /**
     * Listen to the collection deleting event.
     *
     * @param  $collectionBlacklist
     * @return void
     */
    public function deleting($collectionBlacklist)
    {
        parent::deleting($collectionBlacklist);

        Log::Debug("Deleting collection blacklist entry", ['collection blacklist' => $collectionBlacklist->id]);
    }
	
	/**
     * Listen to the collection deleted event.
     *
     * @param  $collectionBlacklist
     * @return void
     */
    public function deleted($collectionBlacklist)
    {
        parent::deleted($collectionBlacklist);

        Log::Info("Deleted collection blacklist entry", ['collection blacklist' => $collectionBlacklist->id]);
    }
	
	/**
     * Listen to the collection restoring event.
     *
     * @param  $collectionBlacklist
     * @return void
     */
    public function restoring($collectionBlacklist)
    {
        parent::restoring($collectionBlacklist);

        Log::Debug("Restoring collection blacklist entry", ['collection blacklist' => $collectionBlacklist->id]);
    }
	
	/**
     * Listen to the collection restored event.
     *
     * @param  $collectionBlacklist
     * @return void
     */
    public function restored($collectionBlacklist)
    {
        parent::restored($collectionBlacklist);

        Log::Info("Restored collection blacklist entry", ['collection blacklist' => $collectionBlacklist->id]);
    }
}
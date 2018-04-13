<?php

namespace App\Observers\User;

use App\Models\User\CollectionFavourite;
use App\Observers\BaseManicModelObserver;
use Log;

class CollectionFavouritesObserver Extends BaseManicModelObserver
{
	/**
     * Listen to the collection creating event.
     *
     * @param  $collectionFavourite
     * @return void
     */
    public function creating($collectionFavourite)
    {	
		parent::creating($collectionFavourite);

        $collection = $collectionFavourite->collection;

        Log::Debug("Creating collection favourite entry", ['collection favourite' => $collectionFavourite->id, 'collection' => $collection->id]);
    }
	
    /**
     * Listen to the collection created event.
     *
     * @param  $collectionFavourite
     * @return void
     */
    public function created($collectionFavourite)
    {
        parent::created($collectionFavourite);

        Log::Info("Created collection favourite entry", ['collection favourite' => $collectionFavourite->id]);
    }

	/**
     * Listen to the collection updating event.
     *
     * @param  $collectionFavourite
     * @return void
     */
    public function updating($collectionFavourite)
    {
        parent::updating($collectionFavourite);

        Log::Debug("Updating collection favourite entry", ['collection favourite' => $collectionFavourite->id]);
    }
	
    /**
     * Listen to the collection updated event.
     *
     * @param  $collectionFavourite
     * @return void
     */
    public function updated($collectionFavourite)
    {
        parent::updated($collectionFavourite);

        Log::Info("Updated collection favourite entry", ['collection favourite' => $collectionFavourite->id]);
    }
	
	/**
     * Listen to the collection saving event.
     *
     * @param  $collectionFavourite
     * @return void
     */
    public function saving($collectionFavourite)
    {
        parent::saving($collectionFavourite);

        Log::Debug("Saving collection favourite entry", ['collection favourite' => $collectionFavourite->id]);
    }
	
    /**
     * Listen to the collection saved event.
     *
     * @param  $collectionFavourite
     * @return void
     */
    public function saved($collectionFavourite)
    {
        parent::saved($collectionFavourite);

        Log::Info("Saved collection favourite entry", ['collection favourite' => $collectionFavourite->id]);
    }
	
    /**
     * Listen to the collection deleting event.
     *
     * @param  $collectionFavourite
     * @return void
     */
    public function deleting($collectionFavourite)
    {
        parent::deleting($collectionFavourite);

        Log::Debug("Deleting collection favourite entry", ['collection favourite' => $collectionFavourite->id]);
    }
	
	/**
     * Listen to the collection deleted event.
     *
     * @param  $collectionFavourite
     * @return void
     */
    public function deleted($collectionFavourite)
    {
        parent::deleted($collectionFavourite);

        Log::Info("Deleted collection favourite entry", ['collection favourite' => $collectionFavourite->id]);
    }
	
	/**
     * Listen to the collection restoring event.
     *
     * @param  $collectionFavourite
     * @return void
     */
    public function restoring($collectionFavourite)
    {
        parent::restoring($collectionFavourite);

        Log::Debug("Restoring collection favourite entry", ['collection favourite' => $collectionFavourite->id]);
    }
	
	/**
     * Listen to the collection restored event.
     *
     * @param  $collectionFavourite
     * @return void
     */
    public function restored($collectionFavourite)
    {
        parent::restored($collectionFavourite);

        Log::Info("Restored collection favourite entry", ['collection favourite' => $collectionFavourite->id]);
    }
}
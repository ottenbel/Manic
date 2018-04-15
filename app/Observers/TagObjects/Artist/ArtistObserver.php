<?php

namespace App\Observers\TagObjects\Artist;

use App\Models\TagObjects\Artist\Artist;
use App\Observers\BaseManicModelObserver;
use Log;

class ArtistObserver Extends BaseManicModelObserver
{
	/**
     * Listen to the artist creating event.
     *
     * @param  $artist
     * @return void
     */
    public function creating($artist)
    {	
		parent::creating($artist);

        Log::Debug("Creating artist", ['artist' => $artist->id]);
    }
	
    /**
     * Listen to the artist created event.
     *
     * @param  $artist
     * @return void
     */
    public function created($artist)
    {
        parent::created($artist);

        Log::Info("Created artist", ['artist' => $artist->id]);
    }

	/**
     * Listen to the artist updating event.
     *
     * @param  $artist
     * @return void
     */
    public function updating($artist)
    {
        parent::updating($artist);

        Log::Debug("Updating artist", ['artist' => $artist->id]);
    }
	
    /**
     * Listen to the artist updated event.
     *
     * @param  $artist
     * @return void
     */
    public function updated($artist)
    {
        parent::updated($artist);

        Log::Info("Updated artist", ['artist' => $artist->id]);
    }
	
	/**
     * Listen to the artist saving event.
     *
     * @param  $artist
     * @return void
     */
    public function saving($artist)
    {
        parent::saving($artist);

        Log::Debug("Saving artist", ['artist' => $artist->id]);
    }
	
    /**
     * Listen to the artist saved event.
     *
     * @param  $artist
     * @return void
     */
    public function saved($artist)
    {
        parent::saved($artist);

        Log::Debug("Saved artist", ['artist' => $artist->id]);
    }
	
    /**
     * Listen to the artist deleting event.
     *
     * @param  $artist
     * @return void
     */
    public function deleting($artist)
    {
        parent::deleting($artist);

        Log::Debug("Deleting artist", ['artist' => $artist->id]);
    }
	
	/**
     * Listen to the artist deleted event.
     *
     * @param  $artist
     * @return void
     */
    public function deleted($artist)
    {
        parent::deleted($artist);

        Log::Info("Deleted artist", ['artist' => $artist->id]);
    }
	
	/**
     * Listen to the artist restoring event.
     *
     * @param  $artist
     * @return void
     */
    public function restoring($artist)
    {
        parent::restoring($artist);

        Log::Debug("Restoring artist", ['artist' => $artist->id]);
    }
	
	/**
     * Listen to the artist restored event.
     *
     * @param  artist  $artist
     * @return void
     */
    public function restored($artist)
    {
        parent::restored($artist);

        Log::Info("Restored artist", ['artist' => $artist->id]);
    }
}
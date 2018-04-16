<?php

namespace App\Observers\TagObjects\Artist;

use App\Models\TagObjects\Artist\ArtistAlias;
use App\Observers\BaseManicModelObserver;
use Auth;
use Log;

class ArtistAliasObserver Extends BaseManicModelObserver
{
	/**
     * Listen to the artist alias creating event.
     *
     * @param  $artistAlias
     * @return void
     */
    public function creating($artistAlias)
    {	
		parent::creating($artistAlias);

        Log::Debug("Creating artist alias", ['artist alias' => $artistAlias->id, 'artist' => $artistAlias->artist->id]);

        $artist = $artistAlias->artist;

		$artist->updated_by = Auth::user()->id;
		$artist->save();
		$artist->touch();

        
    }
	
    /**
     * Listen to the artist alias created event.
     *
     * @param  $artistAlias
     * @return void
     */
    public function created($artistAlias)
    {
        parent::created($artistAlias);

        Log::Info("Created artist alias", ['artist alias' => $artistAlias->id, 'artist' => $artist->alias->id]);
    }

	/**
     * Listen to the artist alias updating event.
     *
     * @param  $artistAlias
     * @return void
     */
    public function updating($artistAlias)
    {
        parent::updating($artistAlias);

        Log::Debug("Updating artist alias", ['artist alias' => $artistAlias->id, 'artist' => $artist->alias->id]);
    }
	
    /**
     * Listen to the artist alias updated event.
     *
     * @param  $artistAlias
     * @return void
     */
    public function updated($artistAlias)
    {
        parent::updated($artistAlias);

        Log::Info("Updated artist alias", ['artist alias' => $artistAlias->id, 'artist' => $artist->alias->id]);
    }
	
	/**
     * Listen to the artist alias saving event.
     *
     * @param  $artistAlias
     * @return void
     */
    public function saving($artistAlias)
    {
        parent::saving($artistAlias);

        Log::Debug("Saving artist alias", ['artist alias' => $artistAlias->id, 'artist' => $artist->alias->id]);
    }
	
    /**
     * Listen to the artist alias saved event.
     *
     * @param  $artistAlias
     * @return void
     */
    public function saved($artistAlias)
    {
        parent::saved($artistAlias);

        Log::Debug("Saved artist alias", ['artist alias' => $artistAlias->id, 'artist' => $artist->alias->id]);
    }
	
    /**
     * Listen to the artist alias deleting event.
     *
     * @param  $artistAlias
     * @return void
     */
    public function deleting($artistAlias)
    {
        parent::deleting($artistAlias);
		
        Log::Debug("Deleting artist alias", ['artist alias' => $artistAlias->id, 'artist' => $artist->alias->id]);

		$artist = $artistAlias->artist;
        $artist->updated_by = Auth::user()->id;
		$artist->save();
		$artist->touch();
    }
	
	/**
     * Listen to the artist alias deleted event.
     *
     * @param  $artistAlias
     * @return void
     */
    public function deleted($artistAlias)
    {
        parent::deleted($artistAlias);

        Log::Info("Deleted artist alias", ['artist alias' => $artistAlias->id, 'artist' => $artist->alias->id]);
    }
	
	/**
     * Listen to the artist alias restoring event.
     *
     * @param  $artistAlias
     * @return void
     */
    public function restoring($artistAlias)
    {
        parent::restoring($artistAlias);

        Log::Debug("Restoring artist alias", ['artist alias' => $artistAlias->id, 'artist' => $artist->alias->id]);
    }
	
	/**
     * Listen to the artist alias restored event.
     *
     * @param  artist alias  $artistAlias
     * @return void
     */
    public function restored($artistAlias)
    {
        parent::restored($artistAlias);

        Log::Info("Restored artist alias", ['artist alias' => $artistAlias->id, 'artist' => $artist->alias->id]);
    }
}
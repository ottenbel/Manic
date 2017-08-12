<?php

namespace App\Observers\TagObjects\Artist;

use App\Models\TagObjects\Artist\ArtistAlias;
use App\Observers\BaseManicModelObserver;
use Auth;

class ArtistAliasObserver Extends BaseManicModelObserver
{
	/**
     * Listen to the artist alias creating event.
     *
     * @param  $model
     * @return void
     */
    public function creating($model)
    {	
		parent::creating($model);
		
		$artist = $model->artist()->first();
		$artist->updated_by = Auth::user()->id;
		$artist->save();
		$artist->touch();
    }
	
    /**
     * Listen to the artist alias created event.
     *
     * @param  $model
     * @return void
     */
    public function created($model)
    {
        parent::created($model);
    }

	/**
     * Listen to the artist alias updating event.
     *
     * @param  $model
     * @return void
     */
    public function updating($model)
    {
        parent::updating($model);
    }
	
    /**
     * Listen to the artist alias updated event.
     *
     * @param  $model
     * @return void
     */
    public function updated($model)
    {
        parent::updated($model);
    }
	
	/**
     * Listen to the artist alias saving event.
     *
     * @param  $model
     * @return void
     */
    public function saving($model)
    {
        parent::saving($model);
    }
	
    /**
     * Listen to the artist alias saved event.
     *
     * @param  $model
     * @return void
     */
    public function saved($model)
    {
        parent::saved($model);
    }
	
    /**
     * Listen to the artist alias deleting event.
     *
     * @param  $model
     * @return void
     */
    public function deleting($model)
    {
        parent::deleting($model);
		
		$artist = $model->artist()->first();
		$artist->updated_by = Auth::user()->id;
		$artist->save();
		$artist->touch();
    }
	
	/**
     * Listen to the artist alias deleted event.
     *
     * @param  $model
     * @return void
     */
    public function deleted($model)
    {
        parent::deleted($model);
    }
	
	/**
     * Listen to the artist alias restoring event.
     *
     * @param  $model
     * @return void
     */
    public function restoring($model)
    {
        parent::restoring($model);
    }
	
	/**
     * Listen to the artist alias restored event.
     *
     * @param  artist alias  $model
     * @return void
     */
    public function restored($model)
    {
        parent::restored($model);
    }
}
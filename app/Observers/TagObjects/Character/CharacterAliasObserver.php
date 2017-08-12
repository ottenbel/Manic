<?php

namespace App\Observers\TagObjects\Character;

use App\Models\TagObjects\Character\CharacterAlias;
use App\Observers\BaseManicModelObserver;
use Auth;

class CharacterAliasObserver Extends BaseManicModelObserver
{
	/**
     * Listen to the character alias creating event.
     *
     * @param  $model
     * @return void
     */
    public function creating($model)
    {	
		parent::creating($model);
		
		$character = $model->character()->first();
		$character->updated_by = Auth::user()->id;
		$character->save();
		$character->touch();
    }
	
    /**
     * Listen to the character alias created event.
     *
     * @param  $model
     * @return void
     */
    public function created($model)
    {
        parent::created($model);
    }

	/**
     * Listen to the character alias updating event.
     *
     * @param  $model
     * @return void
     */
    public function updating($model)
    {
        parent::updating($model);
    }
	
    /**
     * Listen to the character alias updated event.
     *
     * @param  $model
     * @return void
     */
    public function updated($model)
    {
        parent::updated($model);
    }
	
	/**
     * Listen to the character alias saving event.
     *
     * @param  $model
     * @return void
     */
    public function saving($model)
    {
        parent::saving($model);
    }
	
    /**
     * Listen to the character alias saved event.
     *
     * @param  $model
     * @return void
     */
    public function saved($model)
    {
        parent::saved($model);
    }
	
    /**
     * Listen to the character alias deleting event.
     *
     * @param  $model
     * @return void
     */
    public function deleting($model)
    {
        parent::deleting($model);
		
		$character = $model->character()->first();
		$character->updated_by = Auth::user()->id;
		$character->save();
		$character->touch();
    }
	
	/**
     * Listen to the character alias deleted event.
     *
     * @param  $model
     * @return void
     */
    public function deleted($model)
    {
        parent::deleted($model);
    }
	
	/**
     * Listen to the character alias restoring event.
     *
     * @param  $model
     * @return void
     */
    public function restoring($model)
    {
        parent::restoring($model);
    }
	
	/**
     * Listen to the character alias restored event.
     *
     * @param  character alias  $model
     * @return void
     */
    public function restored($model)
    {
        parent::restored($model);
    }
}
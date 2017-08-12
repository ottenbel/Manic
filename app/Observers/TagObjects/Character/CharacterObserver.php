<?php

namespace App\Observers\TagObjects\Character;

use App\Models\TagObjects\Character\Character;
use App\Observers\BaseManicModelObserver;
use Auth;

class CharacterObserver Extends BaseManicModelObserver
{
	/**
     * Listen to the character creating event.
     *
     * @param  $model
     * @return void
     */
    public function creating($model)
    {	
		parent::creating($model);
		
		$series = $model->series()->first();
		$series->updated_by = Auth::user()->id;
		$series->save();
		$series->touch();
    }
	
    /**
     * Listen to the character created event.
     *
     * @param  $model
     * @return void
     */
    public function created($model)
    {
        parent::created($model);
    }

	/**
     * Listen to the character updating event.
     *
     * @param  $model
     * @return void
     */
    public function updating($model)
    {
        parent::updating($model);
    }
	
    /**
     * Listen to the character updated event.
     *
     * @param  $model
     * @return void
     */
    public function updated($model)
    {
        parent::updated($model);
    }
	
	/**
     * Listen to the character saving event.
     *
     * @param  $model
     * @return void
     */
    public function saving($model)
    {
        parent::saving($model);
    }
	
    /**
     * Listen to the character saved event.
     *
     * @param  $model
     * @return void
     */
    public function saved($model)
    {
        parent::saved($model);
    }
	
    /**
     * Listen to the character deleting event.
     *
     * @param  $model
     * @return void
     */
    public function deleting($model)
    {
        parent::deleting($model);
		
		$series = $model->series()->first();
		$series->updated_by = Auth::user()->id;
		$series->save();
		$series->touch();
    }
	
	/**
     * Listen to the character deleted event.
     *
     * @param  $model
     * @return void
     */
    public function deleted($model)
    {
        parent::deleted($model);
    }
	
	/**
     * Listen to the character restoring event.
     *
     * @param  $model
     * @return void
     */
    public function restoring($model)
    {
        parent::restoring($model);
    }
	
	/**
     * Listen to the character restored event.
     *
     * @param  character  $model
     * @return void
     */
    public function restored($model)
    {
        parent::restored($model);
    }
}
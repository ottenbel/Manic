<?php

namespace App\Observers\TagObjects\Scanalator;

use App\Models\TagObjects\Scanalator\ScanalatorAlias;
use App\Observers\BaseManicModelObserver;
use Auth;

class ScanalatorAliasObserver Extends BaseManicModelObserver
{
	/**
     * Listen to the scanalator alias creating event.
     *
     * @param  $model
     * @return void
     */
    public function creating($model)
    {	
		parent::creating($model);
		
		$scanalator = $model->scanalator()->first();
		$scanalator->updated_by = Auth::user()->id;
		$scanalator->save();
		$scanalator->touch();
    }
	
    /**
     * Listen to the scanalator alias created event.
     *
     * @param  $model
     * @return void
     */
    public function created($model)
    {
        parent::created($model);
    }

	/**
     * Listen to the scanalator alias updating event.
     *
     * @param  $model
     * @return void
     */
    public function updating($model)
    {
        parent::updating($model);
    }
	
    /**
     * Listen to the scanalator alias updated event.
     *
     * @param  $model
     * @return void
     */
    public function updated($model)
    {
        parent::updated($model);
    }
	
	/**
     * Listen to the scanalator alias saving event.
     *
     * @param  $model
     * @return void
     */
    public function saving($model)
    {
        parent::saving($model);
    }
	
    /**
     * Listen to the scanalator alias saved event.
     *
     * @param  $model
     * @return void
     */
    public function saved($model)
    {
        parent::saved($model);
    }
	
    /**
     * Listen to the scanalator alias deleting event.
     *
     * @param  $model
     * @return void
     */
    public function deleting($model)
    {
        parent::deleting($model);
		
		$scanalator = $model->scanalator()->first();
		$scanalator->updated_by = Auth::user()->id;
		$scanalator->save();
		$scanalator->touch();
    }
	
	/**
     * Listen to the scanalator alias deleted event.
     *
     * @param  $model
     * @return void
     */
    public function deleted($model)
    {
        parent::deleted($model);
    }
	
	/**
     * Listen to the scanalator alias restoring event.
     *
     * @param  $model
     * @return void
     */
    public function restoring($model)
    {
        parent::restoring($model);
    }
	
	/**
     * Listen to the scanalator alias restored event.
     *
     * @param  scanalator alias  $model
     * @return void
     */
    public function restored($model)
    {
        parent::restored($model);
    }
}
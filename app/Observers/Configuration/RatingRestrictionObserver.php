<?php

namespace App\Observers\Configuration\RatingRestriction;

use App\Models\Configuration\ConfigurationRatingRestriction;
use App\Observers\BaseManicModelObserver;

class RatingRestrictionObserver Extends BaseManicModelObserver
{
	/**
     * Listen to the rating restriction creating event.
     *
     * @param  $model
     * @return void
     */
    public function creating($model)
    {	
		parent::creating($model);
    }
	
    /**
     * Listen to the rating restriction created event.
     *
     * @param  $model
     * @return void
     */
    public function created($model)
    {
        parent::created($model);
    }

	/**
     * Listen to the rating restriction updating event.
     *
     * @param  $model
     * @return void
     */
    public function updating($model)
    {
        parent::updating($model);
    }
	
    /**
     * Listen to the rating restriction updated event.
     *
     * @param  $model
     * @return void
     */
    public function updated($model)
    {
        parent::updated($model);
    }
	
	/**
     * Listen to the rating restriction saving event.
     *
     * @param  $model
     * @return void
     */
    public function saving($model)
    {
        parent::saving($model);
    }
	
    /**
     * Listen to the rating restriction saved event.
     *
     * @param  $model
     * @return void
     */
    public function saved($model)
    {
        parent::saved($model);
    }
	
    /**
     * Listen to the rating restriction deleting event.
     *
     * @param  $model
     * @return void
     */
    public function deleting($model)
    {
        parent::deleting($model);
    }
	
	/**
     * Listen to the rating restriction deleted event.
     *
     * @param  $model
     * @return void
     */
    public function deleted($model)
    {
        parent::deleted($model);
    }
	
	/**
     * Listen to the rating restriction restoring event.
     *
     * @param  $model
     * @return void
     */
    public function restoring($model)
    {
        parent::restoring($model);
    }
	
	/**
     * Listen to the rating restriction restored event.
     *
     * @param  rating restriction  $model
     * @return void
     */
    public function restored($model)
    {
        parent::restored($model);
    }
}
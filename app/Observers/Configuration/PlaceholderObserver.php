<?php

namespace App\Observers\Configuration\Placeholder;

use App\Models\Configuration\ConfigurationPlaceholder;
use App\Observers\BaseManicModelObserver;

class PlaceholderObserver Extends BaseManicModelObserver
{
	/**
     * Listen to the placeholder creating event.
     *
     * @param  $model
     * @return void
     */
    public function creating($model)
    {	
		parent::creating($model);
    }
	
    /**
     * Listen to the placeholder created event.
     *
     * @param  $model
     * @return void
     */
    public function created($model)
    {
        parent::created($model);
    }

	/**
     * Listen to the placeholder updating event.
     *
     * @param  $model
     * @return void
     */
    public function updating($model)
    {
        parent::updating($model);
    }
	
    /**
     * Listen to the placeholder updated event.
     *
     * @param  $model
     * @return void
     */
    public function updated($model)
    {
        parent::updated($model);
    }
	
	/**
     * Listen to the placeholder saving event.
     *
     * @param  $model
     * @return void
     */
    public function saving($model)
    {
        parent::saving($model);
    }
	
    /**
     * Listen to the placeholder saved event.
     *
     * @param  $model
     * @return void
     */
    public function saved($model)
    {
        parent::saved($model);
    }
	
    /**
     * Listen to the placeholder deleting event.
     *
     * @param  $model
     * @return void
     */
    public function deleting($model)
    {
        parent::deleting($model);
    }
	
	/**
     * Listen to the placeholder deleted event.
     *
     * @param  $model
     * @return void
     */
    public function deleted($model)
    {
        parent::deleted($model);
    }
	
	/**
     * Listen to the placeholder restoring event.
     *
     * @param  $model
     * @return void
     */
    public function restoring($model)
    {
        parent::restoring($model);
    }
	
	/**
     * Listen to the placeholder restored event.
     *
     * @param  placeholder  $model
     * @return void
     */
    public function restored($model)
    {
        parent::restored($model);
    }
}
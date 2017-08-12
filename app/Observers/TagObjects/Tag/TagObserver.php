<?php

namespace App\Observers\TagObjects\Tag;

use App\Models\TagObjects\Tag\Tag;
use App\Observers\BaseManicModelObserver;
use Auth;

class TagObserver Extends BaseManicModelObserver
{
	/**
     * Listen to the tag creating event.
     *
     * @param  $model
     * @return void
     */
    public function creating($model)
    {	
		parent::creating($model);
    }
	
    /**
     * Listen to the tag created event.
     *
     * @param  $model
     * @return void
     */
    public function created($model)
    {
        parent::created($model);
    }

	/**
     * Listen to the tag updating event.
     *
     * @param  $model
     * @return void
     */
    public function updating($model)
    {
        parent::updating($model);
    }
	
    /**
     * Listen to the tag updated event.
     *
     * @param  $model
     * @return void
     */
    public function updated($model)
    {
        parent::updated($model);
    }
	
	/**
     * Listen to the tag saving event.
     *
     * @param  $model
     * @return void
     */
    public function saving($model)
    {
        parent::saving($model);
    }
	
    /**
     * Listen to the tag saved event.
     *
     * @param  $model
     * @return void
     */
    public function saved($model)
    {
        parent::saved($model);
    }
	
    /**
     * Listen to the tag deleting event.
     *
     * @param  $model
     * @return void
     */
    public function deleting($model)
    {
        parent::deleting($model);
    }
	
	/**
     * Listen to the tag deleted event.
     *
     * @param  $model
     * @return void
     */
    public function deleted($model)
    {
        parent::deleted($model);
    }
	
	/**
     * Listen to the tag restoring event.
     *
     * @param  $model
     * @return void
     */
    public function restoring($model)
    {
        parent::restoring($model);
    }
	
	/**
     * Listen to the tag restored event.
     *
     * @param  tag  $model
     * @return void
     */
    public function restored($model)
    {
        parent::restored($model);
    }
}
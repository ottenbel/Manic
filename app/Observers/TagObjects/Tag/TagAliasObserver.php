<?php

namespace App\Observers\TagObjects\Tag;

use App\Models\TagObjects\Tag\TagAlias;
use App\Observers\BaseManicModelObserver;
use Auth;

class TagAliasObserver Extends BaseManicModelObserver
{
	/**
     * Listen to the tag alias creating event.
     *
     * @param  $model
     * @return void
     */
    public function creating($model)
    {	
		parent::creating($model);
		
		$tag = $model->tag()->first();
		$tag->updated_by = Auth::user()->id;
		$tag->save();
		$tag->touch();
    }
	
    /**
     * Listen to the tag alias created event.
     *
     * @param  $model
     * @return void
     */
    public function created($model)
    {
        parent::created($model);
    }

	/**
     * Listen to the tag alias updating event.
     *
     * @param  $model
     * @return void
     */
    public function updating($model)
    {
        parent::updating($model);
    }
	
    /**
     * Listen to the tag alias updated event.
     *
     * @param  $model
     * @return void
     */
    public function updated($model)
    {
        parent::updated($model);
    }
	
	/**
     * Listen to the tag alias saving event.
     *
     * @param  $model
     * @return void
     */
    public function saving($model)
    {
        parent::saving($model);
    }
	
    /**
     * Listen to the tag alias saved event.
     *
     * @param  $model
     * @return void
     */
    public function saved($model)
    {
        parent::saved($model);
    }
	
    /**
     * Listen to the tag alias deleting event.
     *
     * @param  $model
     * @return void
     */
    public function deleting($model)
    {
        parent::deleting($model);
		
		$tag = $model->tag()->first();
		$tag->updated_by = Auth::user()->id;
		$tag->save();
		$tag->touch();
    }
	
	/**
     * Listen to the tag alias deleted event.
     *
     * @param  $model
     * @return void
     */
    public function deleted($model)
    {
        parent::deleted($model);
    }
	
	/**
     * Listen to the tag alias restoring event.
     *
     * @param  $model
     * @return void
     */
    public function restoring($model)
    {
        parent::restoring($model);
    }
	
	/**
     * Listen to the tag alias restored event.
     *
     * @param  tag alias  $model
     * @return void
     */
    public function restored($model)
    {
        parent::restored($model);
    }
}
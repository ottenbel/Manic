<?php

namespace App\Observers\TagObjects\Tag;

use App\Models\TagObjects\Tag\Tag;
use App\Observers\BaseManicModelObserver;
use Auth;
use Log;

class TagObserver Extends BaseManicModelObserver
{
	/**
     * Listen to the tag creating event.
     *
     * @param  $tag
     * @return void
     */
    public function creating($tag)
    {	
		parent::creating($tag);

        Log::Debug("Creating tag", ['tag' => $tag->id]);
    }
	
    /**
     * Listen to the tag created event.
     *
     * @param  $tag
     * @return void
     */
    public function created($tag)
    {
        parent::created($tag);

        Log::Info("Created tag", ['tag' => $tag->id]);
    }

	/**
     * Listen to the tag updating event.
     *
     * @param  $tag
     * @return void
     */
    public function updating($tag)
    {
        parent::updating($tag);

        Log::Debug("Updating tag", ['tag' => $tag->id]);
    }
	
    /**
     * Listen to the tag updated event.
     *
     * @param  $tag
     * @return void
     */
    public function updated($tag)
    {
        parent::updated($tag);

        Log::Info("Updated tag", ['tag' => $tag->id]);
    }
	
	/**
     * Listen to the tag saving event.
     *
     * @param  $tag
     * @return void
     */
    public function saving($tag)
    {
        parent::saving($tag);

        Log::Debug("Saving tag", ['tag' => $tag->id]);
    }
	
    /**
     * Listen to the tag saved event.
     *
     * @param  $tag
     * @return void
     */
    public function saved($tag)
    {
        parent::saved($tag);

        Log::Info("Saved tag", ['tag' => $tag->id]);
    }
	
    /**
     * Listen to the tag deleting event.
     *
     * @param  $tag
     * @return void
     */
    public function deleting($tag)
    {
        parent::deleting($tag);

        Log::Debug("Deleting tag", ['tag' => $tag->id]);
    }
	
	/**
     * Listen to the tag deleted event.
     *
     * @param  $tag
     * @return void
     */
    public function deleted($tag)
    {
        parent::deleted($tag);

        Log::Info("Deleted tag", ['tag' => $tag->id]);
    }
	
	/**
     * Listen to the tag restoring event.
     *
     * @param  $tag
     * @return void
     */
    public function restoring($tag)
    {
        parent::restoring($tag);

        Log::Debug("Restoring tag", ['tag' => $tag->id]);
    }
	
	/**
     * Listen to the tag restored event.
     *
     * @param  tag  $tag
     * @return void
     */
    public function restored($tag)
    {
        parent::restored($tag);

        Log::Info("Restored tag", ['tag' => $tag->id]);
    }
}
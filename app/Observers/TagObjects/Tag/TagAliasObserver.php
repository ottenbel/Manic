<?php

namespace App\Observers\TagObjects\Tag;

use App\Models\TagObjects\Tag\TagAlias;
use App\Observers\BaseManicModelObserver;
use Auth;
use Log;

class TagAliasObserver Extends BaseManicModelObserver
{
	/**
     * Listen to the tag alias creating event.
     *
     * @param  $tagAlias
     * @return void
     */
    public function creating($tagAlias)
    {	
		parent::creating($tagAlias);

        Log::Debug("Creating tag alias", ['tag alias' => $tagAlias->id, 'tag' => $tagAlias->tag->id]);

        $tag = $tagAlias->tag;
		$tag->updated_by = Auth::user()->id;
		$tag->save();
		$tag->touch();
    }
	
    /**
     * Listen to the tag alias created event.
     *
     * @param  $tagAlias
     * @return void
     */
    public function created($tagAlias)
    {
        parent::created($tagAlias);

        Log::Info("Created tag alias", ['tag alias' => $tagAlias->id, 'tag' => $tagAlias->tag->id]);
    }

	/**
     * Listen to the tag alias updating event.
     *
     * @param  $tagAlias
     * @return void
     */
    public function updating($tagAlias)
    {
        parent::updating($tagAlias);

        Log::Debug("Updating tag alias", ['tag alias' => $tagAlias->id, 'tag' => $tagAlias->tag->id]);
    }
	
    /**
     * Listen to the tag alias updated event.
     *
     * @param  $tagAlias
     * @return void
     */
    public function updated($tagAlias)
    {
        parent::updated($tagAlias);

        Log::Info("Updated tag alias", ['tag alias' => $tagAlias->id, 'tag' => $tagAlias->tag->id]);
    }
	
	/**
     * Listen to the tag alias saving event.
     *
     * @param  $tagAlias
     * @return void
     */
    public function saving($tagAlias)
    {
        parent::saving($tagAlias);

        Log::Debug("Saving tag alias", ['tag alias' => $tagAlias->id, 'tag' => $tagAlias->tag->id]);
    }
	
    /**
     * Listen to the tag alias saved event.
     *
     * @param  $tagAlias
     * @return void
     */
    public function saved($tagAlias)
    {
        parent::saved($tagAlias);

        Log::Debug("Saved tag alias", ['tag alias' => $tagAlias->id, 'tag' => $tagAlias->tag->id]);
    }
	
    /**
     * Listen to the tag alias deleting event.
     *
     * @param  $tagAlias
     * @return void
     */
    public function deleting($tagAlias)
    {
        parent::deleting($tagAlias);
		
        Log::Debug("Deleting tag alias", ['tag alias' => $tagAlias->id, 'tag' => $tagAlias->tag->id]);

		$tag = $tagAlias->tag;
		$tag->updated_by = Auth::user()->id;
		$tag->save();
		$tag->touch();
    }
	
	/**
     * Listen to the tag alias deleted event.
     *
     * @param  $tagAlias
     * @return void
     */
    public function deleted($tagAlias)
    {
        parent::deleted($tagAlias);

        Log::Info("Deleted tag alias", ['tag alias' => $tagAlias->id, 'tag' => $tagAlias->tag->id]);
    }
	
	/**
     * Listen to the tag alias restoring event.
     *
     * @param  $tagAlias
     * @return void
     */
    public function restoring($tagAlias)
    {
        parent::restoring($tagAlias);

        Log::Debug("Restoring tag alias", ['tag alias' => $tagAlias->id, 'tag' => $tagAlias->tag->id]);
    }
	
	/**
     * Listen to the tag alias restored event.
     *
     * @param  tag alias  $tagAlias
     * @return void
     */
    public function restored($tagAlias)
    {
        parent::restored($tagAlias);

        Log::Info("Restored tag alias", ['tag alias' => $tagAlias->id, 'tag' => $tagAlias->tag->id]);
    }
}
<?php

namespace App\Models\TagObjects\Tag;

use App\Models\TagObjects\CollectionAssociatedTagObjectModel;

class Tag extends CollectionAssociatedTagObjectModel
{
    //Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'tags';
	
	public static function boot()
    {
        parent::boot();
    }
	
	public function aliases()
	{
		return $this->hasMany('App\Models\TagObjects\Tag\TagAlias');
	}
	
	/*
	 * Get all parent tags for the given tag.
	 */
	public function parents()
	{
		return $this->belongsToMany('App\Models\TagObjects\Tag\Tag', 'tag_tag', 'child_id', 'parent_id');
	}
	
	/*
	 * Get all child tags for the given tag.
	 */
	public function children()
	{
		return $this->belongsToMany('App\Models\TagObjects\Tag\Tag', 'tag_tag', 'parent_id', 'child_id');
	}
}

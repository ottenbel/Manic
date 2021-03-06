<?php

namespace App\Models\TagObjects\Tag;

use App\Models\BaseManicModel;

class TagAlias extends BaseManicModel
{
    //Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'tag_alias';
	
	public static function boot()
    {
        parent::boot();
    }
	
	/*
	 * Get the tag that the alias belongs to.
	 */
	public function tag()
	{
		return $this->belongsTo('App\Models\TagObjects\Tag\Tag');
	}
	
	/*
	 * A generic function call to retrieve the tag that the alias belongs to.
	 */
	public function tag_object()
	{
		return $this->belongsTo('App\Models\TagObjects\Tag\Tag', 'tag_id');
	}
}

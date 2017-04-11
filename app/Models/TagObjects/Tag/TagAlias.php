<?php

namespace App\Models\TagObjects\Tag;

use App\Models\BaseManicModel;

class TagAlias extends BaseManicModel
{
    //Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'tag_alias';
	//Update the corresponding tag when creating/updating an alias (use function name).
	protected $touches = ['tag'];
	
	public static function boot()
    {
        parent::boot();
    }
	
	/*
	 * Get the series that the alias belongs to.
	 */
	public function tag()
	{
		return $this->belongsTo('App\Models\TagObjects\Tag\Tag');
	}
}

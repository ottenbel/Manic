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
		
		/*
		 * The touches array doesn't call the update function.
		 */
		static::saved(function($model)
		{
			$tag = $model->tag();
			$tag->touch();
		}
    }
	
	/*
	 * Get the series that the alias belongs to.
	 */
	public function tag()
	{
		return $this->belongsTo('App\Models\TagObjects\Tag\Tag');
	}
}

<?php

namespace App\Models\TagObjects\Tag;

use App\Models\BaseManicModel;
use Auth;

class TagAlias extends BaseManicModel
{
    //Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'tag_alias';
	
	public static function boot()
    {
        parent::boot();
		
		static::creating(function($model)
		{
			parent::creating($model);
			
			$tag = $model->tag()->first();
			$tag->updated_by = Auth::user()->id;
			$tag->save();
			$tag->touch();
		});
		
		static::deleting(function($model)
		{
			parent::deleting($model);
			
			$tag = $model->tag()->first();
			$tag->updated_by = Auth::user()->id;
			$tag->save();
			$tag->touch();
		});
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
		return $this->belongsTo('App\Models\TagObjects\Tag\Tag');
	}
}

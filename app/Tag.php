<?php

namespace App;

use App\BaseManicModel;

class Tag extends BaseManicModel
{
    //Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'tags';
	
	/*
	 * Get the collections associated with the current tag.
	 */
	public function collections()
	{
		return $this->belongsToMany('App\Tag')->withTimestamps()->withPivot('primary', 'created_by', 'updated_by', 'deleted_at');
	}
}

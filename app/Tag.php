<?php

namespace App;

use Illuminate\Support\Facades\DB;
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
		return $this->belongsToMany('App\Collection')->withTimestamps()->withPivot('primary');
	}
	
	/*
	 * Get the number of times the tag is used across the site.
	 */
	public function usage_count()
	{
		return $this->collections()->count();
	}
}

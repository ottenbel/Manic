<?php

namespace App;

use App\BaseManicModel;

class Series extends BaseManicModel
{
    //Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'series';
	
	/*
	 * Get the mapping from series to collections.
	 */
	public function collections()
	{
		return $this->belongsToMany('App\Collection')->withTimestamps()->withPivot('primary');
	}
	
	/*
	 * Get the number of times the series is used across the site.
	 */
	public function usage_count()
	{
		return $this->collections()->count();
	}
}

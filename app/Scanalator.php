<?php

namespace App;

use App\BaseManicModel;

class Scanalator extends BaseManicModel
{
    //Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'scanalators';
	
	/*
	 * Get the chapters associated with the current scanalator.
	 */
	public function chapters()
	{
		return $this->belongsToMany('App\Chapter')->withTimestamps()->withPivot('primary');
	}
	
	/*
	 * Get the number of times the scanalator is used across the site.
	 */
	public function usage_count()
	{
		return $this->chapters();
	}
	
	/*
	 * Get the number of times the tag is used as a primary tag across the site.
	 */
	public function primary_usage_count()
	{
		return $this->chapters()->where('primary', '=', true);
	}
	
	/*
	 * Get the number of times the tag is used as a secondary tag across the site.
	 */
	public function secondary_usage_count()
	{
		return $this->chapters()->where('primary', '=', false);
	}
}

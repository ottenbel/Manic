<?php

namespace App;

use App\BaseManicModel;

class Artist extends BaseManicModel
{
	//Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'artists';
	
	/*
	 * Get the mapping from artist to collections.
	 */
	public function collections()
	{
		return $this->belongsToMany('App\Collection')->withTimestamps()->withPivot('primary');
	}
	
	/*
	 * Get the number of times the artist is used across the site.
	 */
	public function usage_count()
	{
		return $this->collections();
	}
	
	/*
	 * Get the number of times the tag is used as a primary tag across the site.
	 */
	public function primary_usage_count()
	{
		return $this->collections()->where('primary', '=', true);
	}
	
	/*
	 * Get the number of times the tag is used as a secondary tag across the site.
	 */
	public function secondary_usage_count()
	{
		return $this->collections()->where('primary', '=', false);
	}
}

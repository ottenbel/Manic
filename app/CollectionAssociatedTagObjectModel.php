<?php

namespace App;

use App\BaseManicModel;

class CollectionAssociatedTagObjectModel extends BaseManicModel
{
	/*
	 * Get the mapping from tag object to collections.
	 */
	public function collections()
	{
		return $this->belongsToMany('App\Collection')->withTimestamps()->withPivot('primary');
	}
	
	/*
	 * Get the number of times the tag object is used across the site.
	 */
	public function usage_count()
	{
		return $this->collections()->count();
	}
	
	/*
	 * Get the number of times the tag object is used as a primary tag object across the site.
	 */
	public function primary_usage_count()
	{
		return $this->collections()->where('primary', '=', true)->count();
	}
	
	/*
	 * Get the number of times the tag object is used as a secondary tag object across the site.
	 */
	public function secondary_usage_count()
	{
		return $this->collections()->where('primary', '=', false)->count();
	}
}
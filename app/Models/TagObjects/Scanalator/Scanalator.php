<?php

namespace App\Models\TagObjects\Scanalator;

use App\Models\BaseManicModel;

class Scanalator extends BaseManicModel
{
    //Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'scanalators';
	
	/*
	 * Get the chapters associated with the current scanalator.
	 */
	public function chapters()
	{
		return $this->belongsToMany('App\Models\Chapter')->withTimestamps()->withPivot('primary');
	}
	
	/*
	 * Get the number of times the scanalator is used across the site.
	 */
	public function usage_count()
	{
		return $this->chapters()->count();
	}
	
	/*
	 * Get the number of times the tag is used as a primary tag across the site.
	 */
	public function primary_usage_count()
	{
		return $this->chapters()->where('primary', '=', true)->count();
	}
	
	/*
	 * Get the number of times the tag is used as a secondary tag across the site.
	 */
	public function secondary_usage_count()
	{
		return $this->chapters()->where('primary', '=', false)->count();
	}
	
	/*
	 * Get the aliases associated with the character.
	 */
	public function aliases()
	{
		return $this->hasMany('App\Models\TagObjects\Scanalator\ScanalatorAlias');
	}
}

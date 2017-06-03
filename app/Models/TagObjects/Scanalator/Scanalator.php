<?php

namespace App\Models\TagObjects\Scanalator;

use App\Models\BaseManicModel;

class Scanalator extends BaseManicModel
{
    //Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'scanalators';
	
	public static function boot()
    {
        parent::boot();
    }
	
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
	 * Get the aliases associated with the scanalator.
	 */
	public function aliases()
	{
		return $this->hasMany('App\Models\TagObjects\Scanalator\ScanalatorAlias');
	}
	
	/*
	 * Get all parent scanalators for the given scanalator.
	 */
	public function parents()
	{
		return $this->belongsToMany('App\Models\TagObjects\Scanalator\Scanalator', 'scanalator_scanalator', 'child_id', 'parent_id');
	}
	
	/*
	 * Get all child scanalators for the given scanalator.
	 */
	public function children()
	{
		return $this->belongsToMany('App\Models\TagObjects\Scanalator\Scanalator', 'scanalator_scanalator', 'parent_id', 'child_id');
	}
	
	/*
	 * Return all descendants of the given scanalator.
	 */
	public function descendants()
	{
		$children = $this->children()->get();
		
		for ($i = 0; $i < $children->count(); $i++)
		{
			$child = $children[$i];
			if ($child->children->count() > 0)
			{
				$children = $children->merge($child->children()->get());
			}
		}
		
		return $children->unique();
	}
	
	/*
	 * Return all ancestors of the given scanalator.
	 */
	public function ancestors()
	{
		$parents = $this->parents()->get();
		
		for ($i = 0; $i < $parents->count(); $i++)
		{
			$parent = $parents[$i];
			if ($parent->parents->count() > 0)
			{
				$parents = $parents->merge($parent->parents()->get());
			}
		}
		
		return $parents->unique();
	}
}

<?php

namespace App\Models\TagObjects;

use App\Models\BaseManicModel;

class CollectionAssociatedTagObjectModel extends BaseManicModel
{
	public static function boot()
    {
        parent::boot();
    }
	
	/*
	 * Get the mapping from tag object to collections.
	 */
	public function collections()
	{
		return $this->belongsToMany('App\Models\Collection')->withTimestamps()->withPivot('primary');
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
	
	/*
	 * Return all descendants of the given tag object.
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
	 * Return all ancestors of the given tag object.
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
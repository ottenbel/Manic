<?php

namespace App\Models\TagObjects\Series;

use App\Models\TagObjects\CollectionAssociatedTagObjectModel;

class Series extends CollectionAssociatedTagObjectModel
{
    //Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'series';
	protected $fillable = ['name', 'short_description', 'description', 'url'];
	
	public static function boot()
    {
        parent::boot();
    }
	
	/*
	 * Get the characters that are associated with the series.
	 */
	public function characters()
	{
		return $this->hasMany('App\Models\TagObjects\Character\Character');
	}
	
	public function aliases()
	{
		return $this->hasMany('App\Models\TagObjects\Series\SeriesAlias');
	}
	
	/*
	 * Get all parent series for the given series.
	 */
	public function parents()
	{
		return $this->belongsToMany('App\Models\TagObjects\Series\Series', 'series_series', 'child_id', 'parent_id');
	}
	
	/*
	 * Get all child series for the given series.
	 */
	public function children()
	{
		return $this->belongsToMany('App\Models\TagObjects\Series\Series', 'series_series', 'parent_id', 'child_id');
	}
}

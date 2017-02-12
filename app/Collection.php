<?php

namespace App;

use App\BaseManicModel;

class Collection extends BaseManicModel
{
	//Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'collections';
	
	/*
	 * Get all volumes associated with the collection.
	 */
    public function volumes()
	{
		return $this->hasMany('App\Volume')->orderBy('volume_number');
	}
	
	/*
	 * Get all chapters associated with the collection.
	 */
	public function chapters()
	{
		return $this->hasManyThrough('App\Chapter', 'App\Volume');
	}
	
	/*
	 * Get the cover image associated with the collection (one sided mapping).
	 */
	public function cover_image()
	{
		return $this->belongsTo('App\Image', 'cover');
	}
	
	/*
	 * Get the mapping from collection to parent collection.
	 */
	public function parent_collection()
	{
		 return $this->belongsTo('App\Collection', 'parent_id');
	}
	 
	/*
	 * Get mapping from collection to child collections.
	 */
	public function child_collections()
	{
		return $this->hasMany('App\Collection', 'parent_id');
	}
	
	/*
	 * Get the mapping from collection to language.
	 */
	public function language()
	{
		return $this->belongsTo('App\Language');
	}
	
	/*
	 * Get the mapping from collection to artists.
	 */	 
	public function artists()
	{
		return $this->belongsToMany('App\Artist')->withTimestamps()->withPivot('primary');
	}
	
	/*
	 * Get mapping from collection to primary artists.
	 */
	public function primary_artists()
	{
		return $this->belongsToMany('App\Artist')->withTimestamps()->withPivot('primary')->where('primary', '=', true);
	}
	
	/*
	 * Get mapping from collection to secondary artists.
	 */
	public function secondary_artists()
	{
		return $this->belongsToMany('App\Artist')->withTimestamps()->withPivot('primary')->where('primary', '=', false);
	}	
	
	/*
	 * Get the mapping from collection to series.
	 */
	public function series()
	{
		return $this->belongsToMany('App\Series')->withTimestamps()->withPivot('primary');
	}
	
	/*
	 * Get the mapping from collection to primary series.
	 */
	public function primary_series()
	{
		return $this->belongsToMany('App\Series')->withTimestamps()->withPivot('primary')->where('primary', '=', true);
	}
	
	/*
	 * Get the mapping from collection to secondary series.
	 */
	public function secondary_series()
	{
		return $this->belongsToMany('App\Series')->withTimestamps()->withPivot('primary')->where('primary', '=', false);
	}
	
	/*
	 * Get the mapping from collection to tags.
	 */ 
	public function tags()
	{
		return $this->belongsToMany('App\Tag')->withTimestamps()->withPivot('primary');
	}
	
	/*
	 * Get the mapping from collection to primary tags.
	 */
	public function primary_tags()
	{
		return $this->belongsToMany('App\Tag')->withTimestamps()->withPivot('primary')->where('primary', '=', true);
	}
	
	/*
	 * Get the mapping from collection to secondary tags.
	 */
	public function secondary_tags()
	{
		return $this->belongsToMany('App\Tag')->withTimestamps()->withPivot('primary')->where('primary', '=', false);
	}
	 
	/*
	 * Get mapping from rating to collections.
	 */
	public function rating()
	{
		return $this->belongsTo('App\Rating');
	}
	
	/*
	 * Get the mapping from collection to status.
	 */
	public function status()
	{
		return $this->belongsTo('App\Status');
	}
}

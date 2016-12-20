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
		return $this->hasMany('App\Volumes');
	}
	
	/*
	 * Get all chapters associated with the collection.
	 */
	public function chapters()
	{
		return $this->hasManyThrough('App\Chapters', 'App\Volumes');
	}
	
	/*
	 * Get the cover image associated with the collection (one sided mapping).
	 */
	public function cover_image()
	{
		return $this->belongsTo('App\Images');
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
		return $this->hasMany('App\Collection', 'id', 'parent_id');
	}
	
	/*
	 * Get the mapping from collection to language.
	 */
	public function languages()
	{
		return $this->belongsTo('App\Language');
	}
	
	/*
	 * Get the mapping from collection to artists.
	 */	 
	public function artists()
	{
		return $this->belongsToMany('App\Artist')->withTimestamps()->withPivot('primary', 'created_by', 'updated_by', 'deleted_at');
	}
	
	/*
	 * Get the mapping from collection to artists.
	 */
	public function series()
	{
		return $this->belongsToMany('App\Series')->withTimestamps()->withPivot('primary', 'created_by', 'updated_by', 'deleted_at');
	}
		
	/*
	 * Get the mapping from collection to tags.
	 */ 
	public function tags()
	{
		return $this->belongsToMany('App\Tag')->withTimestamps()->withPivot('primary', 'created_by', 'updated_by', 'deleted_at');
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

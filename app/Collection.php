<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
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
	 * To Do: Add mapping from artists to collections (many to many).
	 */
	  
	/*
	 * To Do: Add mapping from languages to collections (many to many).
	 */
	   
	/*
	 * To Do: Add mapping from series to collections (many to many).
	 */
		
	/*
	 * To Do: Add mapping from tags to collections (many to many).
	 */
	 
	/*
	 * To Do: Add mapping from rating to collections (one to many).
	 */
	 
	/*
	 * To Do: Add mapping from status to collections (one many).
	 */
}

<?php

namespace App;

use App\BaseManicModel;

class Chapter extends BaseManicModel
{
	//Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'chapters';
	
	/*
	 * Get the pages associated with the chapter.
	 */
	public function pages()
	{
		return $this->belongsToMany('App\Image')->withTimestamps()->withPivot('page_number');
	}
	
	/*
	 * Get the volume that the chapter is associated with.
	 */
	public function volume()
	{
		return $this->belongsTo('App\Volume');
	}
	
	/*
	 * Get the mapping from collection to scanalators.
	 */	 
	public function scanalators()
	{
		return $this->belongsToMany('App\Scanalator')->withTimestamps()->withPivot('primary');
	}
	
	/*
	 * Get mapping from collection to primary scanalators.
	 */
	public function primary_scanalators()
	{
		return $this->belongsToMany('App\Scanalator')->withTimestamps()->withPivot('primary')->where('primary', '=', true);
	}
	
	/*
	 * Get mapping from collection to secondary scanalators.
	 */
	public function secondary_scanalators()
	{
		return $this->belongsToMany('App\Scanalator')->withTimestamps()->withPivot('primary')->where('primary', '=', false);
	}
	
	/*
	 * Get the next chapter in the collection.
	 */
	public function next_chapter()
	{
		return $this->volume->chapters()->where('number', '>', $this->number)->orderBy('number', 'asc')->take(1);
	}
	
	/*
	 * Get the previous chapter in the collection.
	 */
	public function previous_chapter()
	{
		return $this->volume->chapters()->where('number', '<', $this->number)->orderBy('number', 'desc')->take(1);
	}
}

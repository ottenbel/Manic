<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
	use Uuids;
    
	public $incrementing = false;
	
	/*
	 * Get the pages associated with the chapter.
	 */
	public function pages()
	{
		return $this->hasMany('App\Page');
	}
	
	/*
	 * Get the volume that the chapter is associated with.
	 */
	public function volume()
	{
		return $this->belongsTo('App\Volume');
	}
	
	/*
	 * To Do: Add mapping from chapter to scanalators.
	 */
}

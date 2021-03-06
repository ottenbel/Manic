<?php

namespace App\Models;

use App\Models\BaseManicModel;

class Image extends BaseManicModel
{
	//Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'images';
	
	public static function boot()
    {
        parent::boot();
    }
	
	/*
	 * Get any collections that use this image as a cover image.
	 */
	 public function collections()
	 {
		return $this->hasMany('App\Models\Collection\Collection', 'cover'); 
	 }
	 
	/*
	 * Get any volumes that use this image as a cover image.
	 */
	 public function volumes ()
	 {
		return $this->hasMany('App\Models\Volume\Volume', 'cover');
	 }	 
	 
	 /*
	  * Get any chapters that use this image as a page image.
	  */
	 public function chapters()
	 {
		 return $this->belongsToMany('App\Models\Chapter\Chapter')->withTimestamps()->withPivot('page_number');
	 }
}

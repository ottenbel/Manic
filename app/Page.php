<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use Uuids;
    
	public $incrementing = false;
	
	/*
	 * Get the image associated with the page.
	 */
	public function image()
	{
		return $this->belongsTo('App\Images');
	}
	
	/*
	 * Get the chapter that the page is associated with (one sided mapping).
	 */
	public function chapter()
	{
		return $this->belongsTo('App\Chapter');
	}
}

<?php

namespace App;

use App\BaseManicModel;

class Page extends BasicManicModel
{
    //Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'pages';
	
	/*
	 * Get the image associated with the page.
	 */
	public function image()
	{
		return $this->belongsTo('App\Images');
	}
	
	/*
	 * Get the chapter that the page is associated with.
	 */
	public function chapter()
	{
		return $this->belongsTo('App\Chapter');
	}
	
	
}

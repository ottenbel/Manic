<?php

namespace App;

use App\CollectionAssociatedTagObjectModel;

class Series extends CollectionAssociatedTagObjectModel
{
    //Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'series';
	
	/*
	 * Get the characters that are associated with the series.
	 */
	public function characters()
	{
		return $this->hasMany('App\Character');
	}
}

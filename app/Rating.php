<?php

namespace App;

use App\BaseManicModel;

class Rating extends BaseManicModel
{
    //Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'ratings';
	
	/*
	 * Get the collections associated with the current rating.
	 */
	public function collections()
	{
		return $this->hasMany('App\Collection');
	}
}

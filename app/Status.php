<?php

namespace App;

use App\BaseManicModel;

class Status extends BaseManicModel
{
    //Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'statuses';
	
	/*
	 * Get the collections associated with the current status.
	 */
	public function collections()
	{
		return $this->hasMany('App\Collection');
	}
}

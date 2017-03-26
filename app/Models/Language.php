<?php

namespace App\Models;

use App\Models\BaseManicModel;

class Language extends BaseManicModel
{
	//Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'languages';
	
	/*
	 * Get the collections associated with the current language.
	 */
	public function collections()
	{
		return $this->hasMany('App\Models\Collection');
	}
}

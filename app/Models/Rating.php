<?php

namespace App\Models;

use App\Models\BaseManicModel;

class Rating extends BaseManicModel
{
    //Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'ratings';
	protected $fillable = ['name', 'priority'];
	
	public static function boot()
    {
        parent::boot();
    }
	
	/*
	 * Get the collections associated with the current rating.
	 */
	public function collections()
	{
		return $this->hasMany('App\Models\Collection\Collection');
	}
}

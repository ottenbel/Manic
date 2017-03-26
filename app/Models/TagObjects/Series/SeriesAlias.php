<?php

namespace App\Models\TagObjects\Series;

use App\Models\BaseManicModel;

class SeriesAlias extends BaseManicModel
{
    //Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'series_alias';
	
	/*
	 * Get the series that the alias belongs to.
	 */
	public function series()
	{
		return $this->belongsTo('App\ModelsTagObjects\Series\Series')->withTimestamps();
	}
}

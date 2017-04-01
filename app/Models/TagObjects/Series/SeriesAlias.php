<?php

namespace App\Models\TagObjects\Series;

use App\Models\BaseManicModel;

class SeriesAlias extends BaseManicModel
{
    //Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'series_alias';
	//Update the corresponding series when creating/updating an alias (use function name).
	protected $touches = ['series'];
	
	/*
	 * Get the series that the alias belongs to.
	 */
	public function series()
	{
		return $this->belongsTo('App\Models\TagObjects\Series\Series');
	}
}

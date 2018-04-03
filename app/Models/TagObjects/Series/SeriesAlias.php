<?php

namespace App\Models\TagObjects\Series;

use App\Models\BaseManicModel;

class SeriesAlias extends BaseManicModel
{
    //Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'series_alias';
	
	public static function boot()
    {
        parent::boot();
    }
	
	/*
	 * Get the series that the alias belongs to.
	 */
	public function series()
	{
		return $this->belongsTo('App\Models\TagObjects\Series\Series');
	}
	
	/*
	 * A generic function call to retrieve the series that the alias belongs to.
	 */
	public function tag_object()
	{
		return $this->belongsTo('App\Models\TagObjects\Series\Series', 'series_id');
	}
}

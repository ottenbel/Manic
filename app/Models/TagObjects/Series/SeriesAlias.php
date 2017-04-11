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
		
		/*
		 * The touches array doesn't call the update function.
		 */
		static::saved(function($model)
		{
			$series = $model->series();
			$series->touch();
		}
    }
	
	/*
	 * Get the series that the alias belongs to.
	 */
	public function series()
	{
		return $this->belongsTo('App\Models\TagObjects\Series\Series');
	}
}

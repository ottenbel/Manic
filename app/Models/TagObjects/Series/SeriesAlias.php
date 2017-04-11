<?php

namespace App\Models\TagObjects\Series;

use App\Models\BaseManicModel;
use Auth;

class SeriesAlias extends BaseManicModel
{
    //Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'series_alias';
	
	public static function boot()
    {
        parent::boot();
		
		static::creating(function($model)
		{
			parent::creating($model);
			
			$series = $model->series()->first();
			$series->updated_by = Auth::user()->id;
			$series->save();
		});
		
		static::deleting(function($model)
		{
			parent::deleting($model);
			
			$series = $model->series()->first();
			$series->updated_by = Auth::user()->id;
			$series->save();
		});
    }
	
	/*
	 * Get the series that the alias belongs to.
	 */
	public function series()
	{
		return $this->belongsTo('App\Models\TagObjects\Series\Series');
	}
}

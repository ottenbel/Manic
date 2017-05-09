<?php

namespace App\Models\TagObjects\Scanalator;

use App\Models\BaseManicModel;
use Auth;

class ScanalatorAlias extends BaseManicModel
{
    //Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'scanalator_alias';
	
	public static function boot()
    {
        parent::boot();
		
		static::creating(function($model)
		{
			parent::creating($model);
			
			$scanalator = $model->scanalator()->first();
			$scanalator->updated_by = Auth::user()->id;
			$scanalator->save();
			$scanalator->touch();
		});
		
		static::deleting(function($model)
		{
			parent::deleting($model);
			
			$scanalator = $model->scanalator()->first();
			$scanalator->updated_by = Auth::user()->id;
			$scanalator->save();
			$scanalator->touch();
		});
    }
	
	/*
	 * Get the scanalator that the alias belongs to.
	 */
	public function scanalator()
	{
		return $this->belongsTo('App\Models\TagObjects\Scanalator\Scanalator');
	}
	
	/*
	 * A generic function call to retrieve the scanalator that the alias belongs to.
	 */
	public function tag_object()
	{
		return $this->belongsTo('App\Models\TagObjects\Scanalator\Scanalator', 'scanalator_id');
	}
}

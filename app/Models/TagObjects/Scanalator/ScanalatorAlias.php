<?php

namespace App\Models\TagObjects\Scanalator;

use App\Models\BaseManicModel;

class ScanalatorAlias extends BaseManicModel
{
    //Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'scanalator_alias';
	
	public static function boot()
    {
        parent::boot();
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

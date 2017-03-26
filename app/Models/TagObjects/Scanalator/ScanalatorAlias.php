<?php

namespace App\Models\TagObjects\Scanalator;

use App\Models\BaseManicModel;

class ScanalatorAlias extends BaseManicModel
{
    //Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'scanalator_alias';
	
	/*
	 * Get the scanalator that the alias belongs to.
	 */
	public function scanalator()
	{
		return $this->belongsTo('App\ModelsTagObjects\Scanalator\Scanalator')->withTimestamps();
	}
}

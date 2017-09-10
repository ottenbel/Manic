<?php

namespace App\Models\Configuration;

use App\Models\BaseManicModel;

class ConfigurationPlaceholder extends BaseManicModel
{
	//Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'configuration_placeholder';
	
	public static function boot()
    {
        parent::boot();
    }
	
	public function user()
	{
		return $this->belongsTo('App\Models\User');
	}
}

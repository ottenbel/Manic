<?php

namespace App\Models;

use App\Models\BaseManicConfigurationModel;

class ConfigurationPagination extends BaseManicConfigurationModel
{
	//Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'configuration_pagination';
	
	public static function boot()
    {
        parent::boot();
    }
}

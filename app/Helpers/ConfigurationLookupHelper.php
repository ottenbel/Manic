<?php

namespace App\Helpers;

use App\Models\Configuration\ConfigurationPagination;
use App\Models\User;
use Auth;

class ConfigurationLookupHelper
{
	public static function LookupPaginationConfiguration($key)
	{
		if (Auth::check())
		{
			return ConfigurationPagination::where('key', '=', $key)->where('user_id', '=', Auth::user()->id)->first();
		}
		else
		{
			return ConfigurationPagination::where('key', '=', $key)->where('user_id', '=', null)->first();
		}	
	}
}

?>
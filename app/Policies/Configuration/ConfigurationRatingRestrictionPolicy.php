<?php

namespace App\Policies\Configuration;

use App\Models\User;
use App\Models\ConfigurationRatingRestriction;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConfigurationRatingRestrictionPolicy
{
    use HandlesAuthorization;

	 /**
     * Determine whether the user can update the configuration setting.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function update(User $user, bool $isSiteConfig)
    {
        if ($isSiteConfig)
		{
			return $user->hasPermissionTo('Edit Global Rating Restriction Settings');
		}
		else
		{ 
			return $user->hasPermissionTo('Edit Personal Rating Restriction Settings');
		}
    }

    /**
     * Determine whether the user can reset the configuration setting.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function reset(User $user)
    {
        return $user->hasPermissionTo('Edit Personal Rating Restriction Settings');
    }
}

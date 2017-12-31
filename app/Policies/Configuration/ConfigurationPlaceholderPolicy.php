<?php

namespace App\Policies\Configuration;

use App\Models\User;
use App\Models\ConfigurationPlaceholder;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConfigurationPlaceholderPolicy
{
    use HandlesAuthorization;

	 /**
     * Determine whether the user can update the placeholder settings.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function update(User $user, bool $isSiteConfig)
    {
        if ($isSiteConfig)
		{
			return $user->hasPermissionTo('Edit Global Placeholder Settings');
		}
		else
		{ 
			return $user->hasPermissionTo('Edit Personal Placeholder Settings');
		}
    }

    /**
     * Determine whether the user can reset the placeholder settings.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function reset(User $user)
    {
        return $user->hasPermissionTo('Edit Personal Placeholder Settings');
    }
}

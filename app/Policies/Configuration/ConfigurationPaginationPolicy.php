<?php

namespace App\Policies\Configuration;

use App\Models\User;
use App\Models\ConfigurationPagination;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConfigurationPaginationPolicy
{
    use HandlesAuthorization;

	 /**
     * Determine whether the user can update the pagination settings.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function update(User $user, bool $isSiteConfig)
    {
        if ($isSiteConfig)
		{
			return $user->hasPermissionTo('Edit Global Pagination Settings');
		}
		else
		{ 
			return $user->hasPermissionTo('Edit Personal Pagination Settings'); 
		}
    }

    /**
     * Determine whether the user can reset the pagination settings.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function reset(User $user)
    {
        return $user->hasPermissionTo('Edit Personal Pagination Settings');
    }
}

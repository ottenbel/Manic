<?php

namespace App\Policies\Configuration;

use App\Models\User;
use App\Models\ConfigurationPlaceholder;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConfigurationPlaceholderPolicy
{
    use HandlesAuthorization;

	 /**
     * Determine whether the user can update the chapter.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function update(User $user, bool $isSiteConfig)
    {
        if ($isSiteConfig)
		{
			if ($user->has_administrator_permission() || ($user->has_editor_permission()))
				{ return true; }
			else
				{ return false; }
		}
		else
			{ return true; }
    }

    /**
     * Determine whether the user can reset the chapter.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function reset(User $user)
    {
        return true;
    }
}

<?php

namespace App\Policies\TagObjects\Scanalator;

use App\Models\User;
use App\Models\TagObjects\Scanalator\ScanalatorAlias;
use Illuminate\Auth\Access\HandlesAuthorization;

class ScanalatorAliasPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the scanalator alias.
     *
     * @param  \App\User  $user
     * @param  \App\ScanalatorAlias  $scanalatorAlias
     * @return mixed
     */
    public function view(User $user, ScanalatorAlias $scanalatorAlias)
    {
		//Global tags are public so we won't use the can view check on those 
		if ($scanalatorAlias->user_id == $user->id)
		{
			return true;
		}
		else
		{
			return false;
		}
    }

    /**
     * Determine whether the user can create scanalator aliases.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user, $isGlobal)
    {
        if ($isGlobal)
		{
			return $user->hasPermissionTo('Create Global Scanalator Alias');
		}
		else
		{
			return $user->hasPermissionTo('Create Personal Scanalator Alias');
		}
    }

    /**
     * Determine whether the user can delete the scanalator alias.
     *
     * @param  \App\User  $user
     * @param  \App\ScanalatorAlias  $scanalatorAlias
     * @return mixed
     */
    public function delete(User $user, ScanalatorAlias $scanalatorAlias)
    {
        if ($scanalatorAlias->user_id == null)
		{
			return $user->hasPermissionTo('Delete Global Scanalator Alias');
		}
		else if ($scanalatorAlias->user_id == $user->id)
		{
			return $user->hasPermissionTo('Delete Personal Scanalator Alias');
		}
		else
		{
			return false;
		}
    }
}

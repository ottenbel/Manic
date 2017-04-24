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
		if (($scanalatorAlias->user_id == $user->id) || ($user->has_administrator_permission()))
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
			return $user->has_editor_permission();
		}
		else
		{
			return $user->has_user_permission();
		}
    }

    /**
     * Determine whether the user can delete the scanalator alias.
     *
     * @param  \App\User  $user
     * @param  \App\ScanalatorAlias  $scanalatorAlias
     * @return mixed
     */
    public function delete(User $user, ScanalatorAlias $scanalatorAlias, $isGlobal)
    {
        if ($scanalatorAlias->user_id == null)
		{
			return $user->has_editor_permission();
		}
		else if (($scanalatorAlias->user_id == $user->id) || ($user->has_administrator_permission()))
		{
			return true;
		}
		else
		{
			return false;
		}
    }
}

<?php

namespace App\Policies\TagObjects\Series;

use App\Models\User;
use App\Models\TagObjects\Series\SeriesAlias;
use Illuminate\Auth\Access\HandlesAuthorization;

class SeriesAliasPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the series alias.
     *
     * @param  \App\User  $user
     * @param  \App\SeriesAlias  $seriesAlias
     * @return mixed
     */
    public function view(User $user, SeriesAlias $seriesAlias)
    {
		//Global tags are public so we won't use the can view check on those 
		if ($seriesAlias->user_id == $user->id)
		{
			return true;
		}
		else
		{
			return false;
		}
    }

    /**
     * Determine whether the user can create series aliases.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user, $isGlobal)
    {
        if ($isGlobal)
		{
			return $user->hasPermissionTo('Create Global Series Alias');
		}
		else
		{
			return $user->hasPermissionTo('Create Personal Series Alias');
		}
    }

    /**
     * Determine whether the user can delete the series alias.
     *
     * @param  \App\User  $user
     * @param  \App\SeriesAlias  $seriesAlias
     * @return mixed
     */
    public function delete(User $user, SeriesAlias $seriesAlias)
    {
        if ($seriesAlias->user_id == null)
		{
			return $user->hasPermissionTo('Delete Global Series Alias');
		}
		else if ($seriesAlias->user_id == $user->id)
		{
			return $user->hasPermissionTo('Delete Personal Series Alias');
		}
		else
		{
			return false;
		}
    }
}

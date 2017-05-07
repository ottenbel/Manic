<?php

namespace App\Policies\TagObjects\Artist;

use App\Models\User;
use App\Models\TagObjects\Artist\ArtistAlias;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArtistAliasPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the artist alias.
     *
     * @param  \App\User  $user
     * @param  \App\ArtistAlias  $artistAlias
     * @return mixed
     */
    public function view(User $user, ArtistAlias $artistAlias)
    {
		//Global tags are public so we won't use the can view check on those 
		if ($artistAlias->user_id == $user->id)
		{
			return true;
		}
		else
		{
			return false;
		}
    }

    /**
     * Determine whether the user can create artist aliases.
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
     * Determine whether the user can delete the artist alias.
     *
     * @param  \App\User  $user
     * @param  \App\ArtistAlias  $artistAlias
     * @return mixed
     */
    public function delete(User $user, ArtistAlias $artistAlias)
    {
        if ($artistAlias->user_id == null)
		{
			return $user->has_editor_permission();
		}
		else if ($artistAlias->user_id == $user->id)
		{
			return true;
		}
		else
		{
			return false;
		}
    }
}

<?php

namespace App\Policies\TagObjects\Tag;

use App\Models\User;
use App\Models\TagObjects\Tag\TagAlias;
use Illuminate\Auth\Access\HandlesAuthorization;

class TagAliasPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the tag alias.
     *
     * @param  \App\User  $user
     * @param  \App\TagAlias  $tagAlias
     * @return mixed
     */
    public function view(User $user, TagAlias $tagAlias)
    {
		//Global tags are public so we won't use the can view check on those 
		if ($tagAlias->user_id == $user->id)
		{
			return true;
		}
		else
		{
			return false;
		}
    }

    /**
     * Determine whether the user can create tag aliases.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user, $isGlobal)
    {
        if ($isGlobal)
		{
			return $user->hasPermissionTo('Create Global Tag Alias');
		}
		else
		{
			return $user->hasPermissionTo('Create Personal Tag Alias');
		}
    }

    /**
     * Determine whether the user can delete the tag alias.
     *
     * @param  \App\User  $user
     * @param  \App\TagAlias  $tagAlias
     * @return mixed
     */
    public function delete(User $user, TagAlias $tagAlias)
    {
        if ($tagAlias->user_id == null)
		{
			return $user->hasPermissionTo('Delete Global Tag Alias');
		}
		else if ($tagAlias->user_id == $user->id)
		{
			return $user->hasPermissionTo('Delete Personal Tag Alias');
		}
		else
		{
			return false;
		}
    }
}

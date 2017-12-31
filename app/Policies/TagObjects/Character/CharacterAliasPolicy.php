<?php

namespace App\Policies\TagObjects\Character;

use App\Models\User;
use App\Models\TagObjects\Character\CharacterAlias;
use Illuminate\Auth\Access\HandlesAuthorization;

class CharacterAliasPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the character alias.
     *
     * @param  \App\User  $user
     * @param  \App\CharacterAlias  $characterAlias
     * @return mixed
     */
    public function view(User $user, CharacterAlias $characterAlias)
    {
		//Global tags are public so we won't use the can view check on those 
		if ($characterAlias->user_id == $user->id)
		{ 
			return true; 
		}
		else
		{ 
			return false; 
		}
    }

    /**
     * Determine whether the user can create character aliases.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user, $isGlobal)
    {
        if ($isGlobal)
		{
			return $user->hasPermissionTo('Create Global Character Alias');
		}
		else
		{
			return $user->hasPermissionTo('Create Personal Character Alias');
		}
    }

    /**
     * Determine whether the user can delete the character alias.
     *
     * @param  \App\User  $user
     * @param  \App\CharacterAlias  $characterAlias
     * @return mixed
     */
    public function delete(User $user, CharacterAlias $characterAlias)
    {
        if ($characterAlias->user_id == null)
		{
			return $user->hasPermissionTo('Delete Global Character Alias');
		}
		else if ($characterAlias->user_id == $user->id)
		{
			return $user->hasPermissionTo('Delete Personal Character Alias');
		}
		else
		{
			return false;
		}
    }
}

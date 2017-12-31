<?php

namespace App\Policies\TagObjects\Character;

use App\Models\User;
use App\Models\TagObjects\Character\Character;
use Illuminate\Auth\Access\HandlesAuthorization;

class CharacterPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the character.
     *
     * @param  \App\User  $user
     * @param  \App\Character  $character
     * @return mixed
     */
    /*public function view(User $user, Character $character)
    {
		//Currently there are no limits on what the user can view (tweak later as needed)
        return true;
    }*/

    /**
     * Determine whether the user can create characters.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('Create Character');
    }

	 /**
     * Determine whether the user can update the character.
     *
     * @param  \App\User  $user
     * @param  \App\Character  $character
     * @return mixed
     */
    public function update(User $user, Character $character)
    {
        return $user->hasPermissionTo('Edit Character');
    }

    /**
     * Determine whether the user can delete the character.
     *
     * @param  \App\User  $user
     * @param  \App\Character  $character
     * @return mixed
     */
    public function delete(User $user, Character $character)
    {
        return $user->hasPermissionTo('Delete Character');
    }
}

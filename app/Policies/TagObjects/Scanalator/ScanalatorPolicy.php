<?php

namespace App\Policies\TagObjects\Scanalator;

use App\Models\User;
use App\Models\TagObjects\Scanalator\Scanalator;
use Illuminate\Auth\Access\HandlesAuthorization;

class ScanalatorPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the scanalator.
     *
     * @param  \App\User  $user
     * @param  \App\Scanalator  $scanalator
     * @return mixed
     */
    /*public function view(User $user, Scanalator $scanalator)
    {
		//Currently there are no limits on what the user can view (tweak later as needed)
        return true;
    }*/

    /**
     * Determine whether the user can create scanalators.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('Create Scanalator');
    }

	 /**
     * Determine whether the user can update the scanalator.
     *
     * @param  \App\User  $user
     * @param  \App\Scanalator  $scanalator
     * @return mixed
     */
    public function update(User $user, Scanalator $scanalator)
    {
        return $user->hasPermissionTo('Edit Scanalator');
    }

    /**
     * Determine whether the user can delete the scanalator.
     *
     * @param  \App\User  $user
     * @param  \App\Scanalator  $scanalator
     * @return mixed
     */
    public function delete(User $user, Scanalator $scanalator)
    {
        return $user->hasPermissionTo('Delete Scanalator');
    }
}

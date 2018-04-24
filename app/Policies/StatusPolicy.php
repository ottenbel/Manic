<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Status;
use Illuminate\Auth\Access\HandlesAuthorization;

class StatusPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create statuss.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('Create Status');
    }

	 /**
     * Determine whether the user can update the status.
     *
     * @param  \App\User  $user
     * @param  \App\status  $status
     * @return mixed
     */
    public function update(User $user, Status $status)
    {
        return $user->hasPermissionTo('Edit Status');
    }

    /**
     * Determine whether the user can delete the status.
     *
     * @param  \App\User  $user
     * @param  \App\status  $status
     * @return mixed
     */
    public function delete(User $user, Status $status)
    {
        return ($user->hasPermissionTo('Delete Status') && $status->collections()->count() == 0);
    }
}

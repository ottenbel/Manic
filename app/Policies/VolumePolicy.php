<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Volume;
use Illuminate\Auth\Access\HandlesAuthorization;

class VolumePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the volume.
     *
     * @param  \App\User  $user
     * @param  \App\Volume  $volume
     * @return mixed
     */
    /*public function view(User $user, Volume $volume)
    {
		//Currently there are no limits on what the user can view (tweak later as needed)
        return true;
    }*/

    /**
     * Determine whether the user can create volumes.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->has_editor_permission();
    }

	 /**
     * Determine whether the user can update the volume.
     *
     * @param  \App\User  $user
     * @param  \App\Volume  $volume
     * @return mixed
     */
    public function update(User $user, Volume $volume)
    {
        return $user->has_editor_permission();
    }

    /**
     * Determine whether the user can delete the volume.
     *
     * @param  \App\User  $user
     * @param  \App\Volume  $volume
     * @return mixed
     */
    public function delete(User $user, Volume $volume)
    {
        return $user->has_editor_permission();
    }
	
	/**
     * Determine whether the user can export the volume.
     *
     * @param  \App\User  $user
     * @param  \App\Volume  $volume
     * @return mixed
     */
    public function export(User $user, Volume $volume)
    {
        return $user->has_user_permission();
    }
}

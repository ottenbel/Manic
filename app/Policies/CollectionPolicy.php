<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Collection\Collection;
use Illuminate\Auth\Access\HandlesAuthorization;

class CollectionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the collection.
     *
     * @param  \App\User  $user
     * @param  \App\Collection  $collection
     * @return mixed
     */
    /*public function view(User $user, Collection $collection)
    {
		//Currently there are no limits on what the user can view (tweak later as needed)
        return true;
    }*/

    /**
     * Determine whether the user can create collections.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('Create Collection');
    }

	 /**
     * Determine whether the user can update the collection.
     *
     * @param  \App\User  $user
     * @param  \App\Collection  $collection
     * @return mixed
     */
    public function update(User $user, Collection $collection)
    {
        return $user->hasPermissionTo('Edit Collection');
    }

    /**
     * Determine whether the user can delete the collection.
     *
     * @param  \App\User  $user
     * @param  \App\Collection  $collection
     * @return mixed
     */
    public function delete(User $user, Collection $collection)
    {
        return $user->hasPermissionTo('Delete Collection');
    }
	
	/**
     * Determine whether the user can export the volume.
     *
     * @param  \App\User  $user
     * @param  \App\Collection  $collection
     * @return mixed
     */
    public function export(User $user, Collection $collection)
    {
        return $user->hasPermissionTo('Export Collection');
    }
}

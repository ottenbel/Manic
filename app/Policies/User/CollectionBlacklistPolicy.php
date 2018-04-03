<?php

namespace App\Policies\User;

use App\Models\User;
use App\Models\User\CollectionBlacklist;
use Illuminate\Auth\Access\HandlesAuthorization;

class CollectionBlacklistPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can add favourite collections.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('Add Blacklisted Collection');
    }

    /**
     * Determine whether the user can delete a given favourite collections.
     *
     * @param  \App\User  $user
     * @param  \App\Chapter  $chapter
     * @return mixed
     */
    public function delete(User $user, CollectionFavourite $collectionFavourite)
    {
        return (($user->hasPermissionTo('Delete Blacklisted Collection')) && ($collectionFavourite->user->id == $user->id));
    }
}
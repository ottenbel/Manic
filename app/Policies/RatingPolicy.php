<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Rating;
use Illuminate\Auth\Access\HandlesAuthorization;

class RatingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create ratings.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('Create Rating');
    }

	 /**
     * Determine whether the user can update the ratings.
     *
     * @param  \App\User  $user
     * @param  \App\ratings  $ratings
     * @return mixed
     */
    public function update(User $user, Rating $rating)
    {
        return $user->hasPermissionTo('Edit Rating');
    }

    /**
     * Determine whether the user can delete the ratings.
     *
     * @param  \App\User  $user
     * @param  \App\ratings  $ratings
     * @return mixed
     */
    public function delete(User $user, Rating $rating)
    {
        return ($user->hasPermissionTo('Delete Rating') && $rating->collections()->count() == 0);
    }
}

<?php

namespace App\Policies\TagObjects\Artist;

use App\Models\User;
use App\Models\TagObjects\Artist\Artist;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArtistPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the artist.
     *
     * @param  \App\User  $user
     * @param  \App\Artist  $artist
     * @return mixed
     */
    /*public function view(User $user, Artist $artist)
    {
		//Currently there are no limits on what the user can view (tweak later as needed)
        return true;
    }*/

    /**
     * Determine whether the user can create artists.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('Create Artist');
    }

	 /**
     * Determine whether the user can update the artist.
     *
     * @param  \App\User  $user
     * @param  \App\Artist  $artist
     * @return mixed
     */
    public function update(User $user, Artist $artist)
    {
        return $user->hasPermissionTo('Edit Artist');
    }

    /**
     * Determine whether the user can delete the artist.
     *
     * @param  \App\User  $user
     * @param  \App\Artist  $artist
     * @return mixed
     */
    public function delete(User $user, Artist $artist)
    {
        return $user->hasPermissionTo('Delete Artist');
    }
}

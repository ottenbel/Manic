<?php

namespace App\Policies\TagObjects\Series;

use App\Models\User;
use App\Models\TagObjects\Series\Series;
use Illuminate\Auth\Access\HandlesAuthorization;

class SeriesPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the series.
     *
     * @param  \App\User  $user
     * @param  \App\Series  $series
     * @return mixed
     */
    /*public function view(User $user, Series $series)
    {
		//Currently there are no limits on what the user can view (tweak later as needed)
        return true;
    }*/

    /**
     * Determine whether the user can create seriess.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('Create Series');
    }

	 /**
     * Determine whether the user can update the series.
     *
     * @param  \App\User  $user
     * @param  \App\Series  $series
     * @return mixed
     */
    public function update(User $user, Series $series)
    {
        return $user->hasPermissionTo('Edit Series');
    }

    /**
     * Determine whether the user can delete the series.
     *
     * @param  \App\User  $user
     * @param  \App\Series  $series
     * @return mixed
     */
    public function delete(User $user, Series $series)
    {
		return (($user->hasPermissionTo('Delete Series')) && ($series->children()->count() == 0) 
			&& ($series->characters()->count() == 0));
    }
}

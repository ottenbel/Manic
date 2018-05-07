<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Chapter\Chapter;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChapterPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the chapter.
     *
     * @param  \App\User  $user
     * @param  \App\Chapter  $chapter
     * @return mixed
     */
    /*public function view(User $user, Chapter $chapter)
    {
		//Currently there are no limits on what the user can view (tweak later as needed)
        return true;
    }*/

    /**
     * Determine whether the user can create chapters.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('Create Chapter');
    }

	 /**
     * Determine whether the user can update the chapter.
     *
     * @param  \App\User  $user
     * @param  \App\Chapter  $chapter
     * @return mixed
     */
    public function update(User $user, Chapter $chapter)
    {
        return $user->hasPermissionTo('Edit Chapter');
    }

    /**
     * Determine whether the user can delete the chapter.
     *
     * @param  \App\User  $user
     * @param  \App\Chapter  $chapter
     * @return mixed
     */
    public function delete(User $user, Chapter $chapter)
    {
        return $user->hasPermissionTo('Delete Chapter');
    }
	
	/**
     * Determine whether the user can export the chapter.
     *
     * @param  \App\User  $user
     * @param  \App\Chapter  $chapter
     * @return mixed
     */
    public function export(User $user, Chapter $chapter)
    {
        return $user->hasPermissionTo('Export Chapter');
    }
}

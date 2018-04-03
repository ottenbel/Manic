<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Language;
use Illuminate\Auth\Access\HandlesAuthorization;

class LanguagePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create languages.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('Create Language');
    }

	 /**
     * Determine whether the user can update the language.
     *
     * @param  \App\User  $user
     * @param  \App\Language  $language
     * @return mixed
     */
    public function update(User $user, Language $language)
    {
        return $user->hasPermissionTo('Edit Language');
    }

    /**
     * Determine whether the user can delete the language.
     *
     * @param  \App\User  $user
     * @param  \App\Language  $language
     * @return mixed
     */
    public function delete(User $user, Language $language)
    {
        return ($user->hasPermissionTo('Delete Language') && $language->collections()->count() == 0);
    }
}

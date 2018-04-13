<?php

namespace App\Providers\Custom;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use App\Models\User\CollectionFavourite;
use App\Observers\User\CollectionFavouritesObserver;
use App\Models\User\CollectionBlacklist;
use App\Observers\User\CollectionBlacklistObserver;

class UserFavouriteBlacklistServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\User\CollectionFavourite' => 'App\Policies\User\CollectionFavouritesPolicy',
        'App\Models\User\CollectionBlacklist' => 'App\Policies\User\CollectionBlacklistPolicy'
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        CollectionFavourite::observe(CollectionFavouritesObserver::class);
        CollectionBlacklist::observe(CollectionBlacklistObserver::class);
    }
}

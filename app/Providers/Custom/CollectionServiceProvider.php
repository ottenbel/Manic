<?php

namespace App\Providers\Custom;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use App\Models\Collection;
use App\Observers\CollectionObserver;

class CollectionServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = ['App\Models\Collection' => 'App\Policies\CollectionPolicy'];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Collection::observe(CollectionObserver::class);
    }
}

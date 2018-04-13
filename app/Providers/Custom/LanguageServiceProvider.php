<?php

namespace App\Providers\Custom;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use App\Models\Language;
use App\Observers\LanguageObserver;

class LanguageServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = ['App\Models\Language' => 'App\Policies\LanguagePolicy'];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Language::observe(LanguageObserver::class);
    }
}

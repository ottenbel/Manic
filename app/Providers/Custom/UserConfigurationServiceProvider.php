<?php

namespace App\Providers\Custom;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use App\Models\Configuration\ConfigurationPagination;
use App\Observers\Configuration\Pagination\PaginationObserver;
use App\Models\Configuration\ConfigurationPlaceholder;
use App\Observers\Configuration\Placeholder\PlaceholderObserver;
use App\Models\Configuration\ConfigurationRatingRestriction;
use App\Observers\Configuration\RatingRestriction\RatingRestrictionObserver;

class UserConfigurationServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Configuration\ConfigurationPagination' => 'App\Policies\Configuration\ConfigurationPaginationPolicy',
        'App\Models\Configuration\ConfigurationPlaceholder' => 'App\Policies\Configuration\ConfigurationPlaceholderPolicy',
        'App\Models\Configuration\ConfigurationRatingRestriction' => 'App\Policies\Configuration\ConfigurationRatingRestrictionPolicy'
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        ConfigurationPagination::observe(PaginationObserver::class);
        ConfigurationPlaceholder::observe(PlaceholderObserver::class);
        ConfigurationRatingRestriction::observe(RatingRestrictionObserver::class);
    }
}

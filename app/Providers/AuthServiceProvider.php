<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Collection' => 'App\Policies\CollectionPolicy',
		'App\Models\Volume' => 'App\Policies\VolumePolicy',
		'App\Models\Chapter' => 'App\Policies\ChapterPolicy',
		'App\Models\TagObjects\Artist\Artist' => 'App\Policies\TagObjects\Artist\ArtistPolicy',
		'App\Models\TagObjects\Character\Character' => 'App\Policies\TagObjects\Character\CharacterPolicy',
		'App\Models\TagObjects\Scanalator\Scanalator' => 'App\Policies\TagObjects\Scanalator\ScanalatorPolicy',
		'App\Models\TagObjects\Series\Series' => 'App\Policies\TagObjects\Series\SeriesPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}

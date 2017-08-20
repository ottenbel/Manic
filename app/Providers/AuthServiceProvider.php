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
		'App\Models\TagObjects\Tag\Tag' => 'App\Policies\TagObjects\Tag\TagPolicy',
		'App\Models\TagObjects\Artist\ArtistAlias' => 'App\Policies\TagObjects\Artist\ArtistAliasPolicy',
		'App\Models\TagObjects\Character\CharacterAlias' => 'App\Policies\TagObjects\Character\CharacterAliasPolicy',
		'App\Models\TagObjects\Scanalator\ScanalatorAlias' => 'App\Policies\TagObjects\Scanalator\ScanalatorAliasPolicy',
		'App\Models\TagObjects\Series\SeriesAlias' => 'App\Policies\TagObjects\Series\SeriesAliasPolicy',
		'App\Models\TagObjects\Tag\TagAlias' => 'App\Policies\TagObjects\Tag\TagAliasPolicy',
		'App\Models\Configuration\ConfigurationPagination' => 'App\Policies\Configuration\ConfigurationPaginationPolicy',
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

<?php

namespace App\Providers\Custom;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use App\Models\TagObjects\Artist\Artist;
use App\Observers\TagObjects\Artist\ArtistObserver;
use App\Models\TagObjects\Artist\ArtistAlias;
use App\Observers\TagObjects\Artist\ArtistAliasObserver;
use App\Models\TagObjects\Character\Character;
use App\Observers\TagObjects\Character\CharacterObserver;
use App\Models\TagObjects\Character\CharacterAlias;
use App\Observers\TagObjects\Character\CharacterAliasObserver;
use App\Models\TagObjects\Scanalator\Scanalator;
use App\Observers\TagObjects\Scanalator\ScanalatorObserver;
use App\Models\TagObjects\Scanalator\ScanalatorAlias;
use App\Observers\TagObjects\Scanalator\ScanalatorAliasObserver;
use App\Models\TagObjects\Series\Series;
use App\Observers\TagObjects\Series\SeriesObserver;
use App\Models\TagObjects\Series\SeriesAlias;
use App\Observers\TagObjects\Series\SeriesAliasObserver;
use App\Models\TagObjects\Tag\Tag;
use App\Observers\TagObjects\Tag\TagObserver;
use App\Models\TagObjects\Tag\TagAlias;
use App\Observers\TagObjects\Tag\TagAliasObserver;

class TagObjectsServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\TagObjects\Artist\Artist' => 'App\Policies\TagObjects\Artist\ArtistPolicy',
        'App\Models\TagObjects\Character\Character' => 'App\Policies\TagObjects\Character\CharacterPolicy',
        'App\Models\TagObjects\Scanalator\Scanalator' => 'App\Policies\TagObjects\Scanalator\ScanalatorPolicy',
        'App\Models\TagObjects\Series\Series' => 'App\Policies\TagObjects\Series\SeriesPolicy',
        'App\Models\TagObjects\Tag\Tag' => 'App\Policies\TagObjects\Tag\TagPolicy',
        'App\Models\TagObjects\Artist\ArtistAlias' => 'App\Policies\TagObjects\Artist\ArtistAliasPolicy',
        'App\Models\TagObjects\Character\CharacterAlias' => 'App\Policies\TagObjects\Character\CharacterAliasPolicy',
        'App\Models\TagObjects\Scanalator\ScanalatorAlias' => 'App\Policies\TagObjects\Scanalator\ScanalatorAliasPolicy',
        'App\Models\TagObjects\Series\SeriesAlias' => 'App\Policies\TagObjects\Series\SeriesAliasPolicy',
        'App\Models\TagObjects\Tag\TagAlias' => 'App\Policies\TagObjects\Tag\TagAliasPolicy'
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Artist::observe(ArtistObserver::class);
        Character::observe(CharacterObserver::class);
        Scanalator::observe(ScanalatorObserver::class);
        Series::observe(SeriesObserver::class);
        Tag::observe(TagObserver::class);

        ArtistAlias::observe(ArtistAliasObserver::class);
        CharacterAlias::observe(CharacterAliasObserver::class);
        ScanalatorAlias::observe(ScanalatorAliasObserver::class);
        SeriesAlias::observe(SeriesAliasObserver::class);
        TagAlias::observe(TagAliasObserver::class);
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Chapter;
use App\Observers\ChapterObserver;
use App\Models\Volume;
use App\Observers\VolumeObserver;
use App\Models\Collection;
use App\Observers\CollectionObserver;
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

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //Register observers
		Chapter::observe(ChapterObserver::class);
		Volume::observe(VolumeObserver::class);
		Collection::observe(CollectionObserver::class);
		Artist::observe(ArtistObserver::class);
		ArtistAlias::observe(ArtistAliasObserver::class);
		Character::observe(CharacterObserver::class);
		CharacterAlias::observe(CharacterAliasObserver::class);
		Scanalator::observe(ScanalatorObserver::class);
		ScanalatorAlias::observe(ScanalatorAliasObserver::class);
		Series::observe(SeriesObserver::class);
		SeriesAlias::observe(SeriesAliasObserver::class);
		Tag::observe(TagObserver::class);
		TagAlias::observe(TagAliasObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

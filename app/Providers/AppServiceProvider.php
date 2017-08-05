<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Chapter;
use App\Observers\ChapterObserver;
use App\Models\Volume;
use App\Observers\VolumeObserver;

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

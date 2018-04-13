<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Auth;
use Log;

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
		

		/**
		 * Add additional context information to all logs.
		 */
		$monolog = Log::getLogger();
		$monolog->pushProcessor(function ($record) 
		{
			$user = "Unknown";
			if (Auth::check()) { $user = Auth::user()->id; }

		    $record['extra']['user'] = $user;
		    $record['extra']['session'] = session()->getId();
		    $record['extra']['ip_address'] = request()->ip();

		    return $record;
		});
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

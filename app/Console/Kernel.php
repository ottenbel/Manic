<?php

namespace App\Console;

use Storage;
use App\Models\Image;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
		
		/*
		 * Clean up the filesystem (delete any images that aren't linked to anything)
		 */
		$schedule->call(function(){
			//Retrieve all images
			$images = Image::all();
			
			foreach ($images as $image)
			{
				if (($image->collections()->count() == 0) && ($image->volumes()->count() == 0)
					&& ($image->chapters()->count() == 0))
					{
						//Delete fullsized image
						Storage::delete($image->name);
						
						//Delete thumbnail
						Storage::delete($image->thumbnail);
						
						//Hard delete image
						$image->forceDelete();
					}
			}
		})->weekly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}

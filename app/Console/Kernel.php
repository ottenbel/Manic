<?php

namespace App\Console;

use DateTime;
use Storage;
use App\Models\Image;
use App\Models\Chapter\ChapterExport;
use App\Models\Volume\VolumeExport;
use App\Models\Collection\CollectionExport;
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
		'App\Console\Commands\GenerateFolders'
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
		$schedule->call(function()
		{
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
		
		/*
		 * Clean up the filesystem (zip files for chapters that haven't been downloaded in the last week)
		 */
		$schedule->call(function()
		{
			//Retrieve all chapters with files on disk for exporting
			$filesForExport = ChapterExport::all();
			$deleteCutOff = new DateTime;
			date_sub($deleteCutOff, date_interval_create_from_date_string('1 week'));
			foreach ($filesForExport as $fileForExport)
			{
				if ($fileForExport->last_downloaded < $deleteCutOff)
				{
					Storage::Delete($fileForExport->path);
					$fileForExport->forceDelete();
				}
			}
		})->daily();
		
		/*
		 * Clean up the filesystem (zip files for volumes that haven't been downloaded in the last week)
		 */
		$schedule->call(function()
		{
			//Retrieve all volumes with files on disk for exporting
			$filesForExport = VolumeExport::all();
			$deleteCutOff = new DateTime;
			date_sub($deleteCutOff, date_interval_create_from_date_string('1 week'));
			foreach ($filesForExport as $fileForExport)
			{
				if ($fileForExport->last_downloaded < $deleteCutOff)
				{
					Storage::Delete($fileForExport->path);
					$fileForExport->forceDelete();
				}
			}
		})->daily();
		
		/*
		 * Clean up the filesystem (zip files for collections that haven't been downloaded in the last week)
		 */
		$schedule->call(function()
		{
			//Retrieve all collections with files on disk for exporting
			$filesForExport = CollectionExport::all();
			$deleteCutOff = new DateTime;
			date_sub($deleteCutOff, date_interval_create_from_date_string('1 week'));
			foreach ($filesForExport as $fileForExport)
			{
				if ($fileForExport->last_downloaded < $deleteCutOff)
				{
					Storage::Delete($fileForExport->path);
					$fileForExport->forceDelete();
				}
			}
		})->daily();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
		$this->load(__DIR__.'/Commands');
    }
}

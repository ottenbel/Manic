<?php

namespace App\Observers\TagObjects\Series;

use App\Models\TagObjects\Series\Series;
use App\Observers\BaseManicModelObserver;
use Auth;
use Log;

class SeriesObserver Extends BaseManicModelObserver
{
	/**
     * Listen to the series creating event.
     *
     * @param  $series
     * @return void
     */
    public function creating($series)
    {	
		parent::creating($series);

        Log::Debug("Creating series", ['series' => $series->id]);
    }
	
    /**
     * Listen to the series created event.
     *
     * @param  $series
     * @return void
     */
    public function created($series)
    {
        parent::created($series);

        Log::Info("Created series", ['series' => $series->id]);
    }

	/**
     * Listen to the series updating event.
     *
     * @param  $series
     * @return void
     */
    public function updating($series)
    {
        parent::updating($series);

        Log::Debug("Updating series", ['series' => $series->id]);
    }
	
    /**
     * Listen to the series updated event.
     *
     * @param  $series
     * @return void
     */
    public function updated($series)
    {
        parent::updated($series);

        Log::Info("Updated series", ['series' => $series->id]);
    }
	
	/**
     * Listen to the series saving event.
     *
     * @param  $series
     * @return void
     */
    public function saving($series)
    {
        parent::saving($series);

        Log::Debug("Saving series", ['series' => $series->id]);
    }
	
    /**
     * Listen to the series saved event.
     *
     * @param  $series
     * @return void
     */
    public function saved($series)
    {
        parent::saved($series);

        Log::Debug("Saved series", ['series' => $series->id]);
    }
	
    /**
     * Listen to the series deleting event.
     *
     * @param  $series
     * @return void
     */
    public function deleting($series)
    {
        parent::deleting($series);

        Log::Debug("Deleting series", ['series' => $series->id]);
    }
	
	/**
     * Listen to the series deleted event.
     *
     * @param  $series
     * @return void
     */
    public function deleted($series)
    {
        parent::deleted($series);

        Log::Info("Deleted series", ['series' => $series->id]);
    }
	
	/**
     * Listen to the series restoring event.
     *
     * @param  $series
     * @return void
     */
    public function restoring($series)
    {
        parent::restoring($series);

        Log::Debug("Restoring series", ['series' => $series->id]);
    }
	
	/**
     * Listen to the series restored event.
     *
     * @param  series  $series
     * @return void
     */
    public function restored($series)
    {
        parent::restored($series);

        Log::Info("Restored series", ['series' => $series->id]);
    }
}
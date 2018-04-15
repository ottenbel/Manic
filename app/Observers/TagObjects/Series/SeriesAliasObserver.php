<?php

namespace App\Observers\TagObjects\Series;

use App\Models\TagObjects\Series\SeriesAlias;
use App\Observers\BaseManicModelObserver;
use Auth;
use Log;

class SeriesAliasObserver Extends BaseManicModelObserver
{
	/**
     * Listen to the series alias creating event.
     *
     * @param  $seriesAlias
     * @return void
     */
    public function creating($seriesAlias)
    {	
		parent::creating($seriesAlias);
		
		$series = $seriesAlias->series;

        Log::Debug("Creating series alias", ['series alias' => $seriesAlias->id, 'series' => $series->id]);

		$series->updated_by = Auth::user()->id;
		$series->save();
		$series->touch();
    }
	
    /**
     * Listen to the series alias created event.
     *
     * @param  $seriesAlias
     * @return void
     */
    public function created($seriesAlias)
    {
        parent::created($seriesAlias);

        Log::Info("Created series alias", ['series alias' => $seriesAlias->id]);
    }

	/**
     * Listen to the series alias updating event.
     *
     * @param  $seriesAlias
     * @return void
     */
    public function updating($seriesAlias)
    {
        parent::updating($seriesAlias);

        Log::Debug("Updating series alias", ['series alias' => $seriesAlias->id]);
    }
	
    /**
     * Listen to the series alias updated event.
     *
     * @param  $seriesAlias
     * @return void
     */
    public function updated($seriesAlias)
    {
        parent::updated($seriesAlias);

        Log::Info("Updated series alias", ['series alias' => $seriesAlias->id]);
    }
	
	/**
     * Listen to the series alias saving event.
     *
     * @param  $seriesAlias
     * @return void
     */
    public function saving($seriesAlias)
    {
        parent::saving($seriesAlias);

        Log::Debug("Saving series alias", ['series alias' => $seriesAlias->id]);
    }
	
    /**
     * Listen to the series alias saved event.
     *
     * @param  $seriesAlias
     * @return void
     */
    public function saved($seriesAlias)
    {
        parent::saved($seriesAlias);

        Log::Debug("Saved series alias", ['series alias' => $seriesAlias->id]);
    }
	
    /**
     * Listen to the series alias deleting event.
     *
     * @param  $seriesAlias
     * @return void
     */
    public function deleting($seriesAlias)
    {
        parent::deleting($seriesAlias);
		
        Log::Debug("Deleting series alias", ['series alias' => $seriesAlias->id]);

		$series = $seriesAlias->series;
		$series->updated_by = Auth::user()->id;
		$series->save();
		$series->touch();
    }
	
	/**
     * Listen to the series alias deleted event.
     *
     * @param  $seriesAlias
     * @return void
     */
    public function deleted($seriesAlias)
    {
        parent::deleted($seriesAlias);

        Log::Info("Deleted series alias", ['series alias' => $seriesAlias->id]);
    }
	
	/**
     * Listen to the series alias restoring event.
     *
     * @param  $seriesAlias
     * @return void
     */
    public function restoring($seriesAlias)
    {
        parent::restoring($seriesAlias);

        Log::Debug("Restoring series alias", ['series alias' => $seriesAlias->id]);
    }
	
	/**
     * Listen to the series alias restored event.
     *
     * @param  series alias  $seriesAlias
     * @return void
     */
    public function restored($seriesAlias)
    {
        parent::restored($seriesAlias);

        Log::Info("Restored series alias", ['series alias' => $seriesAlias->id]);
    }
}
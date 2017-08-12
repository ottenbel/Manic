<?php

namespace App\Observers\TagObjects\Series;

use App\Models\TagObjects\Series\SeriesAlias;
use App\Observers\BaseManicModelObserver;
use Auth;

class SeriesAliasObserver Extends BaseManicModelObserver
{
	/**
     * Listen to the series alias creating event.
     *
     * @param  $model
     * @return void
     */
    public function creating($model)
    {	
		parent::creating($model);
		
		$series = $model->series()->first();
		$series->updated_by = Auth::user()->id;
		$series->save();
		$series->touch();
    }
	
    /**
     * Listen to the series alias created event.
     *
     * @param  $model
     * @return void
     */
    public function created($model)
    {
        parent::created($model);
    }

	/**
     * Listen to the series alias updating event.
     *
     * @param  $model
     * @return void
     */
    public function updating($model)
    {
        parent::updating($model);
    }
	
    /**
     * Listen to the series alias updated event.
     *
     * @param  $model
     * @return void
     */
    public function updated($model)
    {
        parent::updated($model);
    }
	
	/**
     * Listen to the series alias saving event.
     *
     * @param  $model
     * @return void
     */
    public function saving($model)
    {
        parent::saving($model);
    }
	
    /**
     * Listen to the series alias saved event.
     *
     * @param  $model
     * @return void
     */
    public function saved($model)
    {
        parent::saved($model);
    }
	
    /**
     * Listen to the series alias deleting event.
     *
     * @param  $model
     * @return void
     */
    public function deleting($model)
    {
        parent::deleting($model);
		
		$series = $model->series()->first();
		$series->updated_by = Auth::user()->id;
		$series->save();
		$series->touch();
    }
	
	/**
     * Listen to the series alias deleted event.
     *
     * @param  $model
     * @return void
     */
    public function deleted($model)
    {
        parent::deleted($model);
    }
	
	/**
     * Listen to the series alias restoring event.
     *
     * @param  $model
     * @return void
     */
    public function restoring($model)
    {
        parent::restoring($model);
    }
	
	/**
     * Listen to the series alias restored event.
     *
     * @param  series alias  $model
     * @return void
     */
    public function restored($model)
    {
        parent::restored($model);
    }
}
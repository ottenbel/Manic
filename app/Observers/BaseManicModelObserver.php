<?php

namespace App\Observers;

use Auth;
use Storage;

class BaseManicModelObserver
{
	/**
     * Listen to the base manic model creating event.
     *
     * @param  BaseManicModel  $model
     * @return void
     */
    public function creating($model)
    {
        $user = Auth::user();
		if ($user)
		{
			$model->created_by = $user->id;
			$model->updated_by = $user->id;
		}
    }
	
    /**
     * Listen to the base manic model created event.
     *
     * @param  BaseManicModel  $model
     * @return void
     */
    public function created($model)
    {
        //
    }

	/**
     * Listen to the base manic model updating event.
     *
     * @param  BaseManicModel  $model
     * @return void
     */
    public function updating($model)
    {
        $user = Auth::user();
		if ($user)
		{
			$model->updated_by = $user->id;
		}
    }
	
    /**
     * Listen to the base manic model updated event.
     *
     * @param  BaseManicModel  $model
     * @return void
     */
    public function updated($model)
    {
        //
    }
	
	/**
     * Listen to the base manic model saving event.
     *
     * @param  BaseManicModel  $model
     * @return void
     */
    public function saving($model)
    {
        //
    }
	
    /**
     * Listen to the base manic model saved event.
     *
     * @param  BaseManicModel  $model
     * @return void
     */
    public function saved($model)
    {
        //
    }
	
    /**
     * Listen to the base manic model deleting event.
     *
     * @param  BaseManicModel  $model
     * @return void
     */
    public function deleting($model)
    {
        $user = Auth::user();
		if ($user)
		{
			$model->updated_by = $user->id;
		}
    }
	
	/**
     * Listen to the base manic model deleted event.
     *
     * @param  BaseManicModel  $model
     * @return void
     */
    public function deleted($model)
    {
        //
    }
	
	/**
     * Listen to the base manic model restoring event.
     *
     * @param  BaseManicModel  $model
     * @return void
     */
    public function restoring($model)
    {
        //
    }
	
	/**
     * Listen to the base manic model restored event.
     *
     * @param  BaseManicModel  $model
     * @return void
     */
    public function restored($model)
    {
        //
    }
}
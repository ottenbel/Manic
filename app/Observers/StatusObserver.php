<?php

namespace App\Observers;

use App\Models\Status;
use Log;

class StatusObserver Extends BaseManicModelObserver
{
	/**
     * Listen to the status creating event.
     *
     * @param  $status
     * @return void
     */
    public function creating($status)
    {	
		parent::creating($status);

        Log::Debug("Creating status", ['status' => $status->id]);
    }
	
    /**
     * Listen to the status created event.
     *
     * @param  $status
     * @return void
     */
    public function created($status)
    {
        parent::created($status);

        Log::Info("Created status", ['status' => $status->id]);
    }

	/**
     * Listen to the status updating event.
     *
     * @param  $status
     * @return void
     */
    public function updating($status)
    {
        parent::updating($status);

        Log::Debug("Updating status", ['status' => $status->id]);
    }
	
    /**
     * Listen to the status updated event.
     *
     * @param  $status
     * @return void
     */
    public function updated($status)
    {
        parent::updated($status);

        Log::Info("Updated status", ['status' => $status->id]);
    }
	
	/**
     * Listen to the status saving event.
     *
     * @param  $status
     * @return void
     */
    public function saving($status)
    {
        parent::saving($status);

        Log::Debug("Saving status", ['status' => $status->id]);
    }
	
    /**
     * Listen to the status saved event.
     *
     * @param  $status
     * @return void
     */
    public function saved($status)
    {
        parent::saved($status);

        Log::Debug("Saved status", ['status' => $status->id]);
    }
	
    /**
     * Listen to the status deleting event.
     *
     * @param  $status
     * @return void
     */
    public function deleting($status)
    {
        parent::deleting($status);

        Log::Debug("Deleting status", ['status' => $status->id]);
    }
	
	/**
     * Listen to the status deleted event.
     *
     * @param  $status
     * @return void
     */
    public function deleted($status)
    {
        parent::deleted($status);

        Log::Info("Deleted status", ['status' => $status->id]);
    }
	
	/**
     * Listen to the status restoring event.
     *
     * @param  $status
     * @return void
     */
    public function restoring($status)
    {
        parent::restoring($status);

        Log::Debug("Restoring status", ['status' => $status->id]);
    }
	
	/**
     * Listen to the status restored event.
     *
     * @param  status  $status
     * @return void
     */
    public function restored($status)
    {
        parent::restored($status);

        Log::Info("Restored status", ['status' => $status->id]);
    }
}
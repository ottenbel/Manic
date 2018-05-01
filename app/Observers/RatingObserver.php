<?php

namespace App\Observers;

use App\Models\Rating;
use Log;

class RatingObserver Extends BaseManicModelObserver
{
	/**
     * Listen to the rating creating event.
     *
     * @param  $rating
     * @return void
     */
    public function creating($rating)
    {	
		parent::creating($rating);

        Log::Debug("Creating rating", ['rating' => $rating->id]);
    }
	
    /**
     * Listen to the rating created event.
     *
     * @param  $rating
     * @return void
     */
    public function created($rating)
    {
        parent::created($rating);

        Log::Info("Created rating", ['rating' => $rating->id]);
    }

	/**
     * Listen to the rating updating event.
     *
     * @param  $rating
     * @return void
     */
    public function updating($rating)
    {
        parent::updating($rating);

        Log::Debug("Updating rating", ['rating' => $rating->id]);
    }
	
    /**
     * Listen to the rating updated event.
     *
     * @param  $rating
     * @return void
     */
    public function updated($rating)
    {
        parent::updated($rating);

        Log::Info("Updated rating", ['rating' => $rating->id]);
    }
	
	/**
     * Listen to the rating saving event.
     *
     * @param  $rating
     * @return void
     */
    public function saving($rating)
    {
        parent::saving($rating);

        Log::Debug("Saving rating", ['rating' => $rating->id]);
    }
	
    /**
     * Listen to the rating saved event.
     *
     * @param  $rating
     * @return void
     */
    public function saved($rating)
    {
        parent::saved($rating);

        Log::Debug("Saved rating", ['rating' => $rating->id]);
    }
	
    /**
     * Listen to the rating deleting event.
     *
     * @param  $rating
     * @return void
     */
    public function deleting($rating)
    {
        parent::deleting($rating);

        Log::Debug("Deleting rating", ['rating' => $rating->id]);
    }
	
	/**
     * Listen to the rating deleted event.
     *
     * @param  $rating
     * @return void
     */
    public function deleted($rating)
    {
        parent::deleted($rating);

        Log::Info("Deleted rating", ['rating' => $rating->id]);
    }
	
	/**
     * Listen to the rating restoring event.
     *
     * @param  $rating
     * @return void
     */
    public function restoring($rating)
    {
        parent::restoring($rating);

        Log::Debug("Restoring rating", ['rating' => $rating->id]);
    }
	
	/**
     * Listen to the rating restored event.
     *
     * @param  rating  $rating
     * @return void
     */
    public function restored($rating)
    {
        parent::restored($rating);

        Log::Info("Restored rating", ['rating' => $rating->id]);
    }
}
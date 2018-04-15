<?php

namespace App\Observers\Configuration\RatingRestriction;

use App\Models\Configuration\ConfigurationRatingRestriction;
use App\Observers\BaseManicModelObserver;
use Log;

class RatingRestrictionObserver Extends BaseManicModelObserver
{
	/**
     * Listen to the rating restriction creating event.
     *
     * @param  $configurationRatingRestriction
     * @return void
     */
    public function creating($configurationRatingRestriction)
    {	
		parent::creating($configurationRatingRestriction);

        Log::Debug("Creating rating restriction configuration entry", ['placeholder_configuration' => $configurationRatingRestriction->id]);
    }
	
    /**
     * Listen to the rating restriction created event.
     *
     * @param  $configurationRatingRestriction
     * @return void
     */
    public function created($configurationRatingRestriction)
    {
        parent::created($configurationRatingRestriction);

        Log::Info("Created rating restriction configuration entry", ['placeholder_configuration' => $configurationRatingRestriction->id]);
    }

	/**
     * Listen to the rating restriction updating event.
     *
     * @param  $configurationRatingRestriction
     * @return void
     */
    public function updating($configurationRatingRestriction)
    {
        parent::updating($configurationRatingRestriction);

        Log::Debug("Updating rating restriction configuration entry", ['placeholder_configuration' => $configurationRatingRestriction->id]);
    }
	
    /**
     * Listen to the rating restriction updated event.
     *
     * @param  $configurationRatingRestriction
     * @return void
     */
    public function updated($configurationRatingRestriction)
    {
        parent::updated($configurationRatingRestriction);

        Log::Info("Updated rating restriction configuration entry", ['placeholder_configuration' => $configurationRatingRestriction->id]);
    }
	
	/**
     * Listen to the rating restriction saving event.
     *
     * @param  $configurationRatingRestriction
     * @return void
     */
    public function saving($configurationRatingRestriction)
    {
        parent::saving($configurationRatingRestriction);

        Log::Debug("Saving rating restriction configuration entry", ['placeholder_configuration' => $configurationRatingRestriction->id]);
    }
	
    /**
     * Listen to the rating restriction saved event.
     *
     * @param  $configurationRatingRestriction
     * @return void
     */
    public function saved($configurationRatingRestriction)
    {
        parent::saved($configurationRatingRestriction);

        Log::Debug("Saved rating restriction configuration entry", ['placeholder_configuration' => $configurationRatingRestriction->id]);
    }
	
    /**
     * Listen to the rating restriction deleting event.
     *
     * @param  $configurationRatingRestriction
     * @return void
     */
    public function deleting($configurationRatingRestriction)
    {
        parent::deleting($configurationRatingRestriction);

        Log::Debug("Deleting rating restriction configuration entry", ['placeholder_configuration' => $configurationRatingRestriction->id]);
    }
	
	/**
     * Listen to the rating restriction deleted event.
     *
     * @param  $configurationRatingRestriction
     * @return void
     */
    public function deleted($configurationRatingRestriction)
    {
        parent::deleted($configurationRatingRestriction);

        Log::Info("Deleted rating restriction configuration entry", ['placeholder_configuration' => $configurationRatingRestriction->id]);
    }
	
	/**
     * Listen to the rating restriction restoring event.
     *
     * @param  $configurationRatingRestriction
     * @return void
     */
    public function restoring($configurationRatingRestriction)
    {
        parent::restoring($configurationRatingRestriction);

        Log::Debug("Restoring rating restriction configuration entry", ['placeholder_configuration' => $configurationRatingRestriction->id]);
    }
	
	/**
     * Listen to the rating restriction restored event.
     *
     * @param  rating restriction  $configurationRatingRestriction
     * @return void
     */
    public function restored($configurationRatingRestriction)
    {
        parent::restored($configurationRatingRestriction);

        Log::Info("Restored rating restriction configuration entry", ['placeholder_configuration' => $configurationRatingRestriction->id]);
    }
}
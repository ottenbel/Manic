<?php

namespace App\Observers\Configuration\Placeholder;

use App\Models\Configuration\ConfigurationPlaceholder;
use App\Observers\BaseManicModelObserver;
use Log;

class PlaceholderObserver Extends BaseManicModelObserver
{
	/**
     * Listen to the placeholder creating event.
     *
     * @param  $configurationPlaceholder
     * @return void
     */
    public function creating($configurationPlaceholder)
    {	
		parent::creating($configurationPlaceholder);

        Log::Debug("Creating placeholder configuration entry", ['placeholder_configuration' => $configurationPlaceholder->id]);
    }
	
    /**
     * Listen to the placeholder created event.
     *
     * @param  $configurationPlaceholder
     * @return void
     */
    public function created($configurationPlaceholder)
    {
        parent::created($configurationPlaceholder);

        Log::Info("Created placeholder configuration entry", ['placeholder_configuration' => $configurationPlaceholder->id]);
    }

	/**
     * Listen to the placeholder updating event.
     *
     * @param  $configurationPlaceholder
     * @return void
     */
    public function updating($configurationPlaceholder)
    {
        parent::updating($configurationPlaceholder);

        Log::Debug("Updating placeholder configuration entry", ['placeholder_configuration' => $configurationPlaceholder->id]);
    }
	
    /**
     * Listen to the placeholder updated event.
     *
     * @param  $configurationPlaceholder
     * @return void
     */
    public function updated($configurationPlaceholder)
    {
        parent::updated($configurationPlaceholder);

        Log::Info("Updated placeholder configuration entry", ['placeholder_configuration' => $configurationPlaceholder->id]);
    }
	
	/**
     * Listen to the placeholder saving event.
     *
     * @param  $configurationPlaceholder
     * @return void
     */
    public function saving($configurationPlaceholder)
    {
        parent::saving($configurationPlaceholder);

        Log::Debug("Saving placeholder configuration entry", ['placeholder_configuration' => $configurationPlaceholder->id]);
    }
	
    /**
     * Listen to the placeholder saved event.
     *
     * @param  $configurationPlaceholder
     * @return void
     */
    public function saved($configurationPlaceholder)
    {
        parent::saved($configurationPlaceholder);

        Log::Debug("Saved placeholder configuration entry", ['placeholder_configuration' => $configurationPlaceholder->id]);
    }
	
    /**
     * Listen to the placeholder deleting event.
     *
     * @param  $configurationPlaceholder
     * @return void
     */
    public function deleting($configurationPlaceholder)
    {
        parent::deleting($configurationPlaceholder);

        Log::Debug("Deleting placeholder configuration entry", ['placeholder_configuration' => $configurationPlaceholder->id]);
    }
	
	/**
     * Listen to the placeholder deleted event.
     *
     * @param  $configurationPlaceholder
     * @return void
     */
    public function deleted($configurationPlaceholder)
    {
        parent::deleted($configurationPlaceholder);

        Log::Info("Deleted placeholder configuration entry", ['placeholder_configuration' => $configurationPlaceholder->id]);
    }
	
	/**
     * Listen to the placeholder restoring event.
     *
     * @param  $configurationPlaceholder
     * @return void
     */
    public function restoring($configurationPlaceholder)
    {
        parent::restoring($configurationPlaceholder);

        Log::Debug("Restoring placeholder configuration entry", ['placeholder_configuration' => $configurationPlaceholder->id]);
    }
	
	/**
     * Listen to the placeholder restored event.
     *
     * @param  placeholder  $configurationPlaceholder
     * @return void
     */
    public function restored($configurationPlaceholder)
    {
        parent::restored($configurationPlaceholder);

        Log::Info("Restored placeholder configuration entry", ['placeholder_configuration' => $configurationPlaceholder->id]);
    }
}
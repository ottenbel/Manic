<?php

namespace App\Observers\Configuration\Pagination;

use App\Models\Configuration\ConfigurationPagination;
use App\Observers\BaseManicModelObserver;
use Log;

class PaginationObserver Extends BaseManicModelObserver
{
	/**
     * Listen to the pagination creating event.
     *
     * @param  $configurationPagination
     * @return void
     */
    public function creating($configurationPagination)
    {	
		parent::creating($configurationPagination);

        Log::Debug("Creating pagination configuration entry", ['pagination_configuration' => $configurationPagination]);
    }
	
    /**
     * Listen to the pagination created event.
     *
     * @param  $configurationPagination
     * @return void
     */
    public function created($configurationPagination)
    {
        parent::created($configurationPagination);

        Log::Info("Created pagination configuration entry", ['pagination_configuration' => $configurationPagination]);
    }

	/**
     * Listen to the pagination updating event.
     *
     * @param  $configurationPagination
     * @return void
     */
    public function updating($configurationPagination)
    {
        parent::updating($configurationPagination);

        Log::Debug("Updating pagination configuration entry", ['pagination_configuration' => $configurationPagination]);
    }
	
    /**
     * Listen to the pagination updated event.
     *
     * @param  $configurationPagination
     * @return void
     */
    public function updated($configurationPagination)
    {
        parent::updated($configurationPagination);

        Log::Info("Updated pagination configuration entry", ['pagination_configuration' => $configurationPagination]);
    }
	
	/**
     * Listen to the pagination saving event.
     *
     * @param  $configurationPagination
     * @return void
     */
    public function saving($configurationPagination)
    {
        parent::saving($configurationPagination);

        Log::Debug("Saving pagination configuration entry", ['pagination_configuration' => $configurationPagination]);
    }
	
    /**
     * Listen to the pagination saved event.
     *
     * @param  $configurationPagination
     * @return void
     */
    public function saved($configurationPagination)
    {
        parent::saved($configurationPagination);

        Log::Info("Saved pagination configuration entry", ['pagination_configuration' => $configurationPagination]);
    }
	
    /**
     * Listen to the pagination deleting event.
     *
     * @param  $configurationPagination
     * @return void
     */
    public function deleting($configurationPagination)
    {
        parent::deleting($configurationPagination);

        Log::Debug("Deleting pagination configuration entry", ['pagination_configuration' => $configurationPagination]);
    }
	
	/**
     * Listen to the pagination deleted event.
     *
     * @param  $configurationPagination
     * @return void
     */
    public function deleted($configurationPagination)
    {
        parent::deleted($configurationPagination);

        Log::Info("Deleted pagination configuration entry", ['pagination_configuration' => $configurationPagination]);
    }
	
	/**
     * Listen to the pagination restoring event.
     *
     * @param  $configurationPagination
     * @return void
     */
    public function restoring($configurationPagination)
    {
        parent::restoring($configurationPagination);

        Log::Debug("Restoring pagination configuration entry", ['pagination_configuration' => $configurationPagination]);
    }
	
	/**
     * Listen to the pagination restored event.
     *
     * @param  pagination  $configurationPagination
     * @return void
     */
    public function restored($configurationPagination)
    {
        parent::restored($configurationPagination);

        Log::Info("Created pagination configuration entry", ['pagination_configuration' => $configurationPagination]);
    }
}
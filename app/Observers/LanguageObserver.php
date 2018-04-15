<?php

namespace App\Observers;

use App\Models\Language;
use Log;

class LanguageObserver Extends BaseManicModelObserver
{
	/**
     * Listen to the language creating event.
     *
     * @param  $language
     * @return void
     */
    public function creating($language)
    {	
		parent::creating($language);

        Log::Debug("Creating language", ['language' => $language->id]);
    }
	
    /**
     * Listen to the language created event.
     *
     * @param  $language
     * @return void
     */
    public function created($language)
    {
        parent::created($language);

        Log::Info("Created language", ['language' => $language->id]);
    }

	/**
     * Listen to the language updating event.
     *
     * @param  $language
     * @return void
     */
    public function updating($language)
    {
        parent::updating($language);

        Log::Debug("Updating language", ['language' => $language->id]);
    }
	
    /**
     * Listen to the language updated event.
     *
     * @param  $language
     * @return void
     */
    public function updated($language)
    {
        parent::updated($language);

        Log::Info("Updated language", ['language' => $language->id]);
    }
	
	/**
     * Listen to the language saving event.
     *
     * @param  $language
     * @return void
     */
    public function saving($language)
    {
        parent::saving($language);

        Log::Debug("Saving language", ['language' => $language->id]);
    }
	
    /**
     * Listen to the language saved event.
     *
     * @param  $language
     * @return void
     */
    public function saved($language)
    {
        parent::saved($language);

        Log::Debug("Saved language", ['language' => $language->id]);
    }
	
    /**
     * Listen to the language deleting event.
     *
     * @param  $language
     * @return void
     */
    public function deleting($language)
    {
        parent::deleting($language);

        Log::Debug("Deleting language", ['language' => $language->id]);
    }
	
	/**
     * Listen to the language deleted event.
     *
     * @param  $language
     * @return void
     */
    public function deleted($language)
    {
        parent::deleted($language);

        Log::Info("Deleted language", ['language' => $language->id]);
    }
	
	/**
     * Listen to the language restoring event.
     *
     * @param  $language
     * @return void
     */
    public function restoring($language)
    {
        parent::restoring($language);

        Log::Debug("Restoring language", ['language' => $language->id]);
    }
	
	/**
     * Listen to the language restored event.
     *
     * @param  language  $language
     * @return void
     */
    public function restored($language)
    {
        parent::restored($language);

        Log::Info("Restored language", ['language' => $language->id]);
    }
}
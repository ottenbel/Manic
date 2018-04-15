<?php

namespace App\Observers\TagObjects\Character;

use App\Models\TagObjects\Character\Character;
use App\Observers\BaseManicModelObserver;
use Auth;
use Log;

class CharacterObserver Extends BaseManicModelObserver
{
	/**
     * Listen to the character creating event.
     *
     * @param  $character
     * @return void
     */
    public function creating($character)
    {	
		parent::creating($character);

		$series = $character->series;

        Log::Debug("Creating character", ['character' => $character->id, 'series' => $series->id]);

		$series->updated_by = Auth::user()->id;
		$series->save();
		$series->touch();
    }
	
    /**
     * Listen to the character created event.
     *
     * @param  $character
     * @return void
     */
    public function created($character)
    {
        parent::created($character);

        Log::Info("Created character", ['character' => $character->id]);
    }

	/**
     * Listen to the character updating event.
     *
     * @param  $character
     * @return void
     */
    public function updating($character)
    {
        parent::updating($character);

        Log::Debug("Updating character", ['character' => $character->id]);
    }
	
    /**
     * Listen to the character updated event.
     *
     * @param  $character
     * @return void
     */
    public function updated($character)
    {
        parent::updated($character);

        Log::Info("Updated character", ['character' => $character->id]);
    }
	
	/**
     * Listen to the character saving event.
     *
     * @param  $character
     * @return void
     */
    public function saving($character)
    {
        parent::saving($character);

        Log::Debug("Saving character", ['character' => $character->id]);
    }
	
    /**
     * Listen to the character saved event.
     *
     * @param  $character
     * @return void
     */
    public function saved($character)
    {
        parent::saved($character);

        Log::Debug("Saved character", ['character' => $character->id]);
    }
	
    /**
     * Listen to the character deleting event.
     *
     * @param  $character
     * @return void
     */
    public function deleting($character)
    {
        parent::deleting($character);
		
        Log::Debug("Deleting character", ['character' => $character->id]);

		$series = $character->series;
		$series->updated_by = Auth::user()->id;
		$series->save();
		$series->touch();
    }
	
	/**
     * Listen to the character deleted event.
     *
     * @param  $character
     * @return void
     */
    public function deleted($character)
    {
        parent::deleted($character);

        Log::Info("Deleted character", ['character' => $character->id]);
    }
	
	/**
     * Listen to the character restoring event.
     *
     * @param  $character
     * @return void
     */
    public function restoring($character)
    {
        parent::restoring($character);

        Log::Debug("Restoring character", ['character' => $character->id]);
    }
	
	/**
     * Listen to the character restored event.
     *
     * @param  character  $character
     * @return void
     */
    public function restored($character)
    {
        parent::restored($character);

        Log::Info("Restored character", ['character' => $character->id]);
    }
}
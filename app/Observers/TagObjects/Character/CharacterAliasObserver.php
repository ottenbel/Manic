<?php

namespace App\Observers\TagObjects\Character;

use App\Models\TagObjects\Character\CharacterAlias;
use App\Observers\BaseManicModelObserver;
use Auth;
use Log;

class CharacterAliasObserver Extends BaseManicModelObserver
{
	/**
     * Listen to the character alias creating event.
     *
     * @param  $characterAlias
     * @return void
     */
    public function creating($characterAlias)
    {	
		parent::creating($characterAlias);

		$character = $characterAlias->character;

        Log::Debug("Creating character alias", ['character alias' => $characterAlias->id, 'character' => $character->id]);

		$character->updated_by = Auth::user()->id;
		$character->save();
		$character->touch();
    }
	
    /**
     * Listen to the character alias created event.
     *
     * @param  $characterAlias
     * @return void
     */
    public function created($characterAlias)
    {
        parent::created($characterAlias);

        Log::Info("Created character alias", ['character alias' => $characterAlias->id]);
    }

	/**
     * Listen to the character alias updating event.
     *
     * @param  $characterAlias
     * @return void
     */
    public function updating($characterAlias)
    {
        parent::updating($characterAlias);

        Log::Debug("Updating character alias", ['character alias' => $characterAlias->id]);
    }
	
    /**
     * Listen to the character alias updated event.
     *
     * @param  $characterAlias
     * @return void
     */
    public function updated($characterAlias)
    {
        parent::updated($characterAlias);

        Log::Info("Updated character alias", ['character alias' => $characterAlias->id]);
    }
	
	/**
     * Listen to the character alias saving event.
     *
     * @param  $characterAlias
     * @return void
     */
    public function saving($characterAlias)
    {
        parent::saving($characterAlias);

        Log::Debug("Saving character alias", ['character alias' => $characterAlias->id]);
    }
	
    /**
     * Listen to the character alias saved event.
     *
     * @param  $characterAlias
     * @return void
     */
    public function saved($characterAlias)
    {
        parent::saved($characterAlias);

        Log::Debug("Saved character alias", ['character alias' => $characterAlias->id]);
    }
	
    /**
     * Listen to the character alias deleting event.
     *
     * @param  $characterAlias
     * @return void
     */
    public function deleting($characterAlias)
    {
        parent::deleting($characterAlias);
		
        Log::Debug("Deleting character alias", ['character alias' => $characterAlias->id]);

		$character = $characterAlias->character;
		$character->updated_by = Auth::user()->id;
		$character->save();
		$character->touch();
    }
	
	/**
     * Listen to the character alias deleted event.
     *
     * @param  $characterAlias
     * @return void
     */
    public function deleted($characterAlias)
    {
        parent::deleted($characterAlias);

        Log::Info("Deleted character alias", ['character alias' => $characterAlias->id]);
    }
	
	/**
     * Listen to the character alias restoring event.
     *
     * @param  $characterAlias
     * @return void
     */
    public function restoring($characterAlias)
    {
        parent::restoring($characterAlias);

        Log::Debug("Restoring character alias", ['character alias' => $characterAlias->id]);
    }
	
	/**
     * Listen to the character alias restored event.
     *
     * @param  character alias  $characterAlias
     * @return void
     */
    public function restored($characterAlias)
    {
        parent::restored($characterAlias);

        Log::Info("Restored character alias", ['character alias' => $characterAlias->id]);
    }
}
<?php

namespace App\Models\TagObjects\Character;

use App\Models\TagObjects\CollectionAssociatedTagObjectModel;

class Character extends CollectionAssociatedTagObjectModel
{
	//Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'characters';
	//Update the corresponding series when creating/updating a character (use function name).
	protected $touches = ['series'];
	
	public static function boot()
    {
        parent::boot();
    }
	
	/*
	 * Get the series that the character is associated with.
	 */
	public function series()
	{
		return $this->belongsTo('App\Models\TagObjects\Series\Series');
	}
	
	/*
	 * Get the aliases associated with the character.
	 */
	public function aliases()
	{
		return $this->hasMany('App\Models\TagObjects\Character\CharacterAlias');
	}
}

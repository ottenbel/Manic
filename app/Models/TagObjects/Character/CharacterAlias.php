<?php

namespace App\Models\TagObjects\Character;

use App\Models\BaseManicModel;

class CharacterAlias extends BaseManicModel
{
	//Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'character_alias';
	//Update the corresponding character when creating/updating an alias (use function name).
	protected $touches = ['character'];
	
	public static function boot()
    {
        parent::boot();
    }
	
	/*
	 * Get the character that the alias belongs to.
	 */
	public function character()
	{
		return $this->belongsTo('App\Models\TagObjects\Character\Character');
	}
}

<?php

namespace App\Models\TagObjects\Character;

use App\Models\BaseManicModel;

class CharacterAlias extends BaseManicModel
{
	//Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'character_alias';
	
	/*
	 * Get the character that the alias belongs to.
	 */
	public function character()
	{
		return $this->belongsTo('App\ModelsTagObjects\Character\Character')->withTimestamps();
	}
}

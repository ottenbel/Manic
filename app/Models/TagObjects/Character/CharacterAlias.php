<?php

namespace App\Models\TagObjects\Character;

use App\Models\BaseManicModel;
use Auth;

class CharacterAlias extends BaseManicModel
{
	//Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'character_alias';
	
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
	
	/*
	 * A generic function call to retrieve the character that the alias belongs to.
	 */
	public function tag_object()
	{
		return $this->belongsTo('App\Models\TagObjects\Character\Character', 'character_id');
	}
}

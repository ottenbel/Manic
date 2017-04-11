<?php

namespace App\Models\TagObjects\Character;

use App\Models\BaseManicModel;

class CharacterAlias extends BaseManicModel
{
	//Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'character_alias';
	
	public static function boot()
    {
        parent::boot();
		
		/*
		 * The touches array doesn't call the update function.
		 */
		static::saved(function($model)
		{
			$character = $model->character();
			$character->touch();
		}
    }
	
	/*
	 * Get the character that the alias belongs to.
	 */
	public function character()
	{
		return $this->belongsTo('App\Models\TagObjects\Character\Character');
	}
}

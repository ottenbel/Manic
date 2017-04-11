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
		
		static::creating(function($model)
		{
			parent::creating($model);
			
			$character = $model->character()->first();
			$character->updated_by = Auth::user()->id;
			$character->save();
		});
		
		static::deleting(function($model)
		{
			parent::deleting($model);
			
			$character = $model->character()->first();
			$character->updated_by = Auth::user()->id;
			$character->save();
		});
    }
	
	/*
	 * Get the character that the alias belongs to.
	 */
	public function character()
	{
		return $this->belongsTo('App\Models\TagObjects\Character\Character');
	}
}

<?php

namespace App\Models\TagObjects\Character;

use App\Models\TagObjects\CollectionAssociatedTagObjectModel;
use Auth;

class Character extends CollectionAssociatedTagObjectModel
{
	//Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'characters';
	
	public static function boot()
    {
        parent::boot();
		
		static::creating(function($model)
		{
			parent::creating($model);
			
			$series = $model->series()->first();
			$series->updated_by = Auth::user()->id;
			$series->save();
			$series->touches();
		});
		
		static::deleting(function($model)
		{
			parent::deleting($model);
			
			$series = $model->series()->first();
			$series->updated_by = Auth::user()->id;
			$series->save();
			$series->touches();
		});
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

<?php

namespace App\Models\TagObjects\Character;

use App\Models\TagObjects\CollectionAssociatedTagObjectModel;

class Character extends CollectionAssociatedTagObjectModel
{
	//Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'characters';
	
	/*
	 * Get the series that the character is associated with.
	 */
	public function series()
	{
		return $this->belongsTo('App\Models\TagObjects\Series\Series');
	}
}

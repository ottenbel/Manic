<?php

namespace App\Models\TagObjects\Artist;

use App\Models\BaseManicModel;

class ArtistAlias extends BaseManicModel
{
	//Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'artist_alias';
	
	/*
	 * Get the artist that the alias belongs to.
	 */
	public function artist()
	{
		return $this->belongsTo('App\ModelsTagObjects\Artist\Artist')->withTimestamps();
	}
}

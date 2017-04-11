<?php

namespace App\Models\TagObjects\Artist;

use App\Models\TagObjects\CollectionAssociatedTagObjectModel;

class Artist extends CollectionAssociatedTagObjectModel
{
	//Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'artists';
	
	public static function boot()
    {
        parent::boot();
    }
	
	/*
	 * Get the aliases associated with the artist.
	 */
	public function aliases()
	{
		return $this->hasMany('App\Models\TagObjects\Artist\ArtistAlias');
	}
}

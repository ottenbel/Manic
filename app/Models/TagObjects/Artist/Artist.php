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
	
	/*
	 * Get all parent artists for the given artist.
	 */
	public function parents()
	{
		return $this->belongsToMany('App\Models\TagObjects\Artist\Artist', 'artist_artist', 'child_id', 'parent_id');
	}
	
	/*
	 * Get all child artists for the given artist.
	 */
	public function children()
	{
		return $this->belongsToMany('App\Models\TagObjects\Artist\Artist', 'artist_artist', 'parent_id', 'child_id');
	}
}

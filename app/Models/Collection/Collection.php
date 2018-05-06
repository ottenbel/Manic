<?php

namespace App\Models\Collection;

use App\Models\BaseManicModel;

class Collection extends BaseManicModel
{
	//Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'collections';
	protected $fillable = ['name', 'parent_id', 'description', 'canonical', 'status_id', 'rating_id', 'language_id'];
	
	public static function boot()
    {
        parent::boot();
    }
	
	/*
	 * Get all volumes associated with the collection.
	 */
    public function volumes()
	{
		return $this->hasMany('App\Models\Volume\Volume')->orderBy('volume_number');
	}
	
	public function unsorted_volumes()
	{
		return $this->hasMany('App\Models\Volume\Volume');
	}
	
	/*
	 * Get all chapters associated with the collection.
	 */
	public function chapters()
	{
		return $this->hasManyThrough('App\Models\Chapter', 'App\Models\Volume\Volume');
	}
	
	/*
	 * Get the cover image associated with the collection (one sided mapping).
	 */
	public function cover_image()
	{
		return $this->belongsTo('App\Models\Image', 'cover');
	}
	
	/*
	 * Get the mapping from collection to parent collection.
	 */
	public function parent_collection()
	{
		 return $this->belongsTo('App\Models\Collection\Collection', 'parent_id');
	}
	 
	/*
	 * Get mapping from collection to child collections.
	 */
	public function child_collections()
	{
		return $this->hasMany('App\Models\Collection\Collection', 'parent_id');
	}
	
	/*
	 * Get the mapping from collection to language.
	 */
	public function language()
	{
		return $this->belongsTo('App\Models\Language');
	}
	
	/*
	 * Get the mapping from collection to artists.
	 */	 
	public function artists()
	{
		return $this->belongsToMany('App\Models\TagObjects\Artist\Artist')->withTimestamps()->withPivot('primary');
	}
	
	/*
	 * Get mapping from collection to primary artists.
	 */
	public function primary_artists()
	{
		return $this->belongsToMany('App\Models\TagObjects\Artist\Artist')->withTimestamps()->withPivot('primary')->where('primary', '=', true);
	}
	
	/*
	 * Get mapping from collection to secondary artists.
	 */
	public function secondary_artists()
	{
		return $this->belongsToMany('App\Models\TagObjects\Artist\Artist')->withTimestamps()->withPivot('primary')->where('primary', '=', false);
	}

	/*
	 * Get the mapping from collection to characters.
	 */	 
	public function characters()
	{
		return $this->belongsToMany('App\Models\TagObjects\Character\Character')->withTimestamps()->withPivot('primary');
	}
	
	/*
	 * Get mapping from collection to primary characters.
	 */
	public function primary_characters()
	{
		return $this->belongsToMany('App\Models\TagObjects\Character\Character')->withTimestamps()->withPivot('primary')->where('primary', '=', true);
	}
	
	/*
	 * Get mapping from collection to secondary characters.
	 */
	public function secondary_characters()
	{
		return $this->belongsToMany('App\Models\TagObjects\Character\Character')->withTimestamps()->withPivot('primary')->where('primary', '=', false);
	}
	
	/*
	 * Get the mapping from collection to series.
	 */
	public function series()
	{
		return $this->belongsToMany('App\Models\TagObjects\Series\Series')->withTimestamps()->withPivot('primary');
	}
	
	/*
	 * Get the mapping from collection to primary series.
	 */
	public function primary_series()
	{
		return $this->belongsToMany('App\Models\TagObjects\Series\Series')->withTimestamps()->withPivot('primary')->where('primary', '=', true);
	}
	
	/*
	 * Get the mapping from collection to secondary series.
	 */
	public function secondary_series()
	{
		return $this->belongsToMany('App\Models\TagObjects\Series\Series')->withTimestamps()->withPivot('primary')->where('primary', '=', false);
	}
	
	/*
	 * Get the mapping from collection to tags.
	 */ 
	public function tags()
	{
		return $this->belongsToMany('App\Models\TagObjects\Tag\Tag')->withTimestamps()->withPivot('primary');
	}
	
	/*
	 * Get the mapping from collection to primary tags.
	 */
	public function primary_tags()
	{
		return $this->belongsToMany('App\Models\TagObjects\Tag\Tag')->withTimestamps()->withPivot('primary')->where('primary', '=', true);
	}
	
	/*
	 * Get the mapping from collection to secondary tags.
	 */
	public function secondary_tags()
	{
		return $this->belongsToMany('App\Models\TagObjects\Tag\Tag')->withTimestamps()->withPivot('primary')->where('primary', '=', false);
	}
	 
	/*
	 * Get mapping from rating to collections.
	 */
	public function rating()
	{
		return $this->belongsTo('App\Models\Rating');
	}
	
	/*
	 * Get the mapping from collection to status.
	 */
	public function status()
	{
		return $this->belongsTo('App\Models\Status');
	}
	
	/*
	 * Get the export associated with the collection.
	 */
	public function export()
	{
		return $this->hasOne('App\Models\Collection\CollectionExport');
	}
	
	/*
	 * Get all collection favourites associated with the user
	 */
	public function favourited()
	{
		return $this->hasMany('App\Models\User\CollectionFavourite');
	}
	
	/*
	 * Get all collection blacklist associated with the user
	 */
	public function blacklisted()
	{
		return $this->hasMany('App\Models\User\CollectionBlacklist');
	}

	//Transform the tag objects on the collection into a string 
	public function PrimaryArtistsToString()
	{
		return collect($this->primary_artists()->pluck('name'))->implode(", ");
	}

	public function SecondaryArtistsToString()
	{
		return collect($this->secondary_artists()->pluck('name'))->implode(", ");
	}

	public function PrimarySeriesToString()
	{
		return collect($this->primary_series()->pluck('name'))->implode(", ");
	}

	public function SecondarySeriesToString()
	{
		return collect($this->secondary_series()->pluck('name'))->implode(", ");
	}

	public function PrimaryCharactersToString()
	{
		return collect($this->primary_characters()->pluck('name'))->implode(", ");
	}

	public function SecondaryCharactersToString()
	{
		return collect($this->secondary_characters()->pluck('name'))->implode(", ");
	}

	public function PrimaryTagsToString()
	{
		return collect($this->primary_tags()->pluck('name'))->implode(", ");
	}

	public function SecondaryTagsToString()
	{
		return collect($this->secondary_tags()->pluck('name'))->implode(", ");
	}
}

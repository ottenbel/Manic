<?php

namespace App\Models\TagObjects\Character;

use App\Models\TagObjects\CollectionAssociatedTagObjectModel;

class Character extends CollectionAssociatedTagObjectModel
{
	//Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'characters';
	protected $fillable = ['name', 'short_description', 'description', 'url'];
	
	public static function boot()
    {
        parent::boot();
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
	
	/*
	 * Get all parent characters for the given character.
	 */
	public function parents()
	{
		return $this->belongsToMany('App\Models\TagObjects\Character\Character', 'character_character', 'child_id', 'parent_id');
	}
	
	/*
	 * Get all child characters for the given character.
	 */
	public function children()
	{
		return $this->belongsToMany('App\Models\TagObjects\Character\Character', 'character_character', 'parent_id', 'child_id');
	}
}

<?php

namespace App;

use App\BaseManicModel;

class Chapter extends BaseManicModel
{
	//Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'chapters';
	
	/*
	 * Get the pages associated with the chapter.
	 */
	public function pages()
	{
		return $this->hasMany('App\Page');
	}
	
	/*
	 * Get the volume that the chapter is associated with.
	 */
	public function volume()
	{
		return $this->belongsTo('App\Volume');
	}
	
	/*
	 * Get mapping from chapter to scanalators.
	 */
	public function scanalators()
	{
		return $this->belongsToMany('App\Scanalator')->withTimestamps()->withPivot('primary', 'created_by', 'updated_by', 'deleted_at');
	}
}

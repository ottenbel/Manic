<?php

namespace App;

use App\BaseManicModel;

class Volume extends BasicManicModel
{
    //Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'volumes';
	
	/*
	 * Get all chapters associated with the volume.
	 */
	public function chapters()
	{
		return $this->hasMany('App\Chapter');
	}
	
	/*
	 * Get the collection that the volume is associated with.
	 */
	public function collection()
	{
		return $this->belongsTo('App\Collection');
	}
	
	/*
	 * Get the cover image associated with the volume.
	 */
	public function cover_image()
	{
		return $this->belongsTo('App\Images');
	}
}

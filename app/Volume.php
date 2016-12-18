<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Volume extends Model
{
    use Uuids;
    
	public $incrementing = false;
	
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

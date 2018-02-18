<?php

namespace App\Models;

use App\Models\BaseManicModel;

class Volume extends BaseManicModel
{
    //Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'volumes';
	
	public static function boot()
    {
        parent::boot();
    }
	
	/*
	 * Get all chapters associated with the volume.
	 */
	public function chapters()
	{
		return $this->hasMany('App\Models\Chapter');
	}
	
	/*
	 * Get the collection that the volume is associated with.
	 */
	public function collection()
	{
		return $this->belongsTo('App\Models\Collection');
	}
	
	/*
	 * Get the cover image associated with the volume.
	 */
	public function cover_image()
	{
		return $this->belongsTo('App\Models\Image', 'cover');
	}
	
	/*
	 * Get the next volume in the collection.
	 */
	public function next_volume()
	{
		return $this->collection->volumes()->where('volume_number', '>', $this->volume_number)->orderBy('volume_number', 'asc')->take(1);
	}
	
	/*
	 * Get the previous volume in the collection.
	 */
	public function previous_volume()
	{
		return $this->collection->volumes()->where('volume_number', '<', $this->volume_number)->orderBy('volume_number', 'desc')->take(1);
	}
	
	/*
	 * Get the first chapter in the volume.
	 */
	public function first_chapter()
	{
		return $this->chapters()->orderBy('chapter_number', 'asc')->take(1);
	}
	
	/*
	 * Get the last chapter in the volume.
	 */
	public function last_chapter()
	{
		return $this->chapters()->orderBy('chapter_number', 'desc')->take(1);
	}
	
	/*
	 * Get the export associated with the volume.
	 */
	public function export()
	{
		return $this->hasOne('App\Models\VolumeExport');
	}
}

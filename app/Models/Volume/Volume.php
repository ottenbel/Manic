<?php

namespace App\Models\Volume;

use App\Models\BaseManicModel;
use Illuminate\Support\Facades\Cache;

class Volume extends BaseManicModel
{
    //Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'volumes';
    public $with = ['cover_image'];
	
	public static function boot()
    {
        parent::boot();
    }
	
	/*
	 * Get all chapters associated with the volume.
	 */
	public function chapters()
	{
		return $this->hasMany('App\Models\Chapter\Chapter');
	}
	
	/*
	 * Get the collection that the volume is associated with.
	 */
	public function collection()
	{
		return $this->belongsTo('App\Models\Collection\Collection');
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
		$next_volume = Cache::tags([ $this->collection->id . 'next_volume'])->rememberForever($this->id . "next_volume", function () {
			return $this->collection->volumes()->where('volume_number', '>', $this->volume_number)->orderBy('volume_number', 'asc')->first();
		});

		return $next_volume;
	}
	
	/*
	 * Get the previous volume in the collection.
	 */
	public function previous_volume()
	{
		$previous_volume = Cache::tags([ $this->collection->id . 'previous_volume'])->rememberForever($this->id . "previous_volume", function () {
				return $this->collection->volumes()->where('volume_number', '<', $this->volume_number)->orderBy('volume_number', 'desc')->first();
		});

		return $previous_volume;
	}
	
	/*
	 * Get the first chapter in the volume.
	 */
	public function first_chapter()
	{
		$firstChapter = Cache::rememberForever($this->id ."first_chapter", function () {
			return $this->chapters()->orderBy('chapter_number', 'asc')->first();
		});

		return $firstChapter;
	}
	
	/*
	 * Get the last chapter in the volume.
	 */
	public function last_chapter()
	{
		$lastChapter = Cache::rememberForever($this->id ."last_chapter", function () {
			return $this->chapters()->orderBy('chapter_number', 'desc')->first();
		});

		return $lastChapter;
	}
	
	/*
	 * Get the export associated with the volume.
	 */
	public function export()
	{
		return $this->hasOne('App\Models\Volume\VolumeExport');
	}
}

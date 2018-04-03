<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChapterExport extends Model
{	
	use Uuids;
	public $incrementing = false;
	public $timestamps = false;
	
	/*
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'last_downloaded'
    ];
	
	public static function boot()
	{
		parent::boot();
		static::bootUuidsTrait();
	}
	
	/*
	 * Get the mapping to the chapter that the export is associated with.
	 */
	public function chapter()
	{
		return $this->belongsTo('App\Models\Chapter');
	}
}
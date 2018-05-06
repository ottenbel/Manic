<?php

namespace App\Models\Collection;

use Illuminate\Database\Eloquent\Model;
use App\Models\Uuids;

class CollectionExport extends Model
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
	 * Get the mapping to the collection that the export is associated with.
	 */
	public function collection()
	{
		return $this->belongsTo('App\Models\Collection\Collection');
	}
}
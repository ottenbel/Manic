<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Scanalator extends Model
{
    use Uuids;
	use SoftDeletes;
	
	/*
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
	public $incrementing = false;
	
	/*
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
	
	/*
	 * Get the chapters associated with the current scanalator.
	 */
	public function chapters()
	{
		return $this->belongsToMany('App\Chapter')->withTimestamps()->withPivot('primary', 'created_by', 'updated_by', 'deleted_at');
	}
}

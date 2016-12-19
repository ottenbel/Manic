<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Artist extends Model
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
	 * Get the mapping from artist to collections.
	 */
	public function collections()
	{
		return $this->belongsToMany('App\Collection')->withTimestamps()->withPivot('primary', 'created_by', 'updated_by', 'deleted_at');
	}
	
	/*
	 * Get the mapping to the user that created the tag.
	 */
	public function created_by()
	{
		return $this->belongsTo('App\User', 'id', 'created_by');
	}
	
	/*
	 * Get the mapping to the user that last updated the tag.
	 */
	public function updated_by()
	{
		return $this->belongsTo('App\User', 'id', 'updated_by');
	}
}

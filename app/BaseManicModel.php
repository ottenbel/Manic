<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseManicModel extends Model
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
	 * Get the mapping to the user that created the rating.
	 */
	public function created_by_user()
	{
		return $this->belongsTo('App\User', 'created_by');
	}
	
	/*
	 * Get the mapping to the user that last updated the rating.
	 */
	public function updated_by_user()
	{
		return $this->belongsTo('App\User', 'updated_by');
	}
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

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
	
	public static function boot()
	{
		parent::boot();
		static::bootUuidsTrait();
    }
	
	/*
	 * Get the mapping to the user that created the rating.
	 */
	public function created_by_user()
	{
		return $this->belongsTo('App\Models\User', 'created_by');
	}
	
	/*
	 * Get the mapping to the user that last updated the rating.
	 */
	public function updated_by_user()
	{
		return $this->belongsTo('App\Models\User', 'updated_by');
	}
}
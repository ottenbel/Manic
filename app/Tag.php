<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
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
	 * Get the collections associated with the current tag.
	 */
	public function collections()
	{
		return $this->belongsToMany('App\Tag')->withTimestamps()->withPivot('primary', 'created_by', 'updated_by', 'deleted_at');
	}
}

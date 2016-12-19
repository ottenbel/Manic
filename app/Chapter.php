<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chapter extends Model
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
	 * Get the pages associated with the chapter.
	 */
	public function pages()
	{
		return $this->hasMany('App\Page');
	}
	
	/*
	 * Get the volume that the chapter is associated with.
	 */
	public function volume()
	{
		return $this->belongsTo('App\Volume');
	}
	
	/*
	 * Get mapping from chapter to scanalators.
	 */
	public function scanalators()
	{
		return $this->belongsToMany('App\Scanalator')->withTimestamps()->withPivot('primary', 'created_by', 'updated_by', 'deleted_at');
	}

	/*
	 * Get the mapping to the user that created the chapter.
	 */
	public function created_by()
	{
		return $this->belongsTo('App\User', 'id', 'created_by');
	}
	
	/*
	 * Get the mapping to the user that last updated the chapter.
	 */
	public function updated_by()
	{
		return $this->belongsTo('App\User', 'id', 'updated_by');
	}
}

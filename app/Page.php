<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
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
	 * Get the image associated with the page.
	 */
	public function image()
	{
		return $this->belongsTo('App\Images');
	}
	
	/*
	 * Get the chapter that the page is associated with (one sided mapping).
	 */
	public function chapter()
	{
		return $this->belongsTo('App\Chapter');
	}
}

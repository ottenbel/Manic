<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
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
	 * Get any collections that use this image as a cover image.
	 */
	 public function collections()
	 {
		$this->hasMany('App\Collection', 'cover'); 
	 }
	 
	 /*
	  * Get any pages that use this image as a page image.
	  */
	 public function pages()
	 {
		 $this->hasMany('App\Page', '')
	 }
	 
	 /*
	 * Get the mapping to the user that created the image.
	 */
	public function created_by()
	{
		return $this->belongsTo('App\User', 'id', 'created_by');
	}
	
	/*
	 * Get the mapping to the user that last updated the image.
	 */
	public function updated_by()
	{
		return $this->belongsTo('App\User', 'id', 'updated_by');
	}
}

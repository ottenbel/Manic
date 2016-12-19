<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Language extends Model
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
	 * Get the collections associated with the current language.
	 */
	public function collections()
	{
		return $this->hasMany('App\Collection');
	}
}

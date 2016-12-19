<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use Uuids;
	use SoftDeletes;
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
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
	 * Get the list of artists created by the user.
	 */
	public function artists_created()
	{
		return $this->hasMany('App\Artist', 'created_by');
	}
	
	/*
	 * Get the list of artists last updated by the user.
	 */
	public function artists_last_updated()
	{
		return $this->hasMany('App\Artist', 'updated_by');
	}
	
	/*
	 * Get the list of chapters created by the user.
	 */
	public function chapters_created()
	{
		return $this->hasMany('App\Chapter', 'created_by');
	}
	
	/*
	 * Get the list of chapters last updated by the user.
	 */
	public function chapters_last_updated()
	{
		return $this->hasMany('App\Chapter', 'updated_by');
	}
	
	/*
	 * Get the list of collections created by the user.
	 */
	public function collections_created()
	{
		return $this->hasMany('App\Collection', 'created_by');
	}
	
	/*
	 * Get the list of collections last updated by the user.
	 */
	public function collections_last_updated()
	{
		return $this->hasMany('App\Chapter', 'updated_by');
	}
	
	/*
	 * Get the list of languages created by the user.
	 */
	public function languages_created()
	{
		return $this->hasMany('App\Language', 'created_by');
	}
	
	/*
	 * Get the list of languages last updated by the user.
	 */
	public function languages_last_updated()
	{
		return $this->hasMany('App\Languages', 'updated_by');
	}
	
	/*
	 * Get the list of pages created by the user.
	 */
	public function pages_created()
	{
		return $this->hasMany('App\Page', 'created_by');
	}
	
	/*
	 * Get the list of pages last updated by the user.
	 */
	public function pages_last_updated()
	{
		return $this->hasMany('App\Pages', 'updated_by');
	}
	
	/*
	 * Get the list of ratings created by the user.
	 */
	public function ratings_created()
	{
		return $this->hasMany('App\Rating', 'created_by');
	}
	
	/*
	 * Get the list of ratings last updated by the user.
	 */
	public function ratings_last_updated()
	{
		return $this->hasMany('App\Rating', 'updated_by');
	}
}

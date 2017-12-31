<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Config;

class User extends Authenticatable
{
    use Notifiable;
    use Uuids;
	use SoftDeletes;
	use HasRoles;
	
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
	
	public static function boot()
	{
		parent::boot();
		static::bootUuidsTrait();
	}
	
	/*
	 * Get the list of chapters created by the user.
	 */
	public function chapters_created()
	{
		return $this->hasMany('App\Models\Chapter', 'created_by');
	}
	
	/*
	 * Get the list of chapters last updated by the user.
	 */
	public function chapters_last_updated()
	{
		return $this->hasMany('App\Models\Chapter', 'updated_by');
	}
	
	/*
	 * Get the list of collections created by the user.
	 */
	public function collections_created()
	{
		return $this->hasMany('App\Models\Collection', 'created_by');
	}
	
	/*
	 * Get the list of collections last updated by the user.
	 */
	public function collections_last_updated()
	{
		return $this->hasMany('App\Models\Chapter', 'updated_by');
	}
	
	/*
	 * Get the list of languages created by the user.
	 */
	public function languages_created()
	{
		return $this->hasMany('App\Models\Language', 'created_by');
	}
	
	/*
	 * Get the list of languages last updated by the user.
	 */
	public function languages_last_updated()
	{
		return $this->hasMany('App\Models\Languages', 'updated_by');
	}
	
	/*
	 * Get the list of pages created by the user.
	 */
	public function pages_created()
	{
		return $this->hasMany('App\Models\Page', 'created_by');
	}
	
	/*
	 * Get the list of pages last updated by the user.
	 */
	public function pages_last_updated()
	{
		return $this->hasMany('App\Models\Pages', 'updated_by');
	}
	
	/*
	 * Get the list of ratings created by the user.
	 */
	public function ratings_created()
	{
		return $this->hasMany('App\Models\Rating', 'created_by');
	}
	
	/*
	 * Get the list of ratings last updated by the user.
	 */
	public function ratings_last_updated()
	{
		return $this->hasMany('App\Models\Rating', 'updated_by');
	}
	
	/*
	 * Get the list of status created by the user.
	 */
	public function status_created()
	{
		return $this->hasMany('App\Models\Status', 'created_by');
	}
	
	/*
	 * Get the list of status last updated by the user.
	 */
	public function status_last_updated()
	{
		return $this->hasMany('App\Models\Status', 'updated_by');
	}
	
	/*
	 * Get the list of volumes created by the user.
	 */
	public function volumes_created()
	{
		return $this->hasMany('App\Models\Volume', 'created_by');
	}
	
	/*
	 * Get the list of volumes last updated by the user.
	 */
	public function volumes_last_updated()
	{
		return $this->hasMany('App\Models\Volume', 'updated_by');
	}
	
	/*
	 * Get the list of artists created by the user.
	 */
	public function artists_created()
	{
		return $this->hasMany('App\Models\TagObjects\Artist\Artist', 'created_by');
	}
	
	/*
	 * Get the list of artists last updated by the user.
	 */
	public function artists_last_updated()
	{
		return $this->hasMany('App\Models\TagObjects\Artist\Artist', 'updated_by');
	}
	
	/*
	 * Get the list of characters created by the user.
	 */
	public function characters_created()
	{
		return $this->hasMany('App\Models\TagObjects\Character\Character', 'created_by');
	}
	
	/*
	 * Get the list of characters last updated by the user.
	 */
	public function characters_last_updated()
	{
		return $this->hasMany('App\Models\TagObjects\Character\Character', 'updated_by');
	}
	
	/*
	 * Get the list of scanalators created by the user.
	 */
	public function scanalators_created()
	{
		return $this->hasMany('App\Models\TagObjects\Scanalator\Scanalator', 'created_by');
	}
	
	/*
	 * Get the list of scanalators last updated by the user.
	 */
	public function scanalators_last_updated()
	{
		return $this->hasMany('App\Models\TagObjects\Scanalator\Scanalator', 'updated_by');
	}
	
	/*
	 * Get the list of series created by the user.
	 */
	public function series_created()
	{
		return $this->hasMany('App\Models\TagObjects\Series\Series', 'created_by');
	}
	
	/*
	 * Get the list of series last updated by the user.
	 */
	public function series_last_updated()
	{
		return $this->hasMany('App\Models\TagObjects\Series\Series', 'updated_by');
	}
	
	/*
	 * Get the list of tags created by the user.
	 */
	public function tags_created()
	{
		return $this->hasMany('App\Models\TagObjects\Tag\Tag', 'created_by');
	}
	
	/*
	 * Get the list of tags last updated by the user.
	 */
	public function tags_last_updated()
	{
		return $this->hasMany('App\Models\TagObjects\Tag\Tag', 'updated_by');
	}
	
	/*
	 * Get all pagination configuration values associated with the user
	 */
	public function pagination_configuration()
	{
		return $this->hasMany('App\Models\Configuration\ConfigurationPagination');
	}
	
	/*
	 * Get all placeholder configuration values associated with the user
	 */
	public function placeholder_configuration()
	{
		return $this->hasMany('App\Models\Configuration\ConfigurationPlaceholder');
	}
	
	/*
	 * Get all rating restriction configuration values associated with the user
	 */
	public function rating_restriction_configuration()
	{
		return $this->hasMany('App\Models\Configuration\ConfigurationRatingRestriction');
	}
}

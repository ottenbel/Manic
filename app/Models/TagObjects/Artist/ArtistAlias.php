<?php

namespace App\Models\TagObjects\Artist;

use App\Models\BaseManicModel;

class ArtistAlias extends BaseManicModel
{
	//Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'artist_alias';
	
	public static function boot()
    {
        parent::boot();
		
		/*
		 * The touches array doesn't call the update function.
		 */
		static::saved(function($model)
		{
			$artist = $model->artist();
			$artist->touch();
		}
    }
	
	/*
	 * Get the artist that the alias belongs to.
	 */
	public function artist()
	{
		return $this->belongsTo('App\Models\TagObjects\Artist\Artist');
	}
}

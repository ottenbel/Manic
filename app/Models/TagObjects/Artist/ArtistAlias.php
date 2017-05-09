<?php

namespace App\Models\TagObjects\Artist;

use App\Models\BaseManicModel;
use Auth;

class ArtistAlias extends BaseManicModel
{
	//Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'artist_alias';
	
	public static function boot()
    {
        parent::boot();
		
		static::creating(function($model)
		{
			parent::creating($model);
			
			$artist = $model->artist()->first();
			$artist->updated_by = Auth::user()->id;
			$artist->save();
			$artist->touch();
		});
		
		static::deleting(function($model)
		{
			parent::deleting($model);
			
			$artist = $model->artist()->first();
			$artist->updated_by = Auth::user()->id;
			$artist->save();
			$artist->touch();
		});
    }
	
	/*
	 * Get the artist that the alias belongs to.
	 */
	public function artist()
	{
		return $this->belongsTo('App\Models\TagObjects\Artist\Artist');
	}
	
	/*
	 * A generic function call to retrieve the artist that the alias belongs to.
	 */
	public function tag_object()
	{
		return $this->belongsTo('App\Models\TagObjects\Artist\Artist', 'artist_id');
	}
}

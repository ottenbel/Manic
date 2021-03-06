<?php

namespace App\Models\User;

use App\Models\BaseManicModel;

class CollectionFavourite extends BaseManicModel
{
	//Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'collection_favourites';
	
	public static function boot()
    {
        parent::boot();
    }
	
	/*
	 * Get the collection that the favourite is associated with.
	 */
	public function collection()
	{
		return $this->belongsTo('App\Models\Collection\Collection');
	}
	
	/*
	 * Get the user that the favourite is associated with.
	 */
	public function user()
	{
		return $this->belongsTo('App\Models\User');
	}
}
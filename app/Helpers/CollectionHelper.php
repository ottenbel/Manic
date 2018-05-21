<?php

namespace App\Helpers;

use Auth;
use App\Models\Collection\Collection;
use App\Models\Configuration\ConfigurationRatingRestriction;

class CollectionHelper
{
	public static function FilteredCollections()
	{
		$collections = new Collection();
		
		$ratingRestrictions = null;
		if(Auth::check())
		{
			//Remove all entries from the blacklist
			$blacklist = Auth::user()->blacklisted_collections()->pluck('collection_id')->toArray();
			$collections = $collections->whereNotIn('id', $blacklist);
			
			$ratingRestrictions = Auth::user()->rating_restriction_configuration->where('display', '=', false)->pluck('rating_id')->toArray();
		}
		else
		{
			$ratingRestrictions = ConfigurationRatingRestriction::where('user_id', '=', null)->where('display', '=', false)->pluck('rating_id')->toArray();
		}
		
		$collections = $collections->whereNotIn('rating_id', $ratingRestrictions);
		return $collections;
	}
}

?>
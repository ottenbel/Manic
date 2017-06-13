<?php

namespace App\Helpers;

use App\Models\TagObjects\Artist\Artist;
use App\Models\TagObjects\Artist\ArtistAlias;
use App\Models\TagObjects\Character\Character;
use App\Models\TagObjects\Character\CharacterAlias;
use App\Models\TagObjects\Series\Series;
use App\Models\TagObjects\Series\SeriesAlias;
use App\Models\TagObjects\Tag\Tag;
use App\Models\TagObjects\Tag\TagAlias;
use App\Models\TagObjects\Scanalator\Scanalator;
use App\Models\TagObjects\Scanalator\ScanalatorAlias;
use Auth;

class LookupHelper
{
	public static function GetArtistByNameOrAlias($name)
	{
		$artistChild = Artist::where('name', '=', $name)->first();
		if ($artistChild != null)
		{
			return $artistChild;
		}
		
		if(Auth::check())
		{
			$artistChild = ArtistAlias::where('user_id', '=', Auth::user()->id)->where('alias', '=', $name)->first();
			if($artistChild != null)
			{
				$artistChild = $artistChild->artist()->first();
				return $artistChild;
			}
		}
		
		$artistChild = ArtistAlias::where('user_id', '=', null)->where('alias', '=', $name)->first();
		if($artistChild != null)
		{
			$artistChild = $artistChild->artist()->first();
			return $artistChild;
		}
		return null;
	}
	
	public static function GetCharacterByNameOrAlias($name)
	{
		$characterChild = Character::where('name', '=', $name)->first();
		if ($characterChild != null)
		{
			return $characterChild;
		}
		
		if (Auth::check())
		{
			$characterChild = CharacterAlias::where('user_id', '=', Auth::user()->id)->where('alias', '=', $name)->first();
			if($characterChild != null)
			{
				$characterChild = $characterChild->character()->first();
				return $characterChild;
			}
		}
		
		$characterChild = CharacterAlias::where('user_id', '=', null)->where('alias', '=', $name)->first();
		if($characterChild != null)
		{
			$characterChild = $characterChild->character()->first();
			return $characterChild;
		}
		
		return null;
	}
	
	public static function GetScanalatorByNameOrAlias($name)
	{
		$scanalatorChild = Scanalator::where('name', '=', $name)->first();
		if ($scanalatorChild != null)
		{
			return $scanalatorChild;
		}
		
		if (Auth::check())
		{
			$scanalatorChild = ScanalatorAlias::where('user_id', '=', Auth::user()->id)->where('alias', '=', $name)->first();
			if($scanalatorChild != null)
			{
				$scanalatorChild = $scanalatorChild->scanalator()->first();
				return $scanalatorChild;
			}
		}
		
		$scanalatorChild = ScanalatorAlias::where('user_id', '=', null)->where('alias', '=', $name)->first();
		if($scanalatorChild != null)
		{
			$scanalatorChild = $scanalatorChild->scanalator()->first();
			return $scanalatorChild;
		}
		
		return null;
	}
	
	public static function GetSeriesByNameOrAlias($name)
	{
		$seriesChild = Series::where('name', '=', $name)->first();
		if ($seriesChild != null)
		{
			return $seriesChild;
		}
		
		if (Auth::check())
		{
			$seriesChild = SeriesAlias::where('user_id', '=', Auth::user()->id)->where('alias', '=', $name)->first();
			if($seriesChild != null)
			{
				$seriesChild = $seriesChild->series()->first();
				return $seriesChild;
			}
		}
		
		$seriesChild = SeriesAlias::where('user_id', '=', null)->where('alias', '=', $name)->first();
		if($seriesChild != null)
		{
			$seriesChild = $seriesChild->series()->first();
			return $seriesChild;
		}
		
		return null;
	}
	
	public static function GetTagByNameOrAlias($name)
	{
		$tagChild = Tag::where('name', '=', $name)->first();
		if ($tagChild != null)
		{
			return $tagChild;
		}
		
		if (Auth::check())
		{
			$tagChild = TagAlias::where('user_id', '=', Auth::user()->id)->where('alias', '=', $name)->first();
			if($tagChild != null)
			{
				$tagChild = $tagChild->tag()->first();
				return $tagChild;
			}
		}		
		
		$tagChild = TagAlias::where('user_id', '=', null)->where('alias', '=', $name)->first();
		if($tagChild != null)
		{
			$tagChild = $tagChild->tag()->first();
			return $tagChild;
		}
		
		return null;
	}
}
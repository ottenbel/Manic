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
	public static function GetArtistByNameOrAlias($name, $nameOnly = false)
	{
		$artistChild = Artist::where('name', '=', $name)->first();
		
		if (Auth::check() && ($artistChild == null))
		{
			$artistChild = ArtistAlias::where('user_id', '=', Auth::user()->id)->where('alias', '=', $name)->first();
			if($artistChild != null)
			{
				$artistChild = $artistChild->artist()->first();
			}
		}
		
		if ($artistChild == null)
		{
			$artistChild = ArtistAlias::where('user_id', '=', null)->where('alias', '=', $name)->first();
			if ($artistChild != null)
			{
				$artistChild = $artistChild->artist()->first();
			}
		}
		
		if ($artistChild != null)
		{
			if ($nameOnly){ return $artistChild->name; }
			else { return $artistChild; }
		}
		else
		{
			if ($nameOnly) { return $name; }
			else { return null; }
		}
	}
	
	public static function GetCharacterByNameOrAlias($name, $nameOnly = false)
	{
		$characterChild = Character::where('name', '=', $name)->first();
		
		if (Auth::check() && ($characterChild == null))
		{
			$characterChild = CharacterAlias::where('user_id', '=', Auth::user()->id)->where('alias', '=', $name)->first();
			if($characterChild != null)
			{
				$characterChild = $characterChild->character()->first();
			}
		}
		
		if ($characterChild == null)
		{
			$characterChild = CharacterAlias::where('user_id', '=', null)->where('alias', '=', $name)->first();
			if($characterChild != null)
			{
				$characterChild = $characterChild->character()->first();
			}
		}
		
		if ($characterChild != null)
		{
			if ($nameOnly){ return $characterChild->name; }
			else { return $characterChild; }
		}
		else
		{
			if ($nameOnly) { return $name; }
			else { return null; }
		}
	}
	
	public static function GetScanalatorByNameOrAlias($name, $nameOnly = false)
	{
		$scanalatorChild = Scanalator::where('name', '=', $name)->first();
		
		if (Auth::check() && ($scanalatorChild == null))
		{
			$scanalatorChild = ScanalatorAlias::where('user_id', '=', Auth::user()->id)->where('alias', '=', $name)->first();
			if($scanalatorChild != null)
			{
				$scanalatorChild = $scanalatorChild->scanalator()->first();
			}
		}
		
		if ($scanalatorChild == null)
		{
			$scanalatorChild = ScanalatorAlias::where('user_id', '=', null)->where('alias', '=', $name)->first();
			if($scanalatorChild != null)
			{
				$scanalatorChild = $scanalatorChild->scanalator()->first();
			}
		}
		
		if ($scanalatorChild != null)
		{
			if ($nameOnly){ return $scanalatorChild->name; }
			else { return $scanalatorChild; }
		}
		else
		{
			if ($nameOnly) { return $name; }
			else { return null; }
		}
	}
	
	public static function GetSeriesByNameOrAlias($name, $nameOnly = false)
	{
		$seriesChild = Series::where('name', '=', $name)->first();
		
		if (Auth::check() && ($seriesChild == null))
		{
			$seriesChild = SeriesAlias::where('user_id', '=', Auth::user()->id)->where('alias', '=', $name)->first();
			if($seriesChild != null)
			{
				$seriesChild = $seriesChild->series()->first();
			}
		}
		
		if ($seriesChild == null)
		{
			$seriesChild = SeriesAlias::where('user_id', '=', null)->where('alias', '=', $name)->first();
			if($seriesChild != null)
			{
				$seriesChild = $seriesChild->series()->first();
			}
		}
		
		if ($seriesChild != null)
		{
			if ($nameOnly){ return $seriesChild->name; }
			else { return $seriesChild; }
		}
		else
		{
			if ($nameOnly) { return $name; }
			else { return null; }
		}
	}
	
	public static function GetTagByNameOrAlias($name, $nameOnly = false)
	{
		$tagChild = Tag::where('name', '=', $name)->first();
		
		if (Auth::check() && ($tagChild == null))
		{
			$tagChild = TagAlias::where('user_id', '=', Auth::user()->id)->where('alias', '=', $name)->first();
			if($tagChild != null)
			{
				$tagChild = $tagChild->tag()->first();
			}
		}		
		
		if ($tagChild == null)
		{
			$tagChild = TagAlias::where('user_id', '=', null)->where('alias', '=', $name)->first();
			if($tagChild != null)
			{
				$tagChild = $tagChild->tag()->first();
			}
		}
		
		if ($tagChild != null)
		{
			if ($nameOnly){ return $tagChild->name; }
			else { return $tagChild; }
		}
		else
		{
			if ($nameOnly) { return $name; }
			else { return null; }
		}
	}
	
	public static function GetArtistName($name)
	{
		return Self::GetArtistByNameOrAlias($name, true);
	}
	
	public static function GetCharacterName($name)
	{
		return Self::GetCharacterByNameOrAlias($name, true);
	}
	
	public static function GetScanalatorName($name)
	{
		return Self::GetScanalatorByNameOrAlias($name, true);
	}
	
	public static function GetSeriesName($name)
	{
		return Self::GetSeriesByNameOrAlias($name, true);
	}
	
	public static function GetTagName($name)
	{
		return Self::GetTagByNameOrAlias($name, true);
	}
	
}
<?php

namespace App\Helpers;

use App\Models\TagObjects\Artist\Artist;
use App\Models\TagObjects\Artist\ArtistAlias;
use App\Models\TagObjects\Character\Character;
use App\Models\TagObjects\Character\CharacterAlias;
use App\Models\TagObjects\Scanalator\Scanalator;
use App\Models\TagObjects\Scanalator\ScanalatorAlias;
use App\Models\TagObjects\Series\Series;
use App\Models\TagObjects\Series\SeriesAlias;
use App\Models\TagObjects\Tag\Tag;
use App\Models\TagObjects\Tag\TagAlias;
use Auth;
use LookupHelper;

class MappingHelper
{
	/*
	 * Map artists and attach them to the corresponding collection.
	 */
	public static function MapArtists(&$collection, $artistArray, $isPrimary)
	{
		$artistArray = array_unique($artistArray);
		
		foreach ($artistArray as $artistName)
		{
			if (trim($artistName) != "")
			{
				$artist = LookupHelper::GetArtistByNameOrAlias($artistName);
				
				if ($artist != null)
				{
					$collection->artists()->attach($artist, ['primary' => $isPrimary]);
				}
				else
				{
					//Create a new artist
					$artist = new Artist;
					$artist->name = $artistName;
					$artist->save();
					
					$collection->artists()->attach($artist, ['primary' => $isPrimary]);
				}
			}
		}
	}
	
	/*
	 * Map characters and attach them to the corresponding collection.
	 */
	public static function MapCharacters(&$collection, $charactersArray, $isPrimary)
	{
		$charactersArray = array_unique($charactersArray);
		
		$missingCharacters = array();
		
		$fullInheritedSeriesCollection = $collection->series()->get();
		
		//Iterate through all series provided AND the full lineage of those series to retrieve the all possible characters that can be used
		for($i = 0; $i < $fullInheritedSeriesCollection->count(); $i++)
		{
			$currentSeries = $fullInheritedSeriesCollection[$i];
			
			foreach($currentSeries->parents()->get() as $parent)
			{
				$fullInheritedSeriesCollection->push($parent);
			}
		}
		
		foreach ($charactersArray as $characterName)
		{
			if (trim($characterName) != "")
			{
				$character = LookupHelper::GetCharacterByNameOrAlias($characterName);
				
				if ($character != null)
				{
					$series = $fullInheritedSeriesCollection->where('id', '=', $character->series_id)->first();
					if ($series != null)
					{
						$collection->characters()->attach($character, ['primary' => $isPrimary]);
					}
					else
					{
						array_push($missingCharacters, trim($characterName));
					}
				}
				else
				{
					array_push($missingCharacters, trim($characterName));
				}
			}
		}
		return $missingCharacters;
	}
	
	/*
	 * Map tags and attach them to the corresponding collection.
	 */
	 public static function MapTags(&$collection, $tagsArray, $isPrimary)
	{
		$tagsArray = array_unique($tagsArray);
		
		foreach ($tagsArray as $tagName)
		{
			if (trim($tagName) != "")
			{
				$tag = LookupHelper::GetTagByNameOrAlias($tagName);
				
				if ($tag != null)
				{
					$collection->tags()->attach($tag, ['primary' => $isPrimary]);
				}
				else
				{
					//Create a new tag
					$tag = new Tag;
					$tag->name = $tagName;
					$tag->save();
					
					$collection->tags()->attach($tag, ['primary' => $isPrimary]);
				}
			}
		}
	}
	
	/*
	 * Map series and attach them to the corresponding collection.
	 */
	public static function MapSeries(&$collection, $seriesArray, $isPrimary)
	{
		$seriesArray = array_unique($seriesArray);
		
		foreach ($seriesArray as $seriesName)
		{
			if (trim($seriesName) != "")
			{
				$series = LookupHelper::GetSeriesByNameOrAlias($seriesName);
			
				if ($series != null)
				{
					$collection->series()->attach($series, ['primary' => $isPrimary]);
				}
				else
				{
					//Create a new series
					$series = new Series;
					$series->name = $seriesName;
					$series->save();
					
					$collection->series()->attach($series, ['primary' => $isPrimary]);
				}
			}
		}
	}
	
	/*
	 * Map scanalators and attach them to the corresponding chapter.
	 */
    public static function MapScanalators(&$chapter, $scanalatorArray, $isPrimary)
	{
		$scanalatorArray = array_unique($scanalatorArray);
		
		foreach ($scanalatorArray as $scanalatorName)
		{
			if (trim($scanalatorName) != "")
			{
				$scanalator = LookupHelper::GetScanalatorByNameOrAlias($scanalatorName);
				
				if ($scanalator != null)
				{
					$chapter->scanalators()->attach($scanalator, ['primary' => $isPrimary]);
				}
				else
				{
					//Create a new scanalator
					$scanalator = new Scanalator;
					$scanalator->name = $scanalatorName;
					$scanalator->save();
					
					$chapter->scanalators()->attach($scanalator, ['primary' => $isPrimary]);
				}
			}
		}
	}
	
	/*
	 * Map artist children to parent. 
	 */
	public static function MapArtistChildren(&$artist, $artistChildrenArray)
	{
		$artistChildrenArray = array_unique($artistChildrenArray);
		
		$loopedChildren = array();
		
		$ancestors = null;
		if($artist->id != null)
		{
			$ancestors = $artist->ancestors();
		}
		
		foreach ($artistChildrenArray as $artistChildName)
		{
			if (trim($artistChildName) != "")
			{
				$artistChild = LookupHelper::GetArtistByNameOrAlias($artistChildName);
				
				if ($artist->id == null)
				{
					if (strtolower($artist->name) == strtolower($artistChildName))
					{
						array_push($loopedChildren, trim($artistChild->name));
					}
				}
				else if (($artistChild != null) && ($artistChild->id == $artist->id))
				{
					array_push($loopedChildren, trim($artistChild->name));
				}
				else if ($artistChild != null)
				{
					$childDescendants = $artistChild->descendants();
					
					$ancestorCount = $ancestors->count();
					$descendantCount = $childDescendants->count();
					
					$merged = $ancestors->merge($childDescendants);
					
					if (($merged->isEmpty()) ||(($merged->count() == ($ancestorCount + $descendantCount)) 
						&& ($merged->where('id', '=', $artist->id)->isEmpty())))
					{
						$artist->children()->attach($artistChild);
					}
					else
					{
						array_push($loopedChildren, trim($artistChild->name));
					}
				}
				else
				{
					//Create child artist
					$artistChild = new Artist;
					$artistChild->name = $artistChildName;
					$artistChild->save();
					
					$artist->children()->attach($artistChild);
				}
			}
		}
		
		return $loopedChildren;
	}

	/*
	 * Map scanalator children to parent. 
	 */
	public static function MapScanalatorChildren(&$scanalator, $scanalatorChildrenArray)
	{
		$scanalatorChildrenArray = array_unique($scanalatorChildrenArray);
		
		$loopedChildren = array();
		
		$ancestors = null;
		if($scanalator->id != null)
		{
			$ancestors = $scanalator->ancestors();
		}
		
		foreach ($scanalatorChildrenArray as $scanalatorChildName)
		{
			if (trim($scanalatorChildName) != "")
			{
				$scanalatorChild = LookupHelper::GetScanalatorByNameOrAlias($scanalatorChildName);
				
				if ($scanalator->id == null)
				{
					if (strtolower($scanalator->name) == strtolower($scanalatorChildName))
					{
						array_push($loopedChildren, trim($scanalatorChild->name));
					}
				}
				else if (($scanalatorChild != null) && ($scanalatorChild->id == $scanalator->id))
				{
					array_push($loopedChildren, trim($scanalatorChild->name));
				}
				else if ($scanalatorChild != null)
				{
					$childDescendants = $scanalatorChild->descendants();
					
					$ancestorCount = $ancestors->count();
					$descendantCount = $childDescendants->count();
					
					$merged = $ancestors->merge($childDescendants);
					
					if (($merged->isEmpty()) || (($merged->count() == ($ancestorCount + $descendantCount)) 
						&& ($merged->where('id', '=', $scanalator->id)->isEmpty())))
					{
						$scanalator->children()->attach($scanalatorChild);
					}
					else
					{
						array_push($loopedChildren, trim($scanalatorChild->name));
					}
				}
				else
				{
					//Create child scanalator
					$scanalatorChild = new Scanalator;
					$scanalatorChild->name = $scanalatorChildName;
					$scanalatorChild->save();
					
					$scanalator->children()->attach($scanalatorChild);
				}
			}
		}		
		return $loopedChildren;
	}
	
	/*
	 * Map tag children to parent. 
	 */
	public static function MapTagChildren(&$tag, $tagChildrenArray)
	{
		$tagChildrenArray = array_unique($tagChildrenArray);
		
		$loopedChildren = array();
		
		$ancestors = null;
		if($tag->id != null)
		{
			$ancestors = $tag->ancestors();
		}
		
		foreach ($tagChildrenArray as $tagChildName)
		{
			if (trim($tagChildName) != "")
			{
				$tagChild = LookupHelper::GetTagByNameOrAlias($tagChildName);
				
				if ($tag->id == null)
				{
					if (strtolower($tag->name) == strtolower($tagChildName))
					{
						array_push($loopedChildren, trim($tagChild->name));
					}
				}
				else if (($tagChild != null) && ($tagChild->id == $tag->id))
				{
					array_push($loopedChildren, trim($tagChild->name));
				}
				else if ($tagChild != null)
				{
					$childDescendants = $tagChild->descendants();
					
					$ancestorCount = $ancestors->count();
					$descendantCount = $childDescendants->count();
					
					$merged = $ancestors->merge($childDescendants);
					
					if (($merged->isEmpty()) || (($merged->count() == ($ancestorCount + $descendantCount)) 
						&& ($merged->where('id', '=', $tag->id)->isEmpty())))
					{
						$tag->children()->attach($tagChild);
					}
					else
					{
						array_push($loopedChildren, trim($tagChild->name));
					}
				}
				else
				{
					//Create child tag
					$tagChild = new Tag;
					$tagChild->name = $tagChildName;
					$tagChild->save();
					
					$tag->children()->attach($tagChild);
				}
			}
		}		
		return $loopedChildren;
	}
	
	/*
	 * Map series children to parent. 
	 */
	public static function MapSeriesChildren(&$series, $seriesChildrenArray, $lockedChildren)
	{
		$seriesChildrenArray = array_unique($seriesChildrenArray);
		
		$loopedChildren = array();
		
		$ancestors = null;
		if($series->id != null)
		{
			$ancestors = $series->ancestors();
		}
		
		if ($lockedChildren->count() > 0)
		{
			foreach ($lockedChildren as $lockedChild)
			{
				$series->children()->attach($lockedChild);
			}
		}
		
		foreach ($seriesChildrenArray as $seriesChildName)
		{
			if (trim($seriesChildName) != "")
			{
				$seriesChild = LookupHelper::GetSeriesByNameOrAlias($seriesChildName);
				
				if ($series->id == null)
				{
					if (strtolower($series->name) == strtolower($seriesChildName))
					{
						array_push($loopedChildren, trim($seriesChild->name));
					}
				}
				else if (($seriesChild != null) && ($seriesChild->id == $series->id))
				{
					array_push($loopedChildren, trim($seriesChild->name));
				}
				else if ($seriesChild != null)
				{
					$childDescendants = $seriesChild->descendants();
					
					$ancestorCount = $ancestors->count();
					$descendantCount = $childDescendants->count();
					
					$merged = $ancestors->merge($childDescendants);
					
					if (($merged->isEmpty()) || (($merged->count() == ($ancestorCount + $descendantCount))  
						&& ($merged->where('id', '=', $series->id)->isEmpty())))
					{
						$series->children()->attach($seriesChild);
					}
					else
					{
						array_push($loopedChildren, trim($seriesChild->name));
					}
				}
				else
				{
					//Create child series
					$seriesChild = new Series;
					$seriesChild->name = $seriesChildName;
					$seriesChild->save();
					
					$series->children()->attach($seriesChild);
				}
			}
		}		
		return $loopedChildren;
	}
	
	/*
	 * Map tag children to parent. 
	 */
	public static function MapCharacterChildren(&$character, $characterChildrenArray, &$droppedChildren)
	{
		$characterChildrenArray = array_unique($characterChildrenArray);
		
		$loopedChildren = array();
		
		$ancestors = null;
		if($character->id != null)
		{
			$ancestors = $character->ancestors();
		}
		
		$eligibleSeries = $character->series->descendants();
		$eligibleSeries = $eligibleSeries->push($character->series);
		
		foreach ($characterChildrenArray as $characterChildName)
		{
			if (trim($characterChildName) != "")
			{
				$characterChild = LookupHelper::GetCharacterByNameOrAlias($characterChildName);
				
				if ($character->id == null)
				{
					if (strtolower($character->name) == strtolower($characterChild->name))
					{
						array_push($loopedChildren, trim($characterChild->name));
					}
				}
				else if (($characterChild != null) && ($characterChild->id == $character->id))
				{
					array_push($loopedChildren, trim($characterChild->name));
				}
				else if ($characterChild != null)
				{
					$childDescendants = $characterChild->descendants();
					
					$ancestorCount = $ancestors->count();
					$descendantCount = $childDescendants->count();
					
					$merged = $ancestors->merge($childDescendants);
					
					if (($merged->isEmpty()) || (($merged->count() == ($ancestorCount + $descendantCount))  
						&& ($merged->where('id', '=', $character->id)->isEmpty())))
					{						
						if ($eligibleSeries->where('id', '=', $characterChild->series_id)->first() != null)
						{
							$character->children()->attach($characterChild);
						}
						else
						{
							array_push($droppedChildren, trim($characterChild->name));
						}
					}
					else
					{
						array_push($loopedChildren, trim($characterChild->name));
					}
				}
				else
				{
					array_push($droppedChildren, trim($characterChildName));
				}
			}
		}		
		return $loopedChildren;
	}
}
?>
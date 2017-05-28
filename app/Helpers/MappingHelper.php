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
use LookupHelper;

class MappingHelper
{
	/*
	 * Map artists and attach them to the corresponding collection.
	 */
	public static function MapArtists(&$collection, $artist_array, $isPrimary)
	{
		foreach ($artist_array as $artist_name)
		{
			if (trim($artist_name) != "")
			{
				$artist = LookupHelper::GetArtistByNameOrAlias($artist_name);
				
				if ($artist != null)
				{
					$collection->artists()->attach($artist, ['primary' => $isPrimary]);
				}
				else
				{
					//Create a new artist
					$artist = new Artist;
					$artist->name = $artist_name;
					$artist->save();
					
					$collection->artists()->attach($artist, ['primary' => $isPrimary]);
				}
			}
		}
	}
	
	/*
	 * Map characters and attach them to the corresponding collection.
	 */
	public static function MapCharacters(&$collection, $characters_array, $isPrimary)
	{
		$missing_characters = array();
		
		foreach ($characters_array as $character_name)
		{
			if (trim($character_name) != "")
			{
				$character = LookupHelper::GetCharacterByNameOrAlias($character_name);
				
				if ($character != null)
				{
					$series = $collection->series->where('id', '=', $character->series_id)->first();
					if ($series != null)
					{
						$collection->characters()->attach($character, ['primary' => $isPrimary]);
					}
					else
					{
						array_push($missing_characters, trim($character_name));
					}
				}
				else
				{
					array_push($missing_characters, trim($character_name));
				}
			}
		}
		return $missing_characters;
	}
	
	/*
	 * Map tags and attach them to the corresponding collection.
	 */
	 public static function MapTags(&$collection, $tags_array, $isPrimary)
	{
		foreach ($tags_array as $tag_name)
		{
			if (trim($tag_name) != "")
			{
				$tag = LookupHelper::GetTagByNameOrAlias($tag_name);
				
				if ($tag != null)
				{
					$collection->tags()->attach($tag, ['primary' => $isPrimary]);
				}
				else
				{
					//Create a new tag
					$tag = new Tag;
					$tag->name = $tag_name;
					$tag->save();
					
					$collection->tags()->attach($tag, ['primary' => $isPrimary]);
				}
			}
		}
	}
	
	/*
	 * Map series and attach them to the corresponding collection.
	 */
	public static function MapSeries(&$collection, $series_array, $isPrimary)
	{
		foreach ($series_array as $series_name)
		{
			if (trim($series_name) != "")
			{
				$series = LookupHelper::GetSeriesByNameOrAlias($series_name);
			
				if ($series != null)
				{
					$collection->series()->attach($series, ['primary' => $isPrimary]);
				}
				else
				{
					//Create a new series
					$series = new Series;
					$series->name = $series_name;
					$series->save();
					
					$collection->series()->attach($series, ['primary' => $isPrimary]);
				}
			}
		}
	}
	
	/*
	 * Map scanalators and attach them to the corresponding chapter.
	 */
    public static function MapScanalators(&$chapter, $scanalator_array, $isPrimary)
	{
		foreach ($scanalator_array as $scanalator_name)
		{
			if (trim($scanalator_name) != "")
			{
				$scanalator = LookupHelper::GetScanalatorByNameOrAlias($scanalator_name);
				
				if ($scanalator != null)
				{
					$chapter->scanalators()->attach($scanalator, ['primary' => $isPrimary]);
				}
				else
				{
					//Create a new scanalator
					$scanalator = new Scanalator;
					$scanalator->name = $scanalator_name;
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
		$loopedChildren = array();
		
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
					$causedLoop = false;
					$children = $artistChild->children()->get();
					for ($i = 0; $i < $children->count(); $i++)
					{
						$child = $children[$i];
						//Check if the current child is the parent artist
						if ($artist->id != null)
						{
							if ($artist->id == $child->id)
							{
								array_push($loopedChildren, trim($artistChildName));
								$causedLoop = true;
								break;
							}
							
							//Continue to iterate through all descendants to avoid introducing loops in artist implication
							if ($child->children->count() > 0)
							{
								$children = $children->merge($child->children()->get());
							}
						}
						else
						{
							$artist->children()->attach($artistChild);
							break;
						}
					}
					
					if (!($causedLoop))
					{
						$artist->children()->attach($artistChild);
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
		$loopedChildren = array();
		
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
					$causedLoop = false;
					$children = $scanalatorChild->children()->get();
					for ($i = 0; $i < $children->count(); $i++)
					{
						$child = $children[$i];
						//Check if the current child is the parent scanalator
						if ($scanalator->id != null)
						{
							if ($scanalator->id == $child->id)
							{
								array_push($loopedChildren, trim($scanalatorChildName));
								$causedLoop = true;
								break;
							}
							
							//Continue to iterate through all descendants to avoid introducing loops in scanalator implication
							if ($child->children->count() > 0)
							{
								$children = $children->merge($child->children()->get());
							}
						}
						else
						{
							$scanalator->children()->attach($scanalatorChild);
							break;
						}
					}
					
					if (!($causedLoop))
					{
						$scanalator->children()->attach($scanalatorChild);
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
		$loopedChildren = array();
		
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
					$causedLoop = false;
					$children = $tagChild->children()->get();
					for ($i = 0; $i < $children->count(); $i++)
					{
						$child = $children[$i];
						//Check if the current child is the parent tag
						if ($tag->id != null)
						{
							if ($tag->id == $child->id)
							{
								array_push($loopedChildren, trim($tagChildName));
								$causedLoop = true;
								break;
							}
							
							//Continue to iterate through all descendants to avoid introducing loops in tag implication
							if ($child->children->count() > 0)
							{
								$children = $children->merge($child->children()->get());
							}
						}
						else
						{
							$tag->children()->attach($tagChild);
							break;
						}
					}
					
					if (!($causedLoop))
					{
						$tag->children()->attach($tagChild);
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
		$loopedChildren = array();
		
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
					$causedLoop = false;
					$children = $seriesChild->children()->get();
					for ($i = 0; $i < $children->count(); $i++)
					{
						$child = $children[$i];
						//Check if the current child is the parent tag
						if ($series->id != null)
						{
							if ($series->id == $child->id)
							{
								array_push($loopedChildren, trim($seriesChildName));
								$causedLoop = true;
								break;
							}
							
							//Continue to iterate through all descendants to avoid introducing loops in tag implication
							if ($child->children->count() > 0)
							{
								$children = $children->merge($child->children()->get());
							}
						}
						else
						{
							if ($series->children()->where("child_id", "=", $seriesChild->id)->count() == 0)
							{
								$series->children()->attach($seriesChild);
							}
							break;
						}
					}
					
					if (!($causedLoop))
					{
						$series->children()->attach($seriesChild);
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
}
?>
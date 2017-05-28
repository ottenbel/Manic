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
				$artist = Artist::where('name', '=', $artist_name)->first();
				$personal_alias = ArtistAlias::where('user_id', '=', Auth::user()->id)->where('alias', '=', $artist_name)->first();
				$global_alias = ArtistAlias::where('user_id', '=', null)->where('alias', '=', $artist_name)->first();
				if ($artist != null)
				{
					$collection->artists()->attach($artist, ['primary' => $isPrimary]);
				}
				else if ($personal_alias != null)
				{
					$artist = Artist::where('id', '=', $personal_alias->artist_id)->first();
					$collection->artists()->attach($artist, ['primary' => $isPrimary]);
				}
				else if ($global_alias != null)
				{
					$artist = Artist::where('id', '=', $global_alias->artist_id)->first();
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
				$character = Character::where('name', '=', $character_name)->first();
				$personal_alias = CharacterAlias::where('user_id', '=', Auth::user()->id)->where('alias', '=', $character_name)->first();
				$global_alias = CharacterAlias::where('user_id', '=', null)->where('alias', '=', $character_name)->first();
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
				else if ($personal_alias != null)
				{
					$character = Character::where('id', '=', $personal_alias->character_id)->first();
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
				else if ($global_alias != null)
				{
					$character = Character::where('id', '=', $global_alias->character_id)->first();
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
				$tag = Tag::where('name', '=', $tag_name)->first();
				$personal_alias = TagAlias::where('user_id', '=', Auth::user()->id)->where('alias', '=', $tag_name)->first();
				$global_alias = TagAlias::where('user_id', '=', null)->where('alias', '=', $tag_name)->first();
				if ($tag != null)
				{
					$collection->tags()->attach($tag, ['primary' => $isPrimary]);
				}
				else if ($personal_alias != null)
				{
					$tag = Tag::where('id', '=', $personal_alias->tag_id)->first();
					$collection->tags()->attach($tag, ['primary' => $isPrimary]);
				}
				else if ($global_alias != null)
				{
					$tag = Tag::where('id', '=', $global_alias->tag_id)->first();
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
				$series = Series::where('name', '=', $series_name)->first();
				$personal_alias = SeriesAlias::where('user_id', '=', Auth::user()->id)->where('alias', '=', $series_name)->first();
				$global_alias = SeriesAlias::where('user_id', '=', null)->where('alias', '=', $series_name)->first();
				
				if ($series != null)
				{
					$collection->series()->attach($series, ['primary' => $isPrimary]);
				}
				else if ($personal_alias != null)
				{
					$series = Series::where('id', '=', $personal_alias->series_id)->first();
					$collection->series()->attach($series, ['primary' => $isPrimary]);
				}
				else if ($global_alias != null)
				{
					$series = Series::where('id', '=', $global_alias->series_id)->first();
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
				$scanalator = Scanalator::where('name', '=', $scanalator_name)->first();
				$personal_alias = ScanalatorAlias::where('user_id', '=', Auth::user()->id)->where('alias', '=', $scanalator_name)->first();
				$global_alias = ScanalatorAlias::where('user_id', '=', null)->where('alias', '=', $scanalator_name)->first();
				if ($scanalator != null)
				{
					$chapter->scanalators()->attach($scanalator, ['primary' => $isPrimary]);
				}
				else if ($personal_alias != null)
				{
					$scanalator = Scanalator::where('id', '=', $personal_alias->scanalator_id)->first();
					$chapter->scanalators()->attach($scanalator, ['primary' => $isPrimary]);
				}
				else if ($global_alias != null)
				{
					$scanalator = Scanalator::where('id', '=', $global_alias->scanalator_id)->first();
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
				$artistChild = Artist::where('name', '=', $artistChildName)->first();
				
				if ($artistChild != null)
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
				$scanalatorChild = Scanalator::where('name', '=', $scanalatorChildName)->first();
				
				if ($scanalatorChild != null)
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
				$tagChild = Tag::where('name', '=', $tagChildName)->first();
				
				if ($tagChild != null)
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
				$seriesChild = Series::where('name', '=', $seriesChildName)->first();
				
				if ($seriesChild != null)
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
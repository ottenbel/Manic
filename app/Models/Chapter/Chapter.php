<?php

namespace App\Models\Chapter;

use App\Models\BaseManicModel;
use Illuminate\Support\Facades\Cache;

class Chapter extends BaseManicModel
{
	//Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'chapters';
	protected $fillable = ['chapter_number', 'name', 'source'];
	
	public static function boot()
    {
        parent::boot();
    }
	
	/*
	 * Get the pages associated with the chapter.
	 */
	public function pages()
	{
		return $this->belongsToMany('App\Models\Image')->withTimestamps()->withPivot('page_number')->orderBy('page_number');
	}
	
	/*
	 * Get the volume that the chapter is associated with.
	 */
	public function volume()
	{
		return $this->belongsTo('App\Models\Volume\Volume');
	}
	
	/*
	 * Get the parent collection associated with the chapter.
	 */
	public function collection()
	{
		return $this->volume->collection();
	}
	
	/*
	 * Get the mapping from collection to scanalators.
	 */	 
	public function scanalators()
	{
		return $this->belongsToMany('App\Models\TagObjects\Scanalator\Scanalator')->withTimestamps()->withPivot('primary');
	}
	
	/*
	 * Get mapping from collection to primary scanalators.
	 */
	public function primary_scanalators()
	{
		return $this->belongsToMany('App\Models\TagObjects\Scanalator\Scanalator')->withTimestamps()->withPivot('primary')->where('primary', '=', true);
	}
	
	/*
	 * Get mapping from collection to secondary scanalators.
	 */
	public function secondary_scanalators()
	{
		return $this->belongsToMany('App\Models\TagObjects\Scanalator\Scanalator')->withTimestamps()->withPivot('primary')->where('primary', '=', false);
	}
	
	/*
	 * Get the next chapter in the collection.
	 */
	public function next_chapter()
	{
		$nextChapter = Cache::tags([ $this->volume->collection->id . 'previous_chapter'])->rememberForever($this->id . "next_chapter", function () {
			return $this->volume->collection->chapters()->where('chapter_number', '>', $this->chapter_number)->orderBy('chapter_number', 'asc')->first();
		});

		return $nextChapter;
	}
	
	/*
	 * Get the previous chapter in the collection.
	 */
	public function previous_chapter()
	{
		$previousChapter = Cache::tags([ $this->volume->collection->id . 'next_chapter'])->rememberForever($this->id . "next_chapter", function () {
			return $this->volume->collection->chapters()->where('chapter_number', '<', $this->chapter_number)->orderBy('chapter_number', 'desc')->first();
		});

		return $previousChapter;
	}
	
	/*
	 * Get the export associated with the chapter.
	 */
	public function export()
	{
		return $this->hasOne('App\Models\Chapter\ChapterExport');
	}

	public function PrimaryScanalatorsToString()
	{
		return collect($this->primary_scanalators()->pluck('name'))->implode(", ");
	}

	public function SecondaryScanalatorsToString()
	{
		return collect($this->secondary_scanalators()->pluck('name'))->implode(", ");
	}
}

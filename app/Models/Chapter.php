<?php

namespace App\Models;

use App\Models\BaseManicModel;
use Auth;

class Chapter extends BaseManicModel
{
	//Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'chapters';
	
	public static function boot()
    {
        parent::boot();
		
		static::creating(function($model)
		{
			parent::creating($model);
			
			$volume = $model->volume()->first();
			$volume->updated_by = Auth::user()->id;
			$volume->save();
			$volume->touch();
			
			$collection = $volume->collection()->first();
			$collection->updated_by = Auth::user()->id;
			$collection->save();
			$collection->touch();
		});
		
		static::deleting(function($model)
		{
			parent::deleting($model);
			
			$volume = $model->volume()->first();
			$volume->updated_by = Auth::user()->id;
			$volume->save();
			$volume->touch();
			
			$collection = $volume->collection()->first();
			$collection->updated_by = Auth::user()->id;
			$collection->save();
			$collection->touch();
		});
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
		return $this->belongsTo('App\Models\Volume');
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
		return $this->volume->collection->chapters()->where('chapter_number', '>', $this->chapter_number)->orderBy('chapter_number', 'asc')->take(1);
	}
	
	/*
	 * Get the previous chapter in the collection.
	 */
	public function previous_chapter()
	{
		return $this->volume->collection->chapters()->where('chapter_number', '<', $this->chapter_number)->orderBy('chapter_number', 'desc')->take(1);
	}
}

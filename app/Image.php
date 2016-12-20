<?php

namespace App;

use App\BaseManicModel;

class Image extends BaseManicModel
{
	//Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'images';
	
	/*
	 * Get any collections that use this image as a cover image.
	 */
	 public function collections()
	 {
		$this->hasMany('App\Collection', 'cover'); 
	 }
	 
	 /*
	  * Get any pages that use this image as a page image.
	  */
	 public function pages()
	 {
		 $this->hasMany('App\Page', '')
	 }
}

<?php

namespace App;

use App\CollectionAssociatedTagObjectModel;

class Artist extends CollectionAssociatedTagObjectModel
{
	//Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'artists';
}

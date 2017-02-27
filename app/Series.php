<?php

namespace App;

use App\CollectionAssociatedTagObjectModel;

class Series extends CollectionAssociatedTagObjectModel
{
    //Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'series';
}

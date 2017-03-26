<?php

namespace App\Models\TagObjects\Tag;

use App\Models\TagObjects\CollectionAssociatedTagObjectModel;

class Tag extends CollectionAssociatedTagObjectModel
{
    //Manually set the table name as we are extending a custom model instead of the eloquent one
    protected $table = 'tags';
}

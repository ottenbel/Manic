<?php

namespace App\Http\Controllers\API\V1\Tag;

use App\Http\Controllers\Controller;
use App\Models\TagObjects\Tag\Tag;
use Illuminate\Http\Request;
use DB;
use Input;

class TagSearchAPIController extends Controller
{
	/*
	 * Internal search API (find tags by name).
	 */
	public function SearchByName(Request $request)
	{
		$searchString = trim(Input::get('searchString'));
		$tags = Tag::where('name', 'like', '%' . $searchString . '%')->leftjoin('collection_tag', 'tags.id', '=', 'collection_tag.tag_id')->select('tags.*', DB::raw('count(*) as total'))->groupBy('name')->orderBy('total', 'desc')->take(5)->pluck('name');
		
		$tags = $tags->sortBy('name');
		
		$tagsList = array();
		foreach ($tags as $tag)
		{
			array_push($tagsList, ['value' => $tag, 'label' => $tag]);
		}
		
		return $tagsList;
	}    
}

<?php

namespace App\Http\Controllers\API\V1\Tag;

use App\Http\Controllers\Controller;
use App\Models\TagObjects\Tag\Tag;
use App\Models\TagObjects\Tag\TagAlias;
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
		
		//Add personal aliases to pluck list if tags don't exit ('figure out how this works for an API call)
		
		//Add global series to pluck list if tags don't exist
		if (count($tags) < 5)
		{
			$global_aliases = TagAlias::where('user_id', '=', null)->where('alias', 'like', '%' . $searchString . '%')->orderBy('alias', 'asc')->take(5 - count($tags))->pluck('alias');
			
			foreach ($global_aliases as $alias)
			{
				$tags->put('name', $alias);
			}
		}
		
		$tags = $tags->sortBy('name');
		
		$tagsList = array();
		foreach ($tags as $tag)
		{
			array_push($tagsList, ['value' => $tag, 'label' => $tag]);
		}
		
		return $tagsList;
	}    
}

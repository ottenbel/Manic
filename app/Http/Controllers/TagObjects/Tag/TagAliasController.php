<?php

namespace App\Http\Controllers\TagObjects\Tag;

use App\Http\Controllers\TagObjects\TagObjectAliasController;
use Illuminate\Http\Request;
use App\Models\TagObjects\Tag\Tag;
use App\Models\TagObjects\Tag\TagAlias;
use App\Http\Requests\TagObjects\Tag\Alias\StoreTagAliasRequest;

class TagAliasController extends TagObjectAliasController
{
	public function __construct()
    {
		parent::__construct();
		
		$this->paginationKey = "pagination_tag_aliases_per_page_index";
		
		$this->middleware('auth')->except('index');
		$this->middleware('permission:Create Personal Tag Alias|Create Global Tag Alias')->only(['create', 'store']);
		$this->middleware('permission:Delete Personal Tag Alias|Delete Global Tag Alias')->only('destroy');
	}
	
    public function index(Request $request)
    {
		$aliases = new TagAlias();
		return $this->GetAliasIndex($request, $aliases, $this->paginationKey, 'tags');
    }

    public function store(StoreTagAliasRequest $request, Tag $tag)
    {
        $alias = new TagAlias();
		return $this->StoreAlias($request, $alias, $tag, 'tag_id', 'tag', 'show_tag');
    }

    public function destroy(TagAlias $tagAlias)
    {
		$this->authorize($tagAlias);
        return $this->DeleteAlias($tagAlias, 'tag_id', 'tag', 'show_tag');
    }
}
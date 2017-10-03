<?php

namespace App\Http\Controllers\TagObjects\Tag;

use App\Http\Controllers\TagObjects\TagObjectAliasController;
use Illuminate\Http\Request;
use App\Models\TagObjects\Tag\Tag;
use App\Models\TagObjects\Tag\TagAlias;
use App\Http\Requests\TagObjects\Tag\Alias\StoreTagAliasRequest;

class TagAliasController extends TagObjectAliasController
{
    public function index(Request $request)
    {
		$aliases = new TagAlias();
		return self::GetAliasIndex($request, $aliases, 'tagAliasesPerPageIndex', 'tags');
    }

    public function store(StoreTagAliasRequest $request, Tag $tag)
    {
        $alias = new TagAlias();
		return self::StoreAlias($request, $alias, $tag, 'tag_id', 'tag', 'show_tag');
    }

    public function destroy(TagAlias $tagAlias)
    {
		$this->authorize($tagAlias);
        return self::DeleteAlias($tagAlias, 'tag_id', 'tag', 'show_tag');
    }
}
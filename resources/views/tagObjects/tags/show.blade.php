@extends('layouts.app')

@section('title')
	{{$tag->name}}
@endsection

@section('head')
	<script src="/js/confirmdelete.js"></script>
@endsection

@section('content')
	@include('partials.tagObjects.show-content', 
	[
		'tagObject' => $tag, 
		'tagObjectName' => 'tag', 
		'titleTagObjectName' => 'Tag', 
		'associatedType' => 'collections', 
		'aliasTagObjectName' => 'tagAlias', 
		'showRoute' => 'show_tag',
		'deleteRoute' => 'delete_tag',		
		'storeAliasRoute' => 'store_tag_alias',
		'deleteTagObjectRoute' => 'delete_tag_alias',
		'globalAliasDisplayClass' => 'global_tag_alias', 
		'personalAliasDisplayClass' => 'personal_tag_alias', 
		'primaryTagObjectDisplayClass' => 'primary_tags', 
		'secondaryTagObjectDisplayClass' => 'secondary_tags',
		'tagObjectCountClass' => 'tag_count',
		'classAliasModelPath' => App\Models\TagObjects\Tag\TagAlias::class
	])
@endsection

@section('footer')

@endsection
@extends('layouts.app')

@section('title')
	Tag Aliases - Page {{$aliases->currentPage()}}
@endsection

@section('head')

@endsection

@section('content')
	@include('partials.tagObjects.alias.index-content', ['tagObjectName' => 'tag', 'tagObjectNames' => 'tags', 'indexAliasRoute' => 'index_tag_alias', 'showRoute' => 'show_tag', 'globalAliasDisplayClass' => 'global_tag_alias', 'personalAliasDisplayClass' => 'personal_tag_alias', 'classAliasModelPath' => App\Models\TagObjects\Tag\TagAlias::class])
@endsection

@section('footer')

@endsection
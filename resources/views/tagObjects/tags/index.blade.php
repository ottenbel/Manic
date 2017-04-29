@extends('layouts.app')

@section('title')
	Tags - Page {{$tags->currentPage()}}
@endsection

@section('head')

@endsection

@section('content')
	@include('partials.tagObjects.index-content', ['tagObjects' => $tags, 'tagObjectName' => 'tag', 'titleTagObjectName' => 'Tag', 'associatedType' => 'collection', 'indexRoute' => 'index_tag', 'showRoute' => 'show_tag', 'createRoute' => 'create_tag', 'tagDisplayClass' => "primary_tags", 'tagDisplayCountClass' => "tag_count", 'classModelPath' => App\Models\TagObjects\Tag\Tag::class])
@endsection

@section('footer')

@endsection
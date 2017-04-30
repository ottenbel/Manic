@extends('layouts.app')

@section('title')
	Series Aliases - Page {{$aliases->currentPage()}}
@endsection

@section('head')

@endsection

@section('content')
	@include('partials.tagObjects.alias.index-content', ['tagObjectName' => 'series', 'tagObjectNames' => 'series', 'indexAliasRoute' => 'index_series_alias', 'showRoute' => 'show_series', 'globalAliasDisplayClass' => 'global_series_alias', 'personalAliasDisplayClass' => 'personal_series_alias', 'classAliasModelPath' => App\Models\TagObjects\Series\SeriesAlias::class])
@endsection

@section('footer')

@endsection
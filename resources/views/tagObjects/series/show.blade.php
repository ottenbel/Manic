@extends('layouts.app')

@section('title')
	{{$series->name}}
@endsection

@section('head')
	<script src="/js/confirmdelete.js"></script>
@endsection

@section('content')
	@include('partials.tagObjects.show-content', 
	[
		'tagObject' => $series, 
		'tagObjectName' => 'series', 
		'titleTagObjectName' => 'Series', 
		'associatedType' => 'collections', 
		'aliasTagObjectName' => 'seriesAlias', 
		'showRoute' => 'show_series', 
		'deleteRoute' => 'delete_series',
		'storeAliasRoute' => 'store_series_alias',
		'deleteTagObjectRoute' => 'delete_series_alias',
		'globalAliasDisplayClass' => 'global_series_alias', 
		'personalAliasDisplayClass' => 'personal_series_alias', 
		'primaryTagObjectDisplayClass' => 'primary_series', 
		'secondaryTagObjectDisplayClass' => 'secondary_series',
		'tagObjectCountClass' => 'series_count',
		'classAliasModelPath' => App\Models\TagObjects\Series\SeriesAlias::class
	])
@endsection

@section('footer')

@endsection
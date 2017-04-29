@extends('layouts.app')

@section('title')
	{{$series->name}}
@endsection

@section('head')

@endsection

@section('content')
	@include('partials.tagObjects.show-content', ['tagObject' => $series, 'tagObjectName' => 'series', 'titleTagObjectName' => 'Series', 'associatedType' => 'collections', 'showRoute' => 'show_series', 'storeAliasRoute' => 'store_series_alias', 'globalAliasDisplayClass' => 'global_series_alias', 'personalAliasDisplayClass' => 'personal_series_alias', 'classAliasModelPath' => App\Models\TagObjects\Series\SeriesAlias::class])
@endsection

@section('footer')

@endsection
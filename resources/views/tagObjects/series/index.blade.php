@extends('layouts.app')

@section('title')
	Series - Page {{$series->currentPage()}}
@endsection

@section('head')

@endsection

@section('content')
	@include('partials.tagObjects.index-content', ['tagObjects' => $series, 'tagObjectName' => 'series', 'titleTagObjectName' => 'Series', 'associatedType' => 'collection', 'indexRoute' => 'index_series', 'showRoute' => 'show_series', 'createRoute' => 'create_series', 'tagDisplayClass' => "primary_series", 'tagDisplayCountClass' => "series_count", 'classModelPath' => App\Models\TagObjects\Series\Series::class])
@endsection

@section('footer')

@endsection
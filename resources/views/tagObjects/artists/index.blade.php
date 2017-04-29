@extends('layouts.app')

@section('title')
	Artists - Page {{$artists->currentPage()}}
@endsection

@section('head')

@endsection

@section('content')
	@include('partials.tagObjects.index-content', ['tagObjects' => $artists, 'tagObjectName' => 'artist', 'tagObjectNames' => 'artists', 'titleTagObjectName' => 'Artist', 'associatedType' => 'collection', 'indexRoute' => 'index_artist', 'showRoute' => 'show_artist', 'createRoute' => 'create_artist', 'tagDisplayClass' => "primary_artists", 'tagDisplayCountClass' => "artist_count", 'classModelPath' => App\Models\TagObjects\Artist\Artist::class])	
@endsection

@section('footer')

@endsection
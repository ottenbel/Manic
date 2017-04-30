@extends('layouts.app')

@section('title')
	Artist Aliases - Page {{$aliases->currentPage()}}
@endsection

@section('head')

@endsection

@section('content')
	@include('partials.tagObjects.alias.index-content', ['tagObjectName' => 'artist', 'tagObjectNames' => 'artists', 'indexAliasRoute' => 'index_artist_alias', 'showRoute' => 'show_artist', 'globalAliasDisplayClass' => 'global_artist_alias', 'personalAliasDisplayClass' => 'personal_artist_alias', 'classAliasModelPath' => App\Models\TagObjects\Artist\ArtistAlias::class])
@endsection

@section('footer')

@endsection
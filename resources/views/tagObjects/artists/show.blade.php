@extends('layouts.app')

@section('title')
	{{$artist->name}}
@endsection

@section('head')

@endsection

@section('content')
	@include('partials.tagObjects.show-content', ['tagObject' => $artist, 'tagObjectName' => 'artist', 'titleTagObjectName' => 'Artist', 'associatedType' => 'collections', 'showRoute' => 'show_artist', 'storeAliasRoute' => 'store_artist_alias', 'globalAliasDisplayClass' => 'global_artist_alias', 'personalAliasDisplayClass' => 'personal_artist_alias', 'classAliasModelPath' => App\Models\TagObjects\Artist\ArtistAlias::class])
@endsection

@section('footer')

@endsection
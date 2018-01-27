@extends('layouts.app')

@section('title')
	{{$artist->name}}
@endsection

@section('head')
	<script src="/js/confirmdelete.js"></script>
@endsection

@section('content')
	@include('partials.tagObjects.show-content', 
	[
		'tagObject' => $artist, 
		'tagObjectName' => 'artist', 
		'titleTagObjectName' => 'Artist', 
		'associatedType' => 'collections', 
		'aliasTagObjectName' => 'artistAlias', 
		'showRoute' => 'show_artist', 
		'deleteRoute' => 'delete_artist',
		'storeAliasRoute' => 'store_artist_alias',
		'deleteTagObjectRoute' => 'delete_artist_alias',
		'globalAliasDisplayClass' => 'global_artist_alias', 
		'personalAliasDisplayClass' => 'personal_artist_alias', 
		'primaryTagObjectDisplayClass' => 'primary_artists',		
		'secondaryTagObjectDisplayClass' => 'secondary_artists',
		'tagObjectCountClass' => 'artist_count',
		'classAliasModelPath' => App\Models\TagObjects\Artist\ArtistAlias::class
	])
@endsection

@section('footer')

@endsection
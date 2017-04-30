@extends('layouts.app')

@section('title')
	Character Aliases - Page {{$aliases->currentPage()}}
@endsection

@section('head')

@endsection

@section('content')
	@include('partials.tagObjects.alias.index-content', ['tagObjectName' => 'character', 'tagObjectNames' => 'characters', 'indexAliasRoute' => 'index_character_alias', 'showRoute' => 'show_character', 'globalAliasDisplayClass' => 'global_character_alias', 'personalAliasDisplayClass' => 'personal_character_alias', 'classAliasModelPath' => App\Models\TagObjects\Character\CharacterAlias::class])
@endsection

@section('footer')

@endsection
@extends('layouts.app')

@section('title')
	{{$character->name}}
@endsection

@section('head')
	<script src="/js/confirmdelete.js"></script>
@endsection

@section('content')
	@include('partials.tagObjects.show-content', ['tagObject' => $character, 'tagObjectName' => 'character', 'titleTagObjectName' => 'Character', 'associatedType' => 'collections', 'aliasTagObjectName' => 'characterAlias', 'showRoute' => 'show_character', 'storeAliasRoute' => 'store_character_alias', 'globalAliasDisplayClass' => 'global_character_alias', 'personalAliasDisplayClass' => 'personal_character_alias', 'deleteTagObjectRoute' => 'delete_character_alias', 'classAliasModelPath' => App\Models\TagObjects\Character\CharacterAlias::class])
@endsection

@section('footer')

@endsection
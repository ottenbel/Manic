@extends('layouts.app')

@section('title')
	Characters - Page {{$characters->currentPage()}}
@endsection

@section('head')

@endsection

@section('content')
	@include('partials.tagObjects.index-content', ['tagObjects' => $characters, 'tagObjectName' => 'character', 'titleTagObjectName' => 'Character', 'associatedType' => 'collection', 'indexRoute' => 'index_character', 'showRoute' => 'show_character', 'createRoute' => 'create_character', 'tagDisplayClass' => "primary_characters", 'tagDisplayCountClass' => "character_count", 'classModelPath' => App\Models\TagObjects\Character\Character::class])
@endsection

@section('footer')

@endsection
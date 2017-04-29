@extends('layouts.app')

@section('title')
	Scanalators - Page {{$scanalators->currentPage()}}
@endsection

@section('head')

@endsection

@section('content')
	@include('partials.tagObjects.index-content', ['tagObjects' => $scanalators, 'tagObjectName' => 'scanalator', 'tagObjectNames' => 'scanalators', 'titleTagObjectName' => 'Scanalator', 'associatedType' => 'chapter', 'indexRoute' => 'index_scanalator', 'showRoute' => 'show_scanalator', 'createRoute' => 'create_scanalator', 'tagDisplayClass' => "primary_scanalators", 'tagDisplayCountClass' => "scanalator_count", 'classModelPath' => App\Models\TagObjects\Scanalator\Scanalator::class])
@endsection

@section('footer')

@endsection
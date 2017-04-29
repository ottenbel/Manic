@extends('layouts.app')

@section('title')
	{{$scanalator->name}}
@endsection

@section('head')

@endsection

@section('content')
	@include('partials.tagObjects.show-content', ['tagObject' => $scanalator, 'tagObjectName' => 'scanalator', 'titleTagObjectName' => 'Scanalator', 'associatedType' => 'chapters', 'showRoute' => 'show_scanalator', 'storeAliasRoute' => 'store_scanalator_alias', 'globalAliasDisplayClass' => 'global_scanalator_alias', 'personalAliasDisplayClass' => 'personal_scanalator_alias', 'classAliasModelPath' => App\Models\TagObjects\Scanalator\ScanalatorAlias::class])
@endsection

@section('footer')

@endsection
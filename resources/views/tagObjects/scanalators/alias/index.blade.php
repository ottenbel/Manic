@extends('layouts.app')

@section('title')
	Scanalator Aliases - Page {{$aliases->currentPage()}}
@endsection

@section('head')

@endsection

@section('content')
	@include('partials.tagObjects.alias.index-content', ['tagObjectName' => 'scanalator', 'tagObjectNames' => 'scanalators', 'indexAliasRoute' => 'index_scanalator_alias', 'showRoute' => 'show_scanalator', 'globalAliasDisplayClass' => 'global_scanalator_alias', 'personalAliasDisplayClass' => 'personal_scanalator_alias', 'classAliasModelPath' => App\Models\TagObjects\Scanalator\ScanalatorAlias::class])
@endsection

@section('footer')

@endsection
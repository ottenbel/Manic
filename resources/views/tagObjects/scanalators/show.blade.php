@extends('layouts.app')

@section('title')
	{{$scanalator->name}}
@endsection

@section('head')
	<script src="/js/confirmdelete.js"></script>
@endsection

@section('content')
	@include('partials.tagObjects.show-content', 
	[
		'tagObject' => $scanalator, 
		'tagObjectName' => 'scanalator', 
		'titleTagObjectName' => 'Scanalator', 
		'associatedType' => 'chapters', 
		'aliasTagObjectName' => 'scanalatorAlias', 
		'showRoute' => 'show_scanalator', 
		'storeAliasRoute' => 'store_scanalator_alias',
		'deleteTagObjectRoute' => 'delete_scanalator_alias',
		'globalAliasDisplayClass' => 'global_scanalator_alias', 
		'personalAliasDisplayClass' => 'personal_scanalator_alias', 
		'primaryTagObjectDisplayClass' => 'primary_scanalators', 
		'secondaryTagObjectDisplayClass' => 'secondary_scanalators',
		'tagObjectCountClass' => 'scanalator_count',		
		'classAliasModelPath' => App\Models\TagObjects\Scanalator\ScanalatorAlias::class
	])
@endsection

@section('footer')

@endsection
@extends('layouts.app')

@section('title')
Create a New Scanalator
@endsection

@section('head')
	<script src="/js/autocomplete/scanalator.js"></script>
@endsection

@section('content')
<div class="container">
	@can('create', App\Models\TagObjects\Scanalator\Scanalator::class)
		<h1>Create a New Scanalator</h1>
		
		<form method="POST" action="{{route('store_scanalator')}}" enctype="multipart/form-data">
			{{ csrf_field() }}
			
			@include('partials.tagObjects.tag-object-input', 
			[
				'child' => 'scanalator_child',
				'namePlaceholder' => $configurations['name'], 
				'shortDescriptionPlaceholder' => $configurations['short_description'],
				'descriptionPlaceholder' => $configurations['description'], 
				'sourcePlaceholder' => $configurations['source'],
				'childPlaceholder' => $configurations['child']
			])
			
			{{ Form::submit('Create Scanalator', array('class' => 'btn btn-primary')) }}
		</form>
	@endcan
	
	@cannot('create', App\Models\TagObjects\Scanalator\Scanalator::class)
		<h1>Error</h1>
		<div class="alert alert-danger" role="alert">
			User does not have the correct permissions in order to create a new scanalator.
		</div>
	@endcan
</div>
@endsection

@section('footer')

@endsection
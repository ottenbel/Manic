@extends('layouts.app')

@section('title')
Create a New Series
@endsection

@section('head')
	<script src="/js/autocomplete/series.js"></script>
@endsection

@section('content')
<div class="container">
	@can('create', App\Models\TagObjects\Series\Series::class)
		<h1>Create a New Series</h1>
		
		<form method="POST" action="{{route('store_series')}}" enctype="multipart/form-data">
			{{ csrf_field() }}
			
			@include('partials.tagObjects.tag-object-input', 
			[
				'child' => 'series_child',
				'namePlaceholder' => 'constants.placeholders.tagObjects.series.name', 
				'shortDescriptionPlaceholder' => 'constants.placeholders.tagObjects.series.shortDescription',
				'descriptionPlaceholder' => 'constants.placeholders.tagObjects.series.description', 
				'sourcePlaceholder' => 'constants.placeholders.tagObjects.series.source',
				'childPlaceholder' => 'constants.placeholders.tagObjects.series.child'
			])
			
			{{ Form::submit('Create Series', array('class' => 'btn btn-primary')) }}
		</form>
	@endcan
	
	@cannot('create', App\Models\TagObjects\Series\Series::class)
		<h1>Error</h1>
		<div class="alert alert-danger" role="alert">
			User does not have the correct permissions in order to create a new series.
		</div>
	@endcan
</div>
@endsection

@section('footer')

@endsection
@extends('layouts.app')

@section('title')
Create a New Series
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	@can('create', App\Models\TagObjects\Series\Series::class)
		<h1>Create a New Series</h1>
		
		<form method="POST" action="{{route('store_series')}}" enctype="multipart/form-data">
			{{ csrf_field() }}
			
			@include('partials.tag-object-input', ['namePlaceholder' => 'constants.placeholders.tagObjects.series.name', 'descriptionPlaceholder' => 'constants.placeholders.tagObjects.series.description', 'sourcePlaceholder' => 'constants.placeholders.tagObjects.series.source'])
			
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
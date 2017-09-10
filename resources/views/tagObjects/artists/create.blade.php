@extends('layouts.app')

@section('title')
Create a New Artist
@endsection

@section('head')
	<script src="/js/autocomplete/artist.js"></script>
@endsection

@section('content')
<div class="container">
	@can('create', App\Models\TagObjects\Artist\Artist::class)
		<h1>Create a New Artist</h1>
		
		<form method="POST" action="{{route('store_artist')}}" enctype="multipart/form-data">
			{{ csrf_field() }}
			
			@include('partials.tagObjects.tag-object-input', 
			[
				'child' => 'artist_child',
				'namePlaceholder' => $configurations['name'], 
				'shortDescriptionPlaceholder' => $configurations['shortDescription'],
				'descriptionPlaceholder' => $configurations['description'], 
				'sourcePlaceholder' => $configurations['source'],
				'childPlaceholder' => $configurations['child']
 			])
			
			{{ Form::submit('Create Artist', array('class' => 'btn btn-primary')) }}
		</form>
	@endcan
	@cannot('create', App\Models\TagObjects\Artist\Artist::class)
		<h1>Error</h1>
		<div class="alert alert-danger" role="alert">
			User does not have the correct permissions in order to create a new artist.
		</div>
	@endcan
</div>
@endsection

@section('footer')

@endsection
@extends('layouts.app')

@section('title')
Create a New Collection
@endsection

@section('head')
<script src="/js/autocomplete/artist.js"></script>
<script src="/js/autocomplete/character.js"></script>
<script src="/js/autocomplete/collection.js"></script>
<script src="/js/autocomplete/series.js"></script>
<script src="/js/autocomplete/tag.js"></script>
@endsection

@section('content')
<div class="container">
	@can('create', App\Models\Collection::class)
		<h1>Create a New Collection</h1>
		
		<form method="POST" action="{{route('store_collection')}}" enctype="multipart/form-data">
			{{ csrf_field() }}
			
			@include('partials.collection.input', array('configurations' => $configurations, 'ratings' => $ratings, 'statuses' => $statuses, 'languages' => $languages))
			
			{{ Form::submit('Create Collection', array('class' => 'btn btn-primary')) }}
		</form>
	@endcan
	
	@cannot('create', App\Models\Collection::class)
		<h1>Error</h1>
		<div class="alert alert-danger" role="alert">
			User does not have the correct permissions in order to create a new collection.
		</div>
	@endcan
</div>
@endsection

@section('footer')

@endsection
@extends('layouts.app')

@section('title')
Create a New Chapter 
@endsection

@section('head')
<script src="/js/autocomplete/scanalator.js"></script>
@endsection

@section('content')
<div class="container">
	@can('create', App\Models\Chapter::class)
		<h1>Create a New Chapter</h1>
		<h2>On <a href="{{route('show_collection', ['collection' => $collection])}}">{{{$collection->name}}}</a></h2>
		
		<form method="POST" action="/chapter" enctype="multipart/form-data">
			{{ csrf_field() }}
			
			@include('partials.chapter-input')
			
			{{ Form::submit('Create Chapter', array('class' => 'btn btn-primary')) }}
		</form>
	@endcan
	
	@cannot('create', App\Models\Chapter::class)
		<h1>Error</h1>
		<div class="alert alert-danger" role="alert">
			User does not have the correct permissions in order to create a new chapter.
		</div>
	@endcan
</div>
@endsection

@section('footer')

@endsection
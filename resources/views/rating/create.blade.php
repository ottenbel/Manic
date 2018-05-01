@extends('layouts.app')

@section('title')
	Create a New Rating
@endsection

@section('head')
	
@endsection

@section('content')
<div class="container">
	@can('create', App\Models\Rating::class)
		<h1>Create a New Rating</h1>
		
		<form method="POST" action="{{route('store_rating')}}" enctype="multipart/form-data">
			{{ csrf_field() }}
			
			@include('partials.rating.input', array('configurations' => $configurations))
			
			{{ Form::submit('Create Rating', array('class' => 'btn btn-primary')) }}
		</form>
	@endcan
	
	@cannot('create', App\Models\Rating::class)
		<h1>Error</h1>
		<div class="alert alert-danger" role="alert">
			User does not have the correct permissions in order to create a new rating.
		</div>
	@endcan
</div>
@endsection

@section('footer')

@endsection
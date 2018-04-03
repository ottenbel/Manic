@extends('layouts.app')

@section('title')
	Create a New Language
@endsection

@section('head')
	
@endsection

@section('content')
<div class="container">
	@can('create', App\Models\Language::class)
		<h1>Create a New Language</h1>
		
		<form method="POST" action="{{route('store_language')}}" enctype="multipart/form-data">
			{{ csrf_field() }}
			
			@include('partials.language.input', array('configurations' => $configurations))
			
			{{ Form::submit('Create Language', array('class' => 'btn btn-primary')) }}
		</form>
	@endcan
	
	@cannot('create', App\Models\Language::class)
		<h1>Error</h1>
		<div class="alert alert-danger" role="alert">
			User does not have the correct permissions in order to create a new language.
		</div>
	@endcan
</div>
@endsection

@section('footer')

@endsection
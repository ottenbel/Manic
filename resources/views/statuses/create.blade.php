@extends('layouts.app')

@section('title')
	Create a New Status
@endsection

@section('head')
	
@endsection

@section('content')
<div class="container">
	@can('create', App\Models\Status::class)
		<h1>Create a New Status</h1>
		
		<form method="POST" action="{{route('store_status')}}" enctype="multipart/form-data">
			{{ csrf_field() }}
			
			@include('partials.statuses.input', array('configurations' => $configurations))
			
			{{ Form::submit('Create Status', array('class' => 'btn btn-primary')) }}
		</form>
	@endcan
	
	@cannot('create', App\Models\Status::class)
		<h1>Error</h1>
		<div class="alert alert-danger" role="alert">
			User does not have the correct permissions in order to create a new status.
		</div>
	@endcan
</div>
@endsection

@section('footer')

@endsection
@extends('layouts.app')

@section('title')
Create a New Permission
@endsection

@section('head')
	
@endsection

@section('content')
<div class="container">
	@can('create', Spatie\Permission\Models\Permission::class)
		<h1>Create a New Permission</h1>
		
		<form method="POST" action="{{route('admin_store_permission')}}" enctype="multipart/form-data">
			{{ csrf_field() }}
			
			@include('partials.rolesAndPermissions.permissions.permission-input')
			
			{{ Form::submit('Create Permission', array('class' => 'btn btn-primary')) }}
		</form>
	@endcan
	@cannot('create', Spatie\Permission\Models\Permission::class)
		<h1>Error</h1>
		<div class="alert alert-danger" role="alert">
			User does not have the correct permissions in order to create a new permission.
		</div>
	@endcan
</div>
@endsection

@section('footer')

@endsection
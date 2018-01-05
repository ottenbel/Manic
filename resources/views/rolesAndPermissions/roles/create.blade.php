@extends('layouts.app')

@section('title')
Create a New Role
@endsection

@section('head')
	
@endsection

@section('content')
<div class="container">
	@can('create', Spatie\Permission\Models\Role::class)
		<h1>Create a New Role</h1>
		
		<form method="POST" action="{{route('admin_store_role')}}" enctype="multipart/form-data">
			{{ csrf_field() }}
			
			@include('partials.rolesAndPermissions.roles.role-input')
			
			{{ Form::submit('Create Role', array('class' => 'btn btn-primary')) }}
		</form>
	@endcan
	@cannot('create', Spatie\Permission\Models\Role::class)
		<h1>Error</h1>
		<div class="alert alert-danger" role="alert">
			User does not have the correct permissions in order to create a new role.
		</div>
	@endcan
</div>
@endsection

@section('footer')

@endsection
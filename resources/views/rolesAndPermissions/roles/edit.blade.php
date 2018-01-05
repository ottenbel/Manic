@extends('layouts.app')

@section('title')
Update Role - {{$role->name}}
@endsection

@section('head')
	<script src="/js/confirmdelete.js"></script>
@endsection

@section('content')
<div class="container">
	@can('create', Spatie\Permission\Models\Role::class)
		<div class="row">
			<div class="col-xs-8"><h1>Update Role</h1></div>
			@can('delete', $role)
				<div class="col-xs-4 text-right">
					<form method="POST" action="{{route('admin_delete_role', ['role' => $role])}}">
						{{ csrf_field() }}
						{{method_field('DELETE')}}
						
						{{ Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete Role', array('type' => 'submit', 'class' => 'btn btn-danger', 'onclick' =>'ConfirmDelete(event)')) }}
					</form>
				</div>
			@endcan
		</div>
		
		<form method="POST" action="{{route('admin_update_role', ['role' => $role])}}" enctype="multipart/form-data">
			{{ csrf_field() }}
			{{method_field('PATCH')}}
			
			@include('partials.rolesAndPermissions.roles.role-input')
			
			{{ Form::submit('Update Role', array('class' => 'btn btn-primary')) }}
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
@extends('layouts.app')

@section('title')
Update User Roles and Permissions - {{$user->name}}
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	@can('Edit User Roles and Permissions')
		<div class="row">
			<div class="col-xs-8"><h1>Update User Roles and Permissions</h1></div>
		</div>
		
		<form method="POST" action="{{route('admin_update_user_roles_and_permissions', ['user' => $user])}}" enctype="multipart/form-data">
			{{ csrf_field() }}
			{{method_field('PATCH')}}
			
			<h2>Roles</h2>
			<div class="row">
				@foreach($roles as $role)
					@if(($loop->index % 2) == 0)
						<div class="row">
					@endif
					
					<div class="col-xs-6">
						{{ Form::label('role-'.$role->name, $role->name) }}
						@if(Input::old("role-$role->name") == null)
							{{ Form::checkbox("role-$role->name", null, $role->hasValue)}}
						@else
							{{ Form::checkbox("role-$role->name", null, Input::old("$role->name"))}}
						@endif
						
						@if($errors->has("role-$role->name"))
							<div class ="alert alert-danger" id="roleValueError">
								{{$errors->first("role-$role->name")}}
							</div>
						@endif
					</div>
					
					@if(($loop->index % 2) == 1)
						</div>
					@endif
				@endforeach
			</div>
			
			<h2>Permissions</h2>
			<div class="row">
				@foreach($permissions as $permission)
					@if(($loop->index % 2) == 0)
						<div class="row">
					@endif
					<div class="col-xs-6">
						{{ Form::label('permission-'.$permission->name, $permission->name) }}
						@if(Input::old("permission-$permission->name") == null)
							{{ Form::checkbox("permission-$permission->name", null, $permission->hasValue)}}
						@else
							{{ Form::checkbox("permission-$permission->name", null, Input::old("$permission->name"))}}
						@endif
						
						@if($errors->has("permission-$permission->name"))
							<div class ="alert alert-danger" id="permissionValueError">
								{{$errors->first("permission-$permission->name")}}
							</div>
						@endif
					</div>
					
					@if(($loop->index % 2) == 1)
						</div>
					@endif
				@endforeach
			</div>
			
			<div class="row">
				{{ Form::submit('Update Roles and Permissions', array('class' => 'btn btn-primary')) }}
			</div>
		</form>
	@endcan
	@cannot('Edit User Roles and Permissions')
		<h1>Error</h1>
		<div class="alert alert-danger" role="alert">
			User does not have the correct permissions in order to modify user roles and permissions.
		</div>
	@endcan
</div>
@endsection

@section('footer')

@endsection
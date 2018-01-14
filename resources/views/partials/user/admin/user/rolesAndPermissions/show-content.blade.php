<button id="rolesAndPermissions_button" class="accordion" type="button">
	Roles and Permissions
</button>

<div id="rolesAndPermissions_panel" class="volume_panel">
	<h2>Roles</h2>
	@if($roles->count() > 0)
		@include('partials.rolesAndPermissions.roles.role-index', ['roles' => $roles])
	@else
		<div>No roles are associated with user {{$user->name}}</div>
	@endif
	
	<h2>Permissions</h2>
	@if($permissions->count() > 0)
		@include('partials.rolesAndPermissions.permissions.permission-index', ['permissions' => $permissions])
	@else
		<div>No permissions are associated with user {{$user->name}}</div>
	@endif
	
	<a class="btn btn-success btn-sm" href="{{route('admin_edit_user_roles_and_permissions', $user)}}"><i class="fa fa-pencil" aria-hidden="true"></i> Edit Roles and Permissions</a>
</div>
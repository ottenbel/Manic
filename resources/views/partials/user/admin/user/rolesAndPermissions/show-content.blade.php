<button id="rolesAndPermissions_button" class="openAccordion" type="button">
	Roles and Permissions
</button>

<div id="rolesAndPermissions_panel" class="volume_panel">
	<h2>Roles</h2>
	<div class ="row">
		@if($roles->count() > 0)
			@include('partials.rolesAndPermissions.roles.role-index', ['roles' => $roles])
		@else
			<div>No roles are associated with user {{$user->name}}</div>
		@endif
	</div>
	
	<h2>Permissions</h2>
	<div class ="row">
		@if($permissions->count() > 0)
			@include('partials.rolesAndPermissions.permissions.permission-index', ['permissions' => $permissions])
		@else
			<div>No direct permissions are associated with user {{$user->name}}. Check associated roles for inherited permissions.</div>
		@endif
	</div>
	
	@if(Auth::user()->can('Edit User Roles and Permissions'))
	<div class="row">
		<a class="btn btn-success btn-sm" href="{{route('admin_edit_user_roles_and_permissions', $user)}}"><i class="fa fa-pencil" aria-hidden="true"></i> Edit Roles and Permissions</a>
		<br/>
	</div>
		
	@endcan
</div>
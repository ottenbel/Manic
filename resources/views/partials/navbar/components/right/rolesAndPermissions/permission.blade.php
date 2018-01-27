@if(Route::is('admin_index_permission'))
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
			Permissions & Roles <span class="caret"></span>
		</a>
		<ul class="dropdown-menu" role="menu">
			<h6 class="dropdown-header">Indexes</h6>
			<li><a href="{{ route('admin_index_role') }}"><i class="fa fa-object-group" aria-hidden="true"></i> Roles</a></li>
			
			@if(Auth::user()->can('create', Spatie\Permission\Models\Permission::class) || Auth::user()->can('create', Spatie\Permission\Models\Role::class))
				<h6 class="dropdown-header">Create/Update</h6>
			
				@can('create', Spatie\Permission\Models\Permission::class)
					<li><a href="{{ route('admin_create_permission') }}"><i class="fa fa-unlock" aria-hidden="true"></i> Create Permission</a></li>
				@endcan
				
				@can('create', Spatie\Permission\Models\Role::class)
					<li><a href="{{ route('admin_create_role') }}"><i class="fa fa-object-group" aria-hidden="true"></i> Create Role</a></li>
				@endcan
			@endif
		</ul>
	</li>
@elseif(Route::is('admin_create_permission'))
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
			Permissions & Roles <span class="caret"></span>
		</a>
		<ul class="dropdown-menu" role="menu">
			<h6 class="dropdown-header">Indexes</h6>
			<li><a href="{{ route('admin_index_permission') }}"><i class="fa fa-unlock" aria-hidden="true"></i> Permissions</a></li>
			<li><a href="{{ route('admin_index_role') }}"><i class="fa fa-object-group" aria-hidden="true"></i> Roles</a></li>
			
			@can('create', Spatie\Permission\Models\Role::class)
				<h6 class="dropdown-header">Create/Update</h6>
				<li><a href="{{ route('admin_create_role') }}"><i class="fa fa-object-group" aria-hidden="true"></i> Create Role</a></li>
			@endcan
		</ul>
	</li>
@elseif(Route::is('admin_edit_permission'))
		<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
			Permissions & Roles <span class="caret"></span>
		</a>
		<ul class="dropdown-menu" role="menu">
			<h6 class="dropdown-header">Indexes</h6>
			<li><a href="{{ route('admin_index_permission') }}"><i class="fa fa-unlock" aria-hidden="true"></i> Permissions</a></li>
			<li><a href="{{ route('admin_index_role') }}"><i class="fa fa-object-group" aria-hidden="true"></i> Roles</a></li>
			
			@if(Auth::user()->can('create', Spatie\Permission\Models\Permission::class) || Auth::user()->can('create', Spatie\Permission\Models\Role::class))
				<h6 class="dropdown-header">Create/Update</h6>
			
				@can('create', Spatie\Permission\Models\Permission::class)
					<li><a href="{{ route('admin_create_permission') }}"><i class="fa fa-unlock" aria-hidden="true"></i> Create Permission</a></li>
				@endcan

				@can('create', Spatie\Permission\Models\Role::class)
					<li><a href="{{ route('admin_create_role') }}"><i class="fa fa-object-group" aria-hidden="true"></i> Create Role</a></li>
				@endcan
			@endif
		</ul>
	</li>
@elseif(Route::is('user_index_permission'))
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
			Permissions & Roles <span class="caret"></span>
		</a>
		<ul class="dropdown-menu" role="menu">
			<h6 class="dropdown-header">Indexes</h6>
			<li><a href="{{ route('user_index_role') }}"><i class="fa fa-object-group" aria-hidden="true"></i> Roles</a></li>
		</ul>
	</li>
@endif
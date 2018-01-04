@if(Route::is('index_permission'))
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
			Permissions & Roles <span class="caret"></span>
		</a>
		<ul class="dropdown-menu" role="menu">
			<li><a href="{{ route('index_role') }}"><i class="fa fa-object-group" aria-hidden="true"></i> Roles</a></li>
			
			@can('create', Spatie\Permission\Models\Permission::class)
				<li><a href="{{ route('create_permission') }}"><i class="fa fa-unlock" aria-hidden="true"></i> Create Permission</a></li>
			@endcan
			
			@can('create', Spatie\Permission\Models\Role::class)
				<li><a href="{{ route('create_role') }}"><i class="fa fa-object-group" aria-hidden="true"></i> Create Role</a></li>
			@endcan
		</ul>
	</li>
@elseif(Route::is('create_permission'))
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
			Permissions & Roles <span class="caret"></span>
		</a>
		<ul class="dropdown-menu" role="menu">
			<li><a href="{{ route('index_permission') }}"><i class="fa fa-unlock" aria-hidden="true"></i> Permissions</a></li>
			<li><a href="{{ route('index_role') }}"><i class="fa fa-object-group" aria-hidden="true"></i> Roles</a></li>
			
			@can('create', Spatie\Permission\Models\Role::class)
				<li><a href="{{ route('create_role') }}"><i class="fa fa-object-group" aria-hidden="true"></i> Create Role</a></li>
			@endcan
		</ul>
	</li>
@elseif(Route::is('edit_permission'))
		<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
			Permissions & Roles <span class="caret"></span>
		</a>
		<ul class="dropdown-menu" role="menu">
			<li><a href="{{ route('index_permission') }}"><i class="fa fa-unlock" aria-hidden="true"></i> Permissions</a></li>
			<li><a href="{{ route('index_role') }}"><i class="fa fa-object-group" aria-hidden="true"></i> Roles</a></li>
			
			@can('create', Spatie\Permission\Models\Permission::class)
				<li><a href="{{ route('create_permission') }}"><i class="fa fa-unlock" aria-hidden="true"></i> Create Permission</a></li>
			@endcan

			@can('create', Spatie\Permission\Models\Role::class)
				<li><a href="{{ route('create_role') }}"><i class="fa fa-object-group" aria-hidden="true"></i> Create Role</a></li>
			@endcan
		</ul>
	</li>
@endif
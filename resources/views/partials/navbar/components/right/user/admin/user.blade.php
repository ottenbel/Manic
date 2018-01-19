@if(Route::is('admin_show_user'))
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
			Users <span class="caret"></span>
		</a>
		<ul class="dropdown-menu" role="menu">
			<h6 class="dropdown-header">Indexes</h6>
			<li><a href="{{ route('admin_index_user') }}"><i class="fa fa-user" aria-hidden="true"></i> Users</a></li>
		</ul>
	</li>
@elseif(Route::is('admin_edit_user_roles_and_permissions'))
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
			Users <span class="caret"></span>
		</a>
		<ul class="dropdown-menu" role="menu">
			<h6 class="dropdown-header">Indexes</h6>
			<li><a href="{{ route('admin_index_user') }}"><i class="fa fa-user" aria-hidden="true"></i> Users</a></li>
			<h6 class="dropdown-header">Create/Update</h6>
			<li><a href="{{ route('admin_show_user', ['user' => $user]) }}"><i class="fa fa-eye" aria-hidden="true"></i> View User</a></li>
		</ul>
	</li>
@endif
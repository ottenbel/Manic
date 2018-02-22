@if(Auth::check() && Route::is('user_*'))
	<li class="dropdown">	
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
			User <span class="caret"></span>
		</a>
		<ul class="dropdown-menu" role="menu">
			<h6 class="dropdown-header">Permissions & Roles</h6>
			<li><a href="{{ route('user_index_permission') }}"><i class="fa fa-unlock" aria-hidden="true"></i> Permissions</a><li>
			<li><a href="{{ route('user_index_role') }}"><i class="fa fa-object-group" aria-hidden="true"></i> Roles</a><li>
			
			@if((Auth::user()->can('Edit Personal Pagination Settings')) 
				|| (Auth::user()->can('Edit Personal Placeholder Settings')) 
				|| (Auth::user()->can('Edit Personal Rating Restriction Settings')))
				
				<h6 class="dropdown-header">Configuration</h6>
				
				@can('Edit Personal Pagination Settings')
					<li><a href="{{route('user_dashboard_configuration_pagination')}}">Pagination</a></li>
				@endcan
				
				@can('Edit Personal Placeholder Settings')
					<li><a href="{{route('user_dashboard_configuration_placeholders')}}">Placeholders</a></li>
				@endcan
				
				@can('Edit Personal Rating Restriction Settings')
					<li><a href="{{route('user_dashboard_configuration_rating_restriction')}}">Rating Restrictions</a></li>
				@endcan
			@endif
			<h6 class="dropdown-header">Account Settings</h6>
				<li><a href="{{ route('edit_password') }}"><i class="fa fa-unlock" aria-hidden="true"></i> Change Password</a></li>
			<h6 class="dropdown-header">Locations of Interest</h6>
				<li><a href="{{ route('index_collection_favourite') }}"><i class="fa fa-star" aria-hidden="true"></i> Favorites</a></li>
		</ul>
	</li>
@elseif(Auth::check() && Route::is('admin_*'))
	<li class="dropdown">	
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
			Admin <span class="caret"></span>
		</a>
		<ul class="dropdown-menu" role="menu">
			@can('View User Index')
				<li><a href="{{route('admin_index_user')}}"><i class="fa fa-user" aria-hidden="true"></i> Users</a></li>
			@endcan
			@if(Auth::user()->can('Create Role') || Auth::user()->can('Edit Role') || Auth::user()->can('Delete Role') || Auth::user()->can('Create Permission') || Auth::user()->can('Edit Permission') || Auth::user()->can('Delete Permission'))
				<h6 class="dropdown-header">Permissions & Roles</h6>
				@if(Auth::user()->can('Create Permission') || Auth::user()->can('Edit Permission') || Auth::user()->can('Delete Permission'))
					<li><a href="{{ route('admin_index_permission') }}"><i class="fa fa-unlock" aria-hidden="true"></i> Permissions</a><li>
				@endif
				@if(Auth::user()->can('Create Role') || Auth::user()->can('Edit Role') || Auth::user()->can('Delete Role'))
					<li><a href="{{ route('admin_index_role') }}"><i class="fa fa-object-group" aria-hidden="true"></i> Roles</a><li>
				@endif
			@endif
			@if((Auth::user()->can('Edit Global Pagination Settings')) 
				|| (Auth::user()->can('Edit Global Placeholder Settings')) 
				|| (Auth::user()->can('Edit Global Rating Restriction Settings')))
				
				<h6 class="dropdown-header">Configuration</h6>
				
				@can('Edit Global Pagination Settings')
					<li><a href="{{route('admin_dashboard_configuration_pagination')}}">Pagination</a></li>
				@endcan
				
				@can('Edit Global Placeholder Settings')
					<li><a href="{{route('admin_dashboard_configuration_placeholders')}}">Placeholders</a></li>
				@endcan
				
				@can('Edit Global Rating Restriction Settings')
					<li><a href="{{route('admin_update_configuration_rating_restriction')}}">Rating Restrictions</a></li>
				@endcan
			@endif
		</ul>
	</li>
@endif

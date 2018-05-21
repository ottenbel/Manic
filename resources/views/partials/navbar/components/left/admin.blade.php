@if(Auth::check() && 
	((Auth::user()->hasAnyPermission(Spatie\Permission\Models\Role::findByName('administrator')->permissions->pluck('name')->toArray())) 
			|| (Auth::user()->hasAnyPermission(Spatie\Permission\Models\Role::findByName('owner')->permissions->pluck('name')->toArray()))))
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
			@if(Auth::user()->can('View Error Log'))
				<h6 class="dropdown-header">Other</h6>
				@can('View Error Log')
					<li><a href="{{route('admin_log')}}">Error Log</a></li>
				@endcan
			@endif
		</ul>
	</li>
@endif
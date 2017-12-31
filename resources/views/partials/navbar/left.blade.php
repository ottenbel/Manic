@if(Auth::check() && (((Auth::user()->can('Edit Personal Pagination Settings')) 
	|| (Auth::user()->can('Edit Personal Placeholder Settings')) 
	|| (Auth::user()->can('Edit Personal Rating Restriction Settings'))) 
	|| ((Auth::user()->can('Edit Global Pagination Settings')) 
	|| (Auth::user()->can('Edit Global Placeholder Settings')) 
	|| (Auth::user()->can('Edit Global Rating Restriction Settings')))))
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
			Configuration <span class="caret"></span>
		</a>
		<ul class="dropdown-menu" role="menu">
			@if((Auth::user()->can('Edit Personal Pagination Settings')) 
				|| (Auth::user()->can('Edit Personal Placeholder Settings')) 
				|| (Auth::user()->can('Edit Personal Rating Restriction Settings')))
				
				<h6 class="dropdown-header">User</h6>
				
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
			
			@if((Auth::user()->can('Edit Global Pagination Settings')) 
				|| (Auth::user()->can('Edit Global Placeholder Settings')) 
				|| (Auth::user()->can('Edit Global Rating Restriction Settings')))
				
				<h6 class="dropdown-header">Administrator</h6>
				
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
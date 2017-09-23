@if(Auth::check())
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
			Configuration <span class="caret"></span>
		</a>
		<ul class="dropdown-menu" role="menu">
			<h6 class="dropdown-header">User</h6>
			<li><a href="{{route('user_dashboard_configuration_pagination')}}">Pagination</a></li>
				
			@if(Auth::user()->has_editor_permission())
				<li><a href="{{route('user_dashboard_configuration_placeholders')}}">Placeholders</a></li>
			@endif
			@if(Auth::user()->has_administrator_permission())
				<h6 class="dropdown-header">Administrator</h6>
				<li><a href="{{route('admin_dashboard_configuration_pagination')}}">Pagination</a></li>
				<li><a href="{{route('admin_dashboard_configuration_placeholders')}}">Placeholders</a></li>
			@endif
		</ul>
	</li>
@endif
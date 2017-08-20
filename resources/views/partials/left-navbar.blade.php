@if(Auth::check())
	<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Configuration <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				<li>
					<h6 class="dropdown-header">User</h6>
					<a href="{{route('user_dashboard_configuration_pagination')}}">Pagination</a>
					@if(Auth::user()->has_administrator_permission())
						<h6 class="dropdown-header">Administrator</h6>
					<a href="{{route('admin_dashboard_configuration_pagination')}}">Pagination</a>
					@endif
				</li>
			</ul>
	</li>
@endif
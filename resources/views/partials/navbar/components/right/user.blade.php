<li class="dropdown">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
		{{ Auth::user()->name }} <span class="caret"></span>
	</a>

	<ul class="dropdown-menu" role="menu">
		<li>
			<a href="{{route('user_dashboard_main')}}"><i class="fa fa-user-circle-o" aria-hidden="true"></i> User Dashboard</a>
		</li>
		@if((Auth::user()->hasAnyPermission(Spatie\Permission\Models\Role::findByName('administrator')->permissions->pluck('name')->toArray())) 
			|| (Auth::user()->hasAnyPermission(Spatie\Permission\Models\Role::findByName('owner')->permissions->pluck('name')->toArray())))
			<li>
				<a href="{{route('admin_dashboard_main')}}"><i class="fa fa-user-circle" aria-hidden="true"></i> Admin Dashboard</a>
			</li>	
		@endif
		<li>
			<a href="{{ url('/logout') }}"
				onclick="event.preventDefault();
						 document.getElementById('logout-form').submit();">
				<i class="fa fa-sign-out" aria-hidden="true"></i> Logout
			</a>

			<form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
				{{ csrf_field() }}
			</form>
		</li>
	</ul>
</li>
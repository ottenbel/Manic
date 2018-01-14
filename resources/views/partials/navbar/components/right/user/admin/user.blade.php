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
@endif
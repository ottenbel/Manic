@if((Route::is('show_status')) && (!empty($status)) && ((Auth::User()->can('update', $status)) ))
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
			Status <span class="caret"></span>
		</a>
		
		<ul class="dropdown-menu" role="menu">
			@if((!empty($status)))
				@can('update', $status)
					<li><a href="{{route('edit_status', ['status' => $status])}}"><i class="fa fa-pencil" aria-hidden="true"></i> Edit Status</a></li>
				@endcan
			@endif
		</ul>
	</li>
@elseif (Route::is('edit_status') && (!empty($status)))
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
			Status <span class="caret"></span>
		</a>
		<ul class="dropdown-menu" role="menu">
			<li><a href="{{route('show_status', ['status' => $status])}}"><i class="fa fa-eye" aria-hidden="true"></i> View Status</a></li>
		</ul>
	</li>
@endif
@if ((Route::is('show_series')) && (!empty($series)) && ((Auth::User()->can('update', $series)) 
	 || (Auth::User()->can('create', App\Models\TagObjects\Character\Character::class))))
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> Series <span class="caret"></span></a>
		<ul class="dropdown-menu" role="menu">
			@can('create', App\Models\TagObjects\Character\Character::class)
				<li><a href="{{route('create_character', ['series' => $series])}}"><i class="fa fa-user" aria-hidden="true"></i> Add Character</a><li>
			@endcan
			@can('update', $series)
				<li><a href="{{route('edit_series', ['series' => $series])}}"><i class="fa fa-pencil" aria-hidden="true"></i> Edit Series</a><li>
			@endcan
		</ul>
	</li>
@elseif (Route::is('edit_series') && (!empty($series)))
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> Series <span class="caret"></span></a>
		<ul class="dropdown-menu" role="menu">
			@can('create', App\Models\TagObjects\Character\Character::class)
				<li><a href="{{route('create_character', ['series' => $series])}}"><i class="fa fa-user" aria-hidden="true"></i> Add Character</a><li>
			@endcan
			<li><a href="{{route('show_series', ['series' => $series])}}"><i class="fa fa-eye" aria-hidden="true"></i> View Series</a><li>
		</ul>
	</li>
@endif
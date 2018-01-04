@if ((Route::is('show_character')) && (!empty($character)) && (Auth::User()->can('update', $character)))	
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> Character <span class="caret"></span></a>
		<ul class="dropdown-menu" role="menu">
			@can('update', $character)
				<li><a href="{{route('edit_character', ['character' => $character])}}"><i class="fa fa-pencil" aria-hidden="true"></i> Edit Character</a><li>
			@endcan
		</ul>
	</li>
@elseif (Route::is('edit_character') && (!empty($character)))
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> Character <span class="caret"></span></a>
		<ul class="dropdown-menu" role="menu">
			<li><a href="{{route('show_character', ['character' => $character])}}"><i class="fa fa-eye" aria-hidden="true"></i> View Character</a><li>
		</ul>
	</li>
@endif
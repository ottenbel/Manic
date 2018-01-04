@if ((Route::is('show_scanalator')) && (!empty($scanalator)) && ((Auth::User()->can('update', $scanalator))))
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
			Scanalator <span class="caret"></span>
		</a>
		<ul class="dropdown-menu" role="menu">
			@can('update', $scanalator)
				<li><a href="{{route('edit_scanalator', ['scanalator' => $scanalator])}}"><i class="fa fa-pencil" aria-hidden="true"></i> Edit Scanalator</a><li>
			@endcan
		</ul>
	</li>
@elseif (Route::is('edit_scanalator') && (!empty($scanalator)))
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
			Scanalator <span class="caret"></span>
		</a>
		<ul class="dropdown-menu" role="menu">
			<li><a href="{{route('show_scanalator', ['scanalator' => $scanalator])}}"><i class="fa fa-eye" aria-hidden="true"></i> View Scanalator</a><li>
		</ul>
	</li>
@endif
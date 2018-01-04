@if ((Route::is('show_artist')) && (!empty($artist)) && ((Auth::User()->can('update', $artist))))
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> Artist <span class="caret"></span></a>
		<ul class="dropdown-menu" role="menu">
			@can('update', $artist)
				<li><a href="{{route('edit_artist', ['artist' => $artist])}}"><i class="fa fa-pencil" aria-hidden="true"></i> Edit Artist</a><li>
			@endcan
		</ul>
	</li>
@elseif (Route::is('edit_artist') && (!empty($artist)))
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Artist <span class="caret"></span></a>
		<ul class="dropdown-menu" role="menu">
			<li><a href="{{route('show_artist', ['artist' => $artist])}}"><i class="fa fa-eye" aria-hidden="true"></i> View Artist</a><li>
		</ul>
	</li>
@endif
@if ((Route::is('show_tag')) && (!empty($tag)) && ((Auth::User()->can('update', $tag)) ))
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
			Tag <span class="caret"></span></a>
		<ul class="dropdown-menu" role="menu">
			@can('update', $tag)
				<li><a href="{{route('edit_tag', ['tag' => $tag])}}"><i class="fa fa-pencil" aria-hidden="true"></i> Edit Tag</a><li>
			@endcan
		</ul>
	</li>
@elseif (Route::is('edit_tag') && (!empty($tag)))
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> Tag <span class="caret"></span></a>
		<ul class="dropdown-menu" role="menu">
			<li><a href="{{route('show_tag', ['tag' => $tag])}}"><i class="fa fa-eye" aria-hidden="true"></i> View Tag</a><li>
		</ul>
	</li>
@endif
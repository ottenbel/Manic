@if((Route::is('show_chapter')) && (!empty($chapter)) &&((Auth::User()->can('update', $chapter))))
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
			Chapter <span class="caret"></span></a>
		<ul class="dropdown-menu" role="menu">
			@can('update', $chapter)
				<li><a href="{{route('edit_chapter', ['chapter' => $chapter])}}"><i class="fa fa-pencil" aria-hidden="true"></i> Edit Chapter</a></li>
			@endcan
		</ul>
	</li>
@elseif (Route::is('edit_chapter') && (!empty($chapter)))
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
			Chapter <span class="caret"></span>
		</a>
		<ul class="dropdown-menu" role="menu">
			<li><a href="{{route('show_chapter', ['chapter' => $chapter])}}"><i class="fa fa-eye" aria-hidden="true"></i> View Chapter</a></li>
		</ul>
	</li>
@endif
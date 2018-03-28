@if((Route::is('show_language')) && (!empty($language)) && ((Auth::User()->can('update', $language)) ))
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
			Language <span class="caret"></span>
		</a>
		
		<ul class="dropdown-menu" role="menu">
			@if((!empty($language)))
				@can('update', $language)
					<li><a href="{{route('edit_language', ['language' => $language])}}"><i class="fa fa-pencil" aria-hidden="true"></i> Edit Language</a></li>
				@endcan
			@endif
		</ul>
	</li>
@elseif (Route::is('edit_language') && (!empty($language)))
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
			Language <span class="caret"></span>
		</a>
		<ul class="dropdown-menu" role="menu">
			<li><a href="{{route('show_language', ['language' => $language])}}"><i class="fa fa-eye" aria-hidden="true"></i> View Language</a></li>
		</ul>
	</li>
@endif
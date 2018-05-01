@if((Route::is('show_rating')) && (!empty($rating)) && ((Auth::User()->can('update', $rating)) ))
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
			Status <span class="caret"></span>
		</a>
		
		<ul class="dropdown-menu" role="menu">
			@if((!empty($rating)))
				@can('update', $rating)
					<li><a href="{{route('edit_rating', ['rating' => $rating])}}"><i class="fa fa-pencil" aria-hidden="true"></i> Edit Rating</a></li>
				@endcan
			@endif
		</ul>
	</li>
@elseif (Route::is('edit_rating') && (!empty($rating)))
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
			Status <span class="caret"></span>
		</a>
		<ul class="dropdown-menu" role="menu">
			<li><a href="{{route('show_rating', ['rating' => $rating])}}"><i class="fa fa-eye" aria-hidden="true"></i> View Rating</a></li>
		</ul>
	</li>
@endif
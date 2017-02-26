<li class="dropdown">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
		Tags<span class="caret"></span>
	</a>
	<ul class="dropdown-menu" role="menu">
		<li><a href="{{ url('/artist') }}">Artist</a><li>
		<li><a href="{{ url('/tag') }}">Tag</a><li>
		<li><a href="{{ url('/scanalator') }}">Scanalator</a><li>
		<li><a href="{{ url('/series') }}">Series</a><li>
	</ul>
</li>

<!-- Authentication Links -->
@if (Auth::guest())
	<li><a href="{{ url('/login') }}">Login</a></li>
	<li><a href="{{ url('/register') }}">Register</a></li>
@else
	<!-- If the user has the edit role -->
	@if(Route::getCurrentRoute()->getActionName() == "App\\Http\\Controllers\\CollectionController@show")
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Collection <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				@if(count($collection->volumes))
					<li><a href="/chapter/create/{{$collection->id}}">Add Chapter</a></li>
				@endif
				<li><a href="/volume/create/{{$collection->id}}">Add Volume</a><li>
				<li><a href="/collection/{{$collection->id}}/edit">Edit Collection</a><li>
				<li><a href="">Delete Collection</a></li>
			</ul>
		</li>
	@elseif(Route::getCurrentRoute()->getActionName() == "App\\Http\\Controllers\\ChapterController@show")
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Chapter <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				<li><a href="/chapter/{{$chapter->id}}/edit">Edit Chapter</a><li>
				<li><a href="">Delete Chapter</a></li>
			</ul>
		</li>
	@elseif (Route::getCurrentRoute()->getActionName() == "App\\Http\\Controllers\\CollectionController@edit")
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Collection <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				<li><a href="/collection/{{$collection->id}}/">View Collection</a><li>
				@if(count($collection->volumes))
					<li><a href="/chapter/create/{{$collection->id}}">Add Chapter</a></li>
				@endif
				<li><a href="/volume/create/{{$collection->id}}">Add Volume</a><li>
				<li><a href="">Delete Collection</a></li>
			</ul>
		</li>
	@elseif (Route::getCurrentRoute()->getActionName() == "App\\Http\\Controllers\\VolumeController@edit")
	<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Volume <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				<li><a href="">Delete Volume</a></li>
			</ul>
		</li>
	@elseif (Route::getCurrentRoute()->getActionName() == "App\\Http\\Controllers\\ChapterController@edit")
	<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Chapter <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				<li><a href="/chapter/{{$chapter->id}}/">View Chapter</a><li>
				<li><a href="">Delete Chapter</a></li>
			</ul>
		</li>
	@elseif (Route::getCurrentRoute()->getActionName() == "App\\Http\\Controllers\\TagController@show")	
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Tag <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				<li><a href="/tag/{{$tag->id}}/edit/">Edit</a><li>
				<li><a href="">Delete Tag</a></li>
			</ul>
		</li>
	@elseif (Route::getCurrentRoute()->getActionName() == "App\\Http\\Controllers\\TagController@edit")
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Tag <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				<li><a href="/tag/{{$tagObject->id}}/">View Tag</a><li>
				<li><a href="">Delete Tag</a></li>
			</ul>
		</li>
	@elseif (Route::getCurrentRoute()->getActionName() == "App\\Http\\Controllers\\ArtistController@show")	
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Artist <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				<li><a href="/artist/{{$artist->id}}/edit/">Edit</a><li>
				<li><a href="">Delete Artist</a></li>
			</ul>
		</li>
	@elseif (Route::getCurrentRoute()->getActionName() == "App\\Http\\Controllers\\ArtistController@edit")
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Artist <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				<li><a href="/artist/{{$tagObject->id}}/">View Artist</a><li>
				<li><a href="">Delete Artist</a></li>
			</ul>
		</li>
	@elseif (Route::getCurrentRoute()->getActionName() == "App\\Http\\Controllers\\SeriesController@show")	
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Series <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				<li><a href="/series/{{$series->id}}/edit/">Edit</a><li>
				<li><a href="">Delete Series</a></li>
			</ul>
		</li>
	@elseif (Route::getCurrentRoute()->getActionName() == "App\\Http\\Controllers\\SeriesController@edit")
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Series <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				<li><a href="/series/{{$tagObject->id}}/">View Series</a><li>
				<li><a href="">Delete Series</a></li>
			</ul>
		</li>
	@elseif (Route::getCurrentRoute()->getActionName() == "App\\Http\\Controllers\\ScanalatorController@show")	
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Scanalator <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				<li><a href="/scanalator/{{$scanalator->id}}/edit/">Edit</a><li>
				<li><a href="">Delete Scanalator</a></li>
			</ul>
		</li>
	@elseif (Route::getCurrentRoute()->getActionName() == "App\\Http\\Controllers\\ScanalatorController@edit")
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Scanalator <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				<li><a href="/scanalator/{{$tagObject->id}}/">View Scanalator</a><li>
				<li><a href="">Delete Scanalator</a></li>
			</ul>
		</li>
	@else
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Create <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				<li><a href="{{url('/collection/create')}}">Collection</a></li>
				<div class="dropdown-divider"></div>
				<h6 class="dropdown-header">Tags</h6>
				<li><a href="{{ url('/artist/create') }}">Artist</a><li>
				<li><a href="{{ url('/tag/create') }}">Tag</a><li>
				<li><a href="{{ url('/scanalator/create') }}">Scanalator</a><li>
				<li><a href="{{ url('/series/create') }}">Series</a><li>	
			</ul>
		</li>
	@endif
	
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
			{{ Auth::user()->name }} <span class="caret"></span>
		</a>

		<ul class="dropdown-menu" role="menu">
			<li>
				<a href="{{url('/home')}}">User Dashboard</a>
			</li>
			<li>
				<a href="{{ url('/logout') }}"
					onclick="event.preventDefault();
							 document.getElementById('logout-form').submit();">
					Logout
				</a>

				<form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
					{{ csrf_field() }}
				</form>
			</li>
		</ul>
	</li>
@endif
<li class="dropdown">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
		Tags<span class="caret"></span>
	</a>
	<ul class="dropdown-menu" role="menu">
		<li><a href="{{ url('/artist') }}">Artist</a><li>
		<li><a href="{{ url('/character') }}">Character</a><li>
		<li><a href="{{ url('/scanalator') }}">Scanalator</a><li>
		<li><a href="{{ url('/series') }}">Series</a><li>
		<li><a href="{{ url('/tag') }}">Tag</a><li>
		<h6 class="dropdown-header">Aliases</h6>
		<li><a href="{{ url('/artist_alias') }}">Artist Aliases</a><li>
		<li><a href="{{ url('/character_alias') }}">Character Aliases</a><li>
		<li><a href="{{ url('/scanalator_alias') }}">Scanalator Aliases</a><li>
		<li><a href="{{ url('/series_alias') }}">Series Aliases</a><li>
		<li><a href="{{ url('/tag_alias') }}">Tag Aliases</a><li>
	</ul>
</li>

<!-- Authentication Links -->
@if (Auth::guest())
	<li><a href="{{ url('/login') }}">Login</a></li>
	<li><a href="{{ url('/register') }}">Register</a></li>
@else
	<!-- Add general checks on roles once all policies have been created -->
	@if((Route::is('show_collection')) && ((Auth::User()->can('create', App\Models\Chapter::class)) 
			|| (Auth::User()->can('create', App\Models\Volume::class)) || (Auth::User()->can('update', $collection)) 
			|| (Auth::User()->can('delete', $collection))))
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Collection <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				@if(count($collection->volumes))
					@can('create', App\Models\Chapter::class)
						<li><a href="{{route('create_chapter', ['collection' => $collection])}}">Add Chapter</a></li>
					@endcan
				@endif
				@can('create', App\Models\Volume::class)
					<li><a href="{{route('create_volume', ['collection' => $collection])}}">Add Volume</a><li>
				@endcan
				@can('update', $collection)
					<li><a href="{{route('edit_collection', ['collection' => $collection])}}">Edit Collection</a><li>
				@endcan
				@can('delete', $collection)
					<li><a href="">Delete Collection</a></li>
				@endcan
			</ul>
		</li>
	@elseif((Route::is('show_chapter')) &&((Auth::User()->can('update', $collection)) 
			|| (Auth::User()->can('delete', $collection))))
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Chapter <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				@can('update', $collection)
					<li><a href="{{route('edit_chapter', ['chapter' => $chapter])}}">Edit Chapter</a><li>
				@endcan
				@can('delete', $collection)
					<li><a href="">Delete Chapter</a></li>
				@endcan
			</ul>
		</li>
	@elseif (Route::is('edit_collection'))
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Collection <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				<li><a href="{{route('show_collection', ['collection' => $collection])}}">View Collection</a><li>
				@if(count($collection->volumes))
					@can('create', App\Models\Chapter::class)
						<li><a href="{{route('create_chapter', ['collection' => $collection])}}">Add Chapter</a></li>
					@endcan
				@endif
				@can('create', App\Models\Volume::class)
					<li><a href="{{route('create_volume', ['collection' => $collection])}}">Add Volume</a><li>
				@endcan
				@can('delete', $collection)
					<li><a href="">Delete Collection</a></li>
				@endcan
			</ul>
		</li>
	@elseif ((Route::is('edit_volume'))
		&& (Auth::User()->can('delete', $volume)))
		<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
					Volume <span class="caret"></span>
				</a>
				@can('delete', $volume)
				<ul class="dropdown-menu" role="menu">
					<li><a href="">Delete Volume</a></li>
				</ul>
				@endcan
			</li>
	@elseif (Route::is('edit_chapter'))
	<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Chapter <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				<li><a href="{{route('show_chapter', ['chapter' => $chapter])}}">View Chapter</a><li>
				@can('delete', $chapter)
					<li><a href="">Delete Chapter</a></li>
				@endcan
			</ul>
		</li>
	@elseif ((Route::getCurrentRoute()->getActionName() == "App\\Http\\Controllers\\TagObjects\\Tag\\TagController@show")
			&& ((Auth::User()->can('update', $tag)) || (Auth::User()->can('delete', $tag))))
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Tag <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				@can('update', $tag)
					<li><a href="/tag/{{$tag->id}}/edit/">Edit Tag</a><li>
				@endcan
				@can('delete', $tag)
					<li><a href="">Delete Tag</a></li>
				@endcan
			</ul>
		</li>
	@elseif (Route::getCurrentRoute()->getActionName() == "App\\Http\\Controllers\\TagObjects\\Tag\\TagController@edit")
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Tag <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				<li><a href="/tag/{{$tagObject->id}}/">View Tag</a><li>
				@can('delete', $tagObject)
					<li><a href="">Delete Tag</a></li>
				@endcan
			</ul>
		</li>
	@elseif ((Route::getCurrentRoute()->getActionName() == "App\\Http\\Controllers\\TagObjects\\Artist\\ArtistController@show")
		&& ((Auth::User()->can('update', $artist)) || (Auth::User()->can('delete', $artist))))
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Artist <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				@can('update', $artist)
					<li><a href="/artist/{{$artist->id}}/edit/">Edit Artist</a><li>
				@endcan
				@can('delete', $artist)
					<li><a href="">Delete Artist</a></li>
				@endcan
			</ul>
		</li>
	@elseif (Route::getCurrentRoute()->getActionName() == "App\\Http\\Controllers\\TagObjects\\Artist\\ArtistController@edit")
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Artist <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				<li><a href="/artist/{{$tagObject->id}}/">View Artist</a><li>
				@can('delete', $tagObject)
					<li><a href="">Delete Artist</a></li>
				@endcan
			</ul>
		</li>
	@elseif ((Route::getCurrentRoute()->getActionName() == "App\\Http\\Controllers\\TagObjects\\Character\\CharacterController@show")
		&& ((Auth::User()->can('update', $character)) || (Auth::User()->can('delete', $character))))	
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Character <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				@can('update', $character)
					<li><a href="/character/{{$character->id}}/edit/">Edit Character</a><li>
				@endcan
				@can('delete', $character)
					<li><a href="">Delete Character</a></li>
				@endcan
			</ul>
		</li>
	@elseif (Route::getCurrentRoute()->getActionName() == "App\\Http\\Controllers\\TagObjects\\Character\\CharacterController@edit")
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Character <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				<li><a href="/character/{{$tagObject->id}}/">View Character</a><li>
				@can('delete', $tagObject)
					<li><a href="">Delete Character</a></li>
				@endcan
			</ul>
		</li>
	@elseif ((Route::getCurrentRoute()->getActionName() == "App\\Http\\Controllers\\TagObjects\\Series\\SeriesController@show")
	&& ((Auth::User()->can('update', $series)) || (Auth::User()->can('delete', $series)) 
		|| (Auth::User()->can('create', App\Models\TagObjects\Character\Character::class))))
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Series <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				@can('create', App\Models\TagObjects\Character\Character::class)
					<li><a href="/character/create/{{$series->id}}">Add Character</a><li>
				@endcan
				@can('update', $series)
					<li><a href="/series/{{$series->id}}/edit/">Edit Series</a><li>
				@endcan
				@can('delete', $series)
					<li><a href="">Delete Series</a></li>
				@endcan
			</ul>
		</li>
	@elseif (Route::getCurrentRoute()->getActionName() == "App\\Http\\Controllers\\TagObjects\\Series\\SeriesController@edit")
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Series <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				@can('create', App\Models\TagObjects\Character\Character::class)
					<li><a href="/character/create/{{$tagObject->id}}">Add Character</a><li>
				@endcan
				<li><a href="/series/{{$tagObject->id}}/">View Series</a><li>
				@can('delete', $tagObject)
					<li><a href="">Delete Series</a></li>
				@endcan
			</ul>
		</li>
	@elseif ((Route::getCurrentRoute()->getActionName() == "App\\Http\\Controllers\\TagObjects\\Scanalator\\ScanalatorController@show")
		&& ((Auth::User()->can('update', $scanalator)) || (Auth::User()->can('delete', $scanalator))))
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Scanalator <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				@can('update', $scanalator)
					<li><a href="/scanalator/{{$scanalator->id}}/edit/">Edit Scanalator</a><li>
				@endcan
				@can('delete', $scanalator)
					<li><a href="">Delete Scanalator</a></li>
				@endcan
			</ul>
		</li>
	@elseif (Route::getCurrentRoute()->getActionName() == "App\\Http\\Controllers\\TagObjects\\Scanalator\\ScanalatorController@edit")
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Scanalator <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				<li><a href="/scanalator/{{$tagObject->id}}/">View Scanalator</a><li>
				@can('delete', $tagObject)
					<li><a href="">Delete Scanalator</a></li>
				@endcan
			</ul>
		</li>
	@elseif ((Auth::user()->can('create', App\Models\Collection::class)) 
			|| (Auth::user()->can('create', App\Models\TagObjects\Tag\Tag::class)) 
			|| (Auth::user()->can('create', App\Models\TagObjects\Artist\Artist::class)) 
			|| (Auth::user()->can('create', App\Models\TagObjects\Character\Character::class)) 
			|| (Auth::user()->can('create', App\Models\TagObjects\Scanalator\Scanalator::class)) 
			|| (Auth::user()->can('create', App\Models\TagObjects\Series\Series::class)))
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Create <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				@can('create', App\Models\Collection::class)
					<li><a href="{{route('create_collection')}}">Collection</a></li>
				@endcan
				@if((Auth::user()->can('create', App\Models\TagObjects\Tag\Tag::class)) 
					|| (Auth::user()->can('create', App\Models\TagObjects\Artist\Artist::class)) 
					|| (Auth::user()->can('create', App\Models\TagObjects\Character\Character::class)) 
					|| (Auth::user()->can('create', App\Models\TagObjects\Scanalator\Scanalator::class)) 
					|| (Auth::user()->can('create', App\Models\TagObjects\Series\Series::class)))
					
					<div class="dropdown-divider"></div>
					<h6 class="dropdown-header">Tags</h6>
			
					@can('create', App\Models\TagObjects\Artist\Artist::class)
						<li><a href="{{ url('/artist/create') }}">Artist</a><li>
					@endcan
					@can('create', App\Models\TagObjects\Character\Character::class)
						<li><a href="{{ url('/character/create') }}">Character</a><li>
					@endcan
					@can('create', App\Models\TagObjects\Scanalator\Scanalator::class)
						<li><a href="{{ url('/scanalator/create') }}">Scanalator</a><li>
					@endcan
					@can('create', App\Models\TagObjects\Series\Series::class)
						<li><a href="{{ url('/series/create') }}">Series</a><li>
					@endcan
					@can('create', App\Models\TagObjects\Tag\Tag::class)
						<li><a href="{{ url('/tag/create') }}">Tag</a><li>
					@endcan
				@endif
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
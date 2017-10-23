<!-- search -->
<li>
<form method="POST" action="{{route('process_search')}}" enctype="multipart/form-data">
	{{ Form::text('query_string', "", array('id' => 'search', 'class' => 'form-control', 'placeholder' => 'Search...', 'style' => 'margin-top: 8px')) }}
</form>
</li>

<li class="dropdown">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
		Tags<span class="caret"></span>
	</a>
	<ul class="dropdown-menu" role="menu">
		<li><a href="{{ route('index_artist') }}"><i class="fa fa-paint-brush" aria-hidden="true"></i>
 Artist</a><li>
		<li><a href="{{ route('index_character') }}"><i class="fa fa-users" aria-hidden="true"></i>
 Character</a><li>
		<li><a href="{{ route('index_scanalator') }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
 Scanalator</a><li>
		<li><a href="{{ route('index_series') }}"><i class="fa fa-book" aria-hidden="true"></i>
 Series</a><li>
		<li><a href="{{ route('index_tag') }}"><i class="fa fa-tags" aria-hidden="true"></i>
 Tag</a><li>
		<h6 class="dropdown-header">Aliases</h6>
		<li><a href="{{ route('index_artist_alias') }}"><i class="fa fa-paint-brush" aria-hidden="true"></i>
 Artist Aliases</a><li>
		<li><a href="{{ route('index_character_alias') }}"><i class="fa fa-users" aria-hidden="true"></i>
 Character Aliases</a><li>
		<li><a href="{{ route('index_scanalator_alias') }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
 Scanalator Aliases</a><li>
		<li><a href="{{ route('index_series_alias') }}"><i class="fa fa-book" aria-hidden="true"></i>
 Series Aliases</a><li>
		<li><a href="{{ route('index_tag_alias') }}"><i class="fa fa-tags" aria-hidden="true"></i>
 Tag Aliases</a><li>
	</ul>
</li>

<!-- Authentication Links -->
@if (Auth::guest())
	<li><a href="{{ route('login') }}"><i class="fa fa-sign-in" aria-hidden="true"></i> Login</a></li>
	<li><a href="{{ route('register') }}"><i class="fa fa-user-plus" aria-hidden="true"></i> Register</a></li>
@else
	<!-- Add general checks on roles once all policies have been created -->
	@if((Route::is('show_collection')) && (!empty($collection)) && ((Auth::User()->can('create', App\Models\Chapter::class)) 
			|| (Auth::User()->can('create', App\Models\Volume::class)) || (Auth::User()->can('update', $collection)) ))
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Collection <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				@if(count($collection->volumes))
					@can('create', App\Models\Chapter::class)
						<li><a href="{{route('create_chapter', ['collection' => $collection])}}"><i class="fa fa-file" aria-hidden="true"></i> Add Chapter</a></li>
					@endcan
				@endif
				@can('create', App\Models\Volume::class)
					<li><a href="{{route('create_volume', ['collection' => $collection])}}"><i class="fa fa-folder" aria-hidden="true"></i> Add Volume</a><li>
				@endcan
				@if((!empty($collection)))
					@can('update', $collection)
						<li><a href="{{route('edit_collection', ['collection' => $collection])}}"><i class="fa fa-pencil" aria-hidden="true"></i>
 Edit Collection</a><li>
					@endcan
				@endif
			</ul>
		</li>
	@elseif((Route::is('show_chapter')) && (!empty($chapter)) &&((Auth::User()->can('update', $chapter))))
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Chapter <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				@can('update', $chapter)
					<li><a href="{{route('edit_chapter', ['chapter' => $chapter])}}"><i class="fa fa-pencil" aria-hidden="true"></i>
 Edit Chapter</a><li>
				@endcan
			</ul>
		</li>
	@elseif (Route::is('edit_collection') && (!empty($collection)))
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Collection <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				<li><a href="{{route('show_collection', ['collection' => $collection])}}"><i class="fa fa-eye" aria-hidden="true"></i> View Collection</a><li>
				@if(count($collection->volumes))
					@can('create', App\Models\Chapter::class)
						<li><a href="{{route('create_chapter', ['collection' => $collection])}}"><i class="fa fa-file" aria-hidden="true"></i>
 Add Chapter</a></li>
					@endcan
				@endif
				@can('create', App\Models\Volume::class)
					<li><a href="{{route('create_volume', ['collection' => $collection])}}"><i class="fa fa-folder" aria-hidden="true"></i>
 Add Volume</a><li>
				@endcan
			</ul>
		</li>
	@elseif (Route::is('edit_chapter') && (!empty($chapter)))
	<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Chapter <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				<li><a href="{{route('show_chapter', ['chapter' => $chapter])}}"><i class="fa fa-eye" aria-hidden="true"></i> View Chapter</a><li>
			</ul>
		</li>
	@elseif ((Route::is('show_tag')) && (!empty($tag)) && ((Auth::User()->can('update', $tag)) ))
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Tag <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				@can('update', $tag)
					<li><a href="{{route('edit_tag', ['tag' => $tag])}}"><i class="fa fa-pencil" aria-hidden="true"></i>
 Edit Tag</a><li>
				@endcan
			</ul>
		</li>
	@elseif (Route::is('edit_tag') && (!empty($tag)))
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Tag <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				<li><a href="{{route('show_tag', ['tag' => $tag])}}"><i class="fa fa-eye" aria-hidden="true"></i> View Tag</a><li>
			</ul>
		</li>
	@elseif ((Route::is('show_artist')) && (!empty($artist)) && ((Auth::User()->can('update', $artist))))
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Artist <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				@can('update', $artist)
					<li><a href="{{route('edit_artist', ['artist' => $artist])}}"><i class="fa fa-pencil" aria-hidden="true"></i>
 Edit Artist</a><li>
				@endcan
			</ul>
		</li>
	@elseif (Route::is('edit_artist') && (!empty($artist)))
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Artist <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				<li><a href="{{route('show_artist', ['artist' => $artist])}}"><i class="fa fa-eye" aria-hidden="true"></i> View Artist</a><li>
			</ul>
		</li>
	@elseif ((Route::is('show_character')) && (!empty($character)) && (Auth::User()->can('update', $character)))	
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Character <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				@can('update', $character)
					<li><a href="{{route('edit_character', ['character' => $character])}}"><i class="fa fa-pencil" aria-hidden="true"></i>
 Edit Character</a><li>
				@endcan
			</ul>
		</li>
	@elseif (Route::is('edit_character') && (!empty($character)))
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Character <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				<li><a href="{{route('show_character', ['character' => $character])}}"><i class="fa fa-eye" aria-hidden="true"></i> View Character</a><li>
			</ul>
		</li>
	@elseif ((Route::is('show_series')) && (!empty($series)) && ((Auth::User()->can('update', $series)) 
		 || (Auth::User()->can('create', App\Models\TagObjects\Character\Character::class))))
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Series <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				@can('create', App\Models\TagObjects\Character\Character::class)
					<li><a href="{{route('create_character', ['series' => $series])}}"><i class="fa fa-user" aria-hidden="true"></i> Add Character</a><li>
				@endcan
				@can('update', $series)
					<li><a href="{{route('edit_series', ['series' => $series])}}"><i class="fa fa-pencil" aria-hidden="true"></i>
 Edit Series</a><li>
				@endcan
			</ul>
		</li>
	@elseif (Route::is('edit_series') && (!empty($series)))
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Series <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				@can('create', App\Models\TagObjects\Character\Character::class)
					<li><a href="{{route('create_character', ['series' => $series])}}"><i class="fa fa-user" aria-hidden="true"></i> Add Character</a><li>
				@endcan
				<li><a href="{{route('show_series', ['series' => $series])}}"><i class="fa fa-eye" aria-hidden="true"></i> View Series</a><li>
			</ul>
		</li>
	@elseif ((Route::is('show_scanalator')) && (!empty($scanalator)) && ((Auth::User()->can('update', $scanalator))))
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Scanalator <span class="caret"></span>
			</a>
			<ul class="dropdown-menu" role="menu">
				@can('update', $scanalator)
					<li><a href="{{route('edit_scanalator', ['scanalator' => $scanalator])}}"><i class="fa fa-pencil" aria-hidden="true"></i>
 Edit Scanalator</a><li>
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
	@if ((Auth::user()->can('create', App\Models\Collection::class)) 
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
					<li><a href="{{route('create_collection')}}"><i class="fa fa-archive" aria-hidden="true"></i>
 Collection</a></li>
				@endcan
				@if((Auth::user()->can('create', App\Models\TagObjects\Tag\Tag::class)) 
					|| (Auth::user()->can('create', App\Models\TagObjects\Artist\Artist::class)) 
					|| (Auth::user()->can('create', App\Models\TagObjects\Character\Character::class)) 
					|| (Auth::user()->can('create', App\Models\TagObjects\Scanalator\Scanalator::class)) 
					|| (Auth::user()->can('create', App\Models\TagObjects\Series\Series::class)))
					
					<div class="dropdown-divider"></div>
					<h6 class="dropdown-header">Tags</h6>
			
					@can('create', App\Models\TagObjects\Artist\Artist::class)
						<li><a href="{{ route('create_artist') }}"><i class="fa fa-paint-brush" aria-hidden="true"></i> Artist</a><li>
					@endcan
					@can('create', App\Models\TagObjects\Character\Character::class)
						<li><a href="{{ route('create_character') }}"><i class="fa fa-users" aria-hidden="true"></i> Character</a><li>
					@endcan
					@can('create', App\Models\TagObjects\Scanalator\Scanalator::class)
						<li><a href="{{ route('create_scanalator') }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Scanalator</a><li>
					@endcan
					@can('create', App\Models\TagObjects\Series\Series::class)
						<li><a href="{{ route('create_series') }}"><i class="fa fa-book" aria-hidden="true"></i>
 Series</a><li>
					@endcan
					@can('create', App\Models\TagObjects\Tag\Tag::class)
						<li><a href="{{route('create_tag')}}"><i class="fa fa-tags" aria-hidden="true"></i> Tag</a><li>
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
				<a href="{{route('user_dashboard_main')}}"><i class="fa fa-user-circle-o" aria-hidden="true"></i> User Dashboard</a>
			</li>
			@if(Auth::user()->has_administrator_permission())
				<li>
					<a href="{{route('admin_dashboard_main')}}"><i class="fa fa-user-circle" aria-hidden="true"></i> Admin Dashboard</a>
				</li>	
			@endif
			<li>
				<a href="{{ url('/logout') }}"
					onclick="event.preventDefault();
							 document.getElementById('logout-form').submit();">
					<i class="fa fa-sign-out" aria-hidden="true"></i> Logout
				</a>

				<form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
					{{ csrf_field() }}
				</form>
			</li>
		</ul>
	</li>
@endif
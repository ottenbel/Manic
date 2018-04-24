<li class="dropdown">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
		Tags<span class="caret"></span>
	</a>
	<ul class="dropdown-menu" role="menu">
		<li><a href="{{ route('index_artist') }}"><i class="fa fa-paint-brush" aria-hidden="true"></i>
 Artist</a></li>
		<li><a href="{{ route('index_character') }}"><i class="fa fa-users" aria-hidden="true"></i>
 Character</a></li>
		<li><a href="{{ route('index_scanalator') }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
 Scanalator</a></li>
		<li><a href="{{ route('index_series') }}"><i class="fa fa-book" aria-hidden="true"></i>
 Series</a></li>
		<li><a href="{{ route('index_tag') }}"><i class="fa fa-tags" aria-hidden="true"></i>
 Tag</a></li>
		<li><a href="{{route('index_language')}}">Language</a></li>
		<li><a href="{{route('index_status')}}">Status</a></li>
		<h6 class="dropdown-header">Aliases</h6>
		<li><a href="{{ route('index_artist_alias') }}"><i class="fa fa-paint-brush" aria-hidden="true"></i>
 Artist Aliases</a></li>
		<li><a href="{{ route('index_character_alias') }}"><i class="fa fa-users" aria-hidden="true"></i>
 Character Aliases</a></li>
		<li><a href="{{ route('index_scanalator_alias') }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
 Scanalator Aliases</a></li>
		<li><a href="{{ route('index_series_alias') }}"><i class="fa fa-book" aria-hidden="true"></i>
 Series Aliases</a></li>
		<li><a href="{{ route('index_tag_alias') }}"><i class="fa fa-tags" aria-hidden="true"></i>
 Tag Aliases</a></li>
	</ul>
</li>
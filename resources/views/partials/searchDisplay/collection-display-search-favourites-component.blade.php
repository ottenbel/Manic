@if($componentNot)
	<span class="{{$notFavouriteSpanClass}}">
		<a href="{{route('index_collection', ['search' => '-'.$componentToken])}}">Favourites</a>
	</span>
@else
	<span class="{{$favouriteSpanClass}}">
		<a href="{{route('index_collection', ['search' => $componentToken])}}">Favourites</a>
	</span>
@endif
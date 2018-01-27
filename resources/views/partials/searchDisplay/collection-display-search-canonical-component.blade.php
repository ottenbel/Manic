@if($componentNot)
	<span class="{{$notComponentSpanClass}}">
		<a href="{{route('index_collection', ['search' => '-'.$componentToken])}}">Canonical</a>
	</span>
@else
	<span class="{{$componentSpanClass}}">
		<a href="{{route('index_collection', ['search' => $componentToken])}}">Canonical</a>
	</span>
@endif
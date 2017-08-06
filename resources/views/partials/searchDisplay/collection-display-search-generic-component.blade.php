@if($componentNot)
	<span class="{{$notComponentSpanClass}}">
		<a href="{{route('index_collection', ['search' => '-$componentToken:' . $componentTagObject->name])}}">{{{$componentTagObject->name}}}</a>
	</span>
@else
	<span class="{{$componentSpanClass}}">
		<a href="{{route('index_collection', ['search' => '$token:' . $componentTagObject->name])}}">{{{$componentTagObject->name}}}</a>
	</span>
@endif
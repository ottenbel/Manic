@if($componentNot)
	<span class="{{$notComponentSpanClass}}">
@else
	<span class="{{$componentSpanClass}}">
@endif

@if($componentToken == 'language')
	<a href="{{route($componentRouteName, [$componentObjectName => $componentTagObject])}}">{{$componentTagObject->name}}</a>
@elseif($componentToken == 'status')
	<a href="{{route($componentRouteName, [$componentObjectName => $componentTagObject])}}">{{$componentTagObject->name}}</a>
@elseif($componentToken == 'rating')
	<a href="{{route($componentRouteName, [$componentObjectName => $componentTagObject])}}">{{$componentTagObject->name}}</a>
@else
	@if($componentNot)
		<a href="{{route('index_collection', ['search' => '-'.$componentToken.':' . $componentTagObject->name])}}">{{{$componentTagObject->name}}}</a>
	@else
		<a href="{{route('index_collection', ['search' => $componentToken.':' . $componentTagObject->name])}}">{{{$componentTagObject->name}}}</a>
	@endif	
@endif
</span>
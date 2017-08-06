@if(($tagObject->short_description != null) && ($tagObject->short_description != ""))
	<span class="{{$tagObjectClass}}" title="{{$tagObject->short_description}}">
@else
	<span class="{{$tagObjectClass}}">
@endif
	<a href="{{route('index_collection', ['search' => '$componentToken:'. $tagObject->name])}}">
		{{{$tagObject->name}}} 
		<span class="tagObjectCountClass">
			({{$tagObject->usage_count()}})
		</span>
	</a>
</span>
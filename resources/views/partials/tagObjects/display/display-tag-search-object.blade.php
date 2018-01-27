@if(($tagObject->short_description != null) && ($tagObject->short_description != ""))
	<span class="{{$tagObjectClass}}" title="{{$tagObject->short_description}}">
@else
	<span class="{{$tagObjectClass}}">
@endif
	<a href="{{route('index_collection', ['search' => $componentToken.':'. $tagObject->name])}}">
		@if($componentToken == 'artist')
		<i class="fa fa-paint-brush" aria-hidden="true"></i>
		@elseif($componentToken == 'character')
		<i class="fa fa-user" aria-hidden="true"></i>
		@elseif($componentToken == 'series')
		<i class="fa fa-book" aria-hidden="true"></i>
		@elseif($componentToken == 'scanalator')
		<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
		@elseif($componentToken == 'tag')
		<i class="fa fa-tag" aria-hidden="true"></i>
		@endif
		 {{{$tagObject->name}}} 
		<span class="tagObjectCountClass">
			({{$tagObject->usage_count()}})
		</span>
	</a>
</span>
@if(($tagObjectComponent->short_description != null) && ($tagObjectComponent->short_description != ""))
	<span class="{{$tagObjectDisplayClassComponent}}" title="{{$tagObjectComponent->short_description}}">
@else
	<span class="{{$tagObjectDisplayClassComponent}}">
@endif
	<a href="{{route($tagObjectShowRouteComponent, [$tagObjectNameComponent => $tagObjectComponent])}}">
		@if($tagObjectName == 'artist')
		<i class="fa fa-paint-brush" aria-hidden="true"></i>
		@elseif($tagObjectName == 'character')
		<i class="fa fa-user" aria-hidden="true"></i>
		@elseif($tagObjectName == 'series')
		<i class="fa fa-book" aria-hidden="true"></i>
		@elseif($tagObjectName == 'scanalator')
		<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
		@elseif($tagObjectName == 'tag')
		<i class="fa fa-tag" aria-hidden="true"></i>
		@endif
		 {{{$tagObjectComponent->name}}} 
		<span class="{{$tagObjectCountClassComponent}}">
			({{$tagObjectComponent->cached_usage_count()}})
		</span>
	</a>
</span>
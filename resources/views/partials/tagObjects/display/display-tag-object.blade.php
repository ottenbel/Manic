@if(($tagObjectComponent->short_description != null) && ($tagObjectComponent->short_description != ""))
	<span class="{{$tagObjectDisplayClassComponent}}" title="{{$tagObjectComponent->short_description}}">
@else
	<span class="{{$tagObjectDisplayClassComponent}}">
@endif
	<a href="{{route($tagObjectShowRouteComponent, [$tagObjectNameComponent => $tagObjectComponent])}}">
		{{{$tagObjectComponent->name}}} 
		<span class="{{$tagObjectCountClassComponent}}">
			({{$tagObjectComponent->usage_count()}})
		</span>
	</a>
</span>
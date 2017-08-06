@if ($primary)
	@if(($componentTagObject->short_description != null) && ($componentTagObject->short_description != ""))
		<span class="{{$primaryComponentSpanClass}}" title="{{$componentTagObject->short_description}}">
	@else
		<span class="{{$primaryComponentSpanClass}}">
	@endif
		<a href="{{route($componentRouteName, [$componentObjectName => $componentTagObject])}}">{{{$componentTagObject->name}}}</a>
	</span>
@elseif ($secondary)
	@if(($componentTagObject->short_description != null) && ($componentTagObject->short_description != ""))
		<span class="{{$secondaryComponentSpanClass}}" title="{{$componentTagObject->short_description}}">
	@else
		<span class="{{$secondaryComponentSpanClass}}">
	@endif
		<a href="{{route($componentRouteName, [$componentObjectName => $componentTagObject])}}">{{{$componentTagObject->name}}}</a>
	</span>
@else
	@if(($componentTagObject->short_description != null) && ($componentTagObject->short_description != ""))
		<span class="{{$elseComponentSpanClass}}" title="{{$componentTagObject->short_description}}">
	@else
		<span class="{{$elseComponentSpanClass}}">
	@endif
		<a href="{{route($componentRouteName, [$componentObjectName => $componentTagObject])}}">{{{$componentTagObject->name}}}</a>
	</span>
@endif
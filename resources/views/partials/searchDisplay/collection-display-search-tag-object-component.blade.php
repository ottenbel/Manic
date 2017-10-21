@if ($primary)
	@if(($componentTagObject->short_description != null) && ($componentTagObject->short_description != ""))
		<span class="{{$primaryComponentSpanClass}}" title="{{$componentTagObject->short_description}}">
	@else
		<span class="{{$primaryComponentSpanClass}}">
	@endif
@elseif ($secondary)
	@if(($componentTagObject->short_description != null) && ($componentTagObject->short_description != ""))
		<span class="{{$secondaryComponentSpanClass}}" title="{{$componentTagObject->short_description}}">
	@else
		<span class="{{$secondaryComponentSpanClass}}">
	@endif
@else
	@if(($componentTagObject->short_description != null) && ($componentTagObject->short_description != ""))
		<span class="{{$elseComponentSpanClass}}" title="{{$componentTagObject->short_description}}">
	@else
		<span class="{{$elseComponentSpanClass}}">
	@endif
@endif
<a href="{{route($componentRouteName, [$componentObjectName => $componentTagObject])}}">
@if($componentObjectName == 'artist')
	<i class="fa fa-paint-brush" aria-hidden="true"></i>
@elseif($componentObjectName == 'character')
	<i class="fa fa-user" aria-hidden="true"></i>
@elseif($componentObjectName == 'series')
	<i class="fa fa-book" aria-hidden="true"></i>
@elseif($componentObjectName == 'scanalator')
	<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
@elseif($componentObjectName == 'tag')
	<i class="fa fa-tag" aria-hidden="true"></i>
@endif
 {{{$componentTagObject->name}}}</a>
</span>

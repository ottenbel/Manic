<div class="container">	
	<div>
		<div>
			<b>Sort By:</b>
			@if($list_type == "usage")
				<b><a href="{{route($indexRoute)}}?type=usage&order={{$list_order}}">{{$titleTagObjectName}} Usage</a></b> <a href="{{route($indexRoute)}}?type=alphabetic&order={{$list_order}}">Alphabetic</a>
			@elseif ($list_type == "alphabetic")
				<a href="{{route($indexRoute)}}?type=usage&order={{$list_order}}">{{$titleTagObjectName}} Usage</a> <b><a href="{{route($indexRoute)}}?type=alphabetic&order={{$list_order}}">Alphabetic</a></b>
			@endif
		</div>
		
		<div>
			<b>Display Order:</b>
			@if($list_order == "asc")
				<b><a href="{{route($indexRoute)}}?type={{$list_type}}&order=asc">Ascending</a></b> <a href="{{route($indexRoute)}}?type={{$list_type}}&order=desc">Descending</a>
			@elseif($list_order == "desc")
				<a href="{{route($indexRoute)}}?type={{$list_type}}&order=asc">Ascending</a> <b><a href="{{route($indexRoute)}}?type={{$list_type}}&order=desc">Descending</a></b>
			@endif
		</div>
	</div>

	@if($tagObjects->count() != 0)
		<br/>
		@foreach($tagObjects as $tagObject)
			@if((($loop->iteration - 1) % 3) == 0)
				<div class="row">
			@endif
			
			<div class="col-xs-4">
				@include('partials.tagObjects.display.display-tag-object',
					['tagObjectDisplayClassComponent' => $tagDisplayClass,
						'tagObjectShowRouteComponent' => $showRoute,
						'tagObjectNameComponent' => $tagObjectName,
						'tagObjectComponent' => $tagObject,
						'tagObjectCountClassComponent' => $tagDisplayCountClass])
			</div>
			
			@if((($loop->iteration - 1) % 3) == 2)
				</div>
				<br/>
			@endif
		@endforeach
		<br/>
		<br/>
		{{ $tagObjects->links() }}
	@else
		
		<br/>
		<div class="text-center">
			@if($list_type == "usage")
				<p>No {{$tagObjectNames}} associated with a {{$associatedType}} have been found in the database. </p>
				
				<p>Try listing {{$tagObjectNames}} by alphabetical order to find any not associated with a {{$associatedType}}.</p>
				
				@can('create', $classModelPath)
					<p>Add a new {{$tagObjectName}} <a href = "{{route($createRoute)}}">here</a>.</p>
				@endcan
			@elseif ($list_type == "alphabetic")
				<p>No {{$tagObjectNames}} have been found in the database. </p>
				@can('create', $classModelPath)
					<p>Add a new {{$tagObjectName}} <a href = "{{route($createRoute)}}">here</a>.</p>
				@endcan
			@endif
		</div>
	@endif
</div>
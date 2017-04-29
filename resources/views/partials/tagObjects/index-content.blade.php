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
				<span class="{{$tagDisplayClass}}"><a href="{{route($showRoute, [$tagObjectName => $tagObject])}}">{{{$tagObject->name}}} <span class="{{$tagDisplayCountClass}}">({{$tagObject->usage_count()}})</span></a></span>
			</div>
			
			@if((($loop->iteration - 1) % 3) == 2)
				</div>
			@endif
		@endforeach
		<br/>
		<br/>
		{{ $tagObjects->links() }}
	@else
		@can('create', $classModelPath)
			<br/>
			<div class="text-center">
				@if($list_type == "usage")
					<p>No {{$tagObjectName}}s associated with a {{$associatedType}} have been found in the database. </p>
					
					<p>Add a new {{$tagObjectName}} <a href = "{{route($createRoute)}}">here</a> or try listing {{$tagObjectName}}s by alphabetical order to find any not associated with a {{$associatedType}}.</p>
				@elseif ($list_type == "alphabetic")
					<p>No {{$tagObjectName}}s have been found in the database. </p>
					
					<p>Add a new {{$tagObjectName}} <a href = "{{route($createRoute)}}">here</a>.</p>
					
				@endif
			</div>
		@endcan
		
		@cannot('create', $classModelPath)
			<br/>
			<div class="text-center">
			@if($list_type == "usage")
				<p>No {{$tagObjectName}}s associated with a {{$associatedType}} have been found in the database. </p>
			
				<p>Try listing {{$tagObjectName}}s by alphabetical order to find any not associated with a {{$associatedType}}.</p>
			@elseif ($list_type == "alphabetic")
				<p>No {{$tagObjectName}}s associated with a {{$associatedType}} have been found in the database.</p>
			@endif
			</div>
		@endcan
	@endif
</div>
<div class="container">
	@if($aliases->count() != 0)
		<div>
			@if(Auth::user())
				<div>
					<b>Sort By:</b>
					@if($list_type == "global")
						<b><a href="{{route($indexAliasRoute)}}?type=global&order={{$list_order}}">Global</a></b>
						<a href="{{route($indexAliasRoute)}}?type=personal&order={{$list_order}}">Personal</a>
						<a href="{{route($indexAliasRoute)}}?type=mixed&order={{$list_order}}">Mixed</a>
					@elseif ($list_type == "personal")
						<a href="{{route($indexAliasRoute)}}?type=global&order={{$list_order}}">Global</a>
						<b><a href="{{route($indexAliasRoute)}}?type=personal&order={{$list_order}}">Personal</a></b>
						<a href="{{route($indexAliasRoute)}}?type=mixed&order={{$list_order}}">Mixed</a>
					@elseif ($list_type == "mixed")
						<a href="{{route($indexAliasRoute)}}?type=global&order={{$list_order}}">Global</a>
						<a href="{{route($indexAliasRoute)}}?type=personal&order={{$list_order}}">Personal</a>
						<b><a href="{{route($indexAliasRoute)}}?type=mixed&order={{$list_order}}">Mixed</a></b>
					@endif	
				</div>
			@endif
			
			<div>
				<b>Display Order:</b>
				@if($list_order == "asc")
					<b><a href="{{route($indexAliasRoute)}}?type={{$list_type}}&order=asc">Ascending</a></b> <a href="{{route($indexAliasRoute)}}?type={{$list_type}}&order=desc">Descending</a>
				@elseif($list_order == "desc")
					<a href="{{route($indexAliasRoute)}}?type={{$list_type}}&order=asc">Ascending</a> <b><a href="{{route($indexAliasRoute)}}?type={{$list_type}}&order=desc">Descending</a></b>
				@endif
			</div>
		</div>
		
		@if($aliases->count() != 0)
			<br/>
			@foreach($aliases as $alias)
				@if((($loop->iteration - 1) % 3) == 0)
					<div class="row">
				@endif
				
				@if($alias->user_id == null)
					<div class="col-xs-4">
						<span class="{{$globalAliasDisplayClass}}"><a href="{{route($showRoute, [$tagObjectName => $alias->tag_object()->first()])}}">{{{$alias->alias}}}</a></span>
					</div>
				@else
					@can('view', $alias)
						<div class="col-xs-4">
							<span class="{{$personalAliasDisplayClass}}"><a href="{{route($showRoute, [$tagObjectName => $alias->tag_object()->first()])}}">{{{$alias->alias}}}</a></span>
						</div>
					@endcan
				@endif
				
				@if((($loop->iteration - 1) % 3) == 2)			
					</div>
				@endif
			@endforeach
			<br/>
			<br/>
			{{ $aliases->links() }}
		@endif
	@else
		@can('create', [$classAliasModelPath, false])
			<div class="text-center">
				No {{$tagObjectName}} aliases have been found in the database. View {{$tagObjectNames}} in the database <a href = "{{route($indexRoute)}}">here.</a>
			</div>
		@endcan
		@cannot('create', [$classAliasModelPath, false])
			<div class="text-center">
				No {{$tagObjectName}} aliases have been found in the database.
			</div>
		@endcan
	@endif
</div>
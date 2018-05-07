@if((Route::is('show_collection')) && (!empty($collection)) && ((Auth::User()->can('create', App\Models\Chapter\Chapter::class)) 
	|| (Auth::User()->can('create', App\Models\Volume\Volume::class)) || (Auth::User()->can('update', $collection)) ))
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
			Collection <span class="caret"></span>
		</a>
		<ul class="dropdown-menu" role="menu">
			@if(count($collection->volumes))
				@can('create', App\Models\Chapter\Chapter::class)
					<li><a href="{{route('create_chapter', ['collection' => $collection])}}"><i class="fa fa-file" aria-hidden="true"></i> Add Chapter</a></li>
				@endcan
			@endif
			@can('create', App\Models\Volume\Volume::class)
				<li><a href="{{route('create_volume', ['collection' => $collection])}}"><i class="fa fa-folder" aria-hidden="true"></i> Add Volume</a></li>
			@endcan
			@if((!empty($collection)))
				@can('update', $collection)
					<li><a href="{{route('edit_collection', ['collection' => $collection])}}"><i class="fa fa-pencil" aria-hidden="true"></i> Edit Collection</a></li>
				@endcan
			@endif
		</ul>
	</li>
@elseif (Route::is('edit_collection') && (!empty($collection)))
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
			Collection <span class="caret"></span>
		</a>
		<ul class="dropdown-menu" role="menu">
			<li><a href="{{route('show_collection', ['collection' => $collection])}}"><i class="fa fa-eye" aria-hidden="true"></i> View Collection</a></li>
			@if(count($collection->volumes))
				@can('create', App\Models\Chapter\Chapter::class)
					<li><a href="{{route('create_chapter', ['collection' => $collection])}}"><i class="fa fa-file" aria-hidden="true"></i> Add Chapter</a></li>
				@endcan
			@endif
			@can('create', App\Models\Volume\Volume::class)
				<li><a href="{{route('create_volume', ['collection' => $collection])}}"><i class="fa fa-folder" aria-hidden="true"></i> Add Volume</a></li>
			@endcan
		</ul>
	</li>
@endif
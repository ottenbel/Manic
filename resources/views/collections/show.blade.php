@extends('layouts.app')

@section('title')
	{{$collection->name}}
@endsection

@section('head')
	<script src="/js/handleexport.js"></script>
	<script src="/js/confirmdelete.js"></script>
@endsection

@section('content')
<div class="container">
	<div id="collection_summary" class="row">
		@if($collection->cover_image != null)
		<div id="cover" class="col-md-3">
			<img src="{{asset($collection->cover_image->name)}}" class="img-responsive img-rounded" alt="Collection Cover" height="100%" width="100%">
		</div>
		@endif
	
		@if($collection->cover_image != null)
			<div id="collection_info" class="col-md-9">
		@else
			<div id="collection_short_info">
		@endif
			<div class="row">
				@if((Route::is('show_collection') && ($collection->volumes->count() > 1)) 
					|| ((Auth::check()) && (Auth::user()->can('delete', $collection)) && (Auth::user()->cannot('update', $collection))))
					@if((Auth::check()) && (Auth::user()->can('export', $collection) && (Auth::user()->can('delete', $collection) && (Auth::user()->cannot('update', $collection)))))
						<div class="col-md-6">
					@elseif(Auth::check() && (Auth::user()->can('export', $collection) || (Auth::user()->can('delete', $collection) && (Auth::user()->cannot('update', $collection)))))
						<div class="col-md-9">
					@else
						<div class="col-md-12">
					@endif
				@else
					<div class="col-md-12">
				@endif
					<h2>{{{$collection->name}}}</h2>
				</div>
				
				@if(Route::is('show_collection') && ($collection->volumes->count() > 1))
					@can('export', $collection)
						<span style="float:right">
							<a class="btn btn-sm btn-success" id="export_collection_button" href="{{route('export_collection', $collection)}}" role="button" onclick="ConfirmExport(this, event)"><i class="fa fa-download" aria-hidden="true"></i> Download Collection</a>
						</style>
					@endcan
				@endif
				
				@if((Auth::check()) && (Auth::user()->can('delete', $collection)) && (Auth::user()->cannot('update', $collection)))
					<span style="float:left">
						<form method="POST" action="{{route('delete_collection', ['collection' => $collection])}}">
							{{ csrf_field() }}
							{{method_field('DELETE')}}
							
							{{ Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete Collection', array('type' => 'submit', 'class' => 'btn btn-sm btn-danger', 'onclick' =>'ConfirmDelete(event)')) }}
						</form>
					</span>
				@endif
			</div>
			
			@if(($collection->primary_artists()->count()) || ($collection->secondary_artists()->count()))
				<div class="row">
					<div class="tag_holder">
						<div class="col-md-2">
							<strong>Artists:</strong>
						</div>
						<div class="col-md-10">
							@foreach($collection->primary_artists()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->get() as $artist)
								@include('partials.tagObjects.display.display-tag-search-object',
									['tagObject' => $artist,
										'tagObjectClass' => 'primary_artists',
										'tagObjectCountClass' => 'artist_count',
										'componentToken' => 'artist'])
							@endforeach
							
							@foreach($collection->secondary_artists()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->get() as $artist)
								@include('partials.tagObjects.display.display-tag-search-object',
									['tagObject' => $artist,
										'tagObjectClass' => 'secondary_artists',
										'tagObjectCountClass' => 'artist_count',
										'componentToken' => 'artist'])
							@endforeach
						</div>
					</div>
				</div>
			@endif
			
			@if(($collection->primary_series()->count()) || ($collection->secondary_series()->count()))
				<div class="row">
					<div class="tag_holder">
						<div class="col-md-2">
							<strong>Series:</strong>
						</div>
						<div class="col-md-10">
							@foreach($collection->primary_series()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->get() as $series)
								@include('partials.tagObjects.display.display-tag-search-object',
									['tagObject' => $series,
										'tagObjectClass' => 'primary_series',
										'tagObjectCountClass' => 'series_count',
										'componentToken' => 'series'])
							@endforeach
							
							@foreach($collection->secondary_series()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->get() as $series)
								@include('partials.tagObjects.display.display-tag-search-object',
									['tagObject' => $series,
										'tagObjectClass' => 'secondary_series',
										'tagObjectCountClass' => 'series_count',
										'componentToken' => 'series'])
							@endforeach
						</div>
					</div>
				</div>
			@endif
			
			@if(($collection->primary_characters()->count()) || ($collection->secondary_characters()->count()))
				<div class="row">
					<div class="tag_holder">
						<div class="col-md-2">
							<strong>Characters:</strong>
						</div>
						<div class="col-md-10">
							@foreach($collection->primary_characters()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->get() as $character)
								@include('partials.tagObjects.display.display-tag-search-object',
									['tagObject' => $character,
										'tagObjectClass' => 'primary_characters',
										'tagObjectCountClass' => 'character_count',
										'componentToken' => 'character'])
							@endforeach
							
							@foreach($collection->secondary_characters()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->get() as $character)
								@include('partials.tagObjects.display.display-tag-search-object',
									['tagObject' => $character,
										'tagObjectClass' => 'secondary_characters',
										'tagObjectCountClass' => 'character_count',
										'componentToken' => 'character'])
							@endforeach
						</div>
					</div>
				</div>
			@endif
			
			@if(($collection->primary_tags()->count()) || ($collection->secondary_tags()->count()))
				<div class="row">
					<div class="tag_holder">
						<div class="col-md-2">
							<strong>Tags:</strong>
						</div>
						<div class="col-md-10">
							@foreach($collection->primary_tags()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->get() as $tag)
								@include('partials.tagObjects.display.display-tag-search-object',
									['tagObject' => $tag,
										'tagObjectClass' => 'primary_tags',
										'tagObjectCountClass' => 'tag_count',
										'componentToken' => 'tag'])
							@endforeach
							
							@foreach($collection->secondary_tags()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->get() as $tag)
								@include('partials.tagObjects.display.display-tag-search-object',
									['tagObject' => $tag,
										'tagObjectClass' => 'secondary_tags',
										'tagObjectCountClass' => 'tag_count',
										'componentToken' => 'tag'])
							@endforeach
						</div>
					</div>
				</div>
			@endif
			
			@if($collection->language != null)
				<div class="row">
					<div class="col-md-2">
						<strong>Language:</strong>
					</div>
					<div class="col-md-10">
						<span class="language_tag"><a href="{{route('index_collection', ['search' => 'language:'. $collection->language->name])}}">{{{$collection->language->name}}}</a></span>
					</div>
				</div>
			@endif
			
			@if($collection->rating != null)
				<div class="row">
					<div class="col-md-2">
						<strong>Rating:</strong>
					</div>
					<div class="col-md-10">
						<span class="rating_tag"><a href="{{route('index_collection', ['search' => 'rating:'. $collection->rating->name])}}">{{{$collection->rating->name}}}</a></span>
					</div>
				</div>	
			@endif
			
			@if($collection->status != null)
				<div class="row">
					<div class="col-md-2">
						<strong>Status:</strong> 
					</div>
					<div class="col-md-10">
						<span class="status_tag"><a href="{{route('index_collection', ['search' => 'status:'. $collection->status->name])}}">{{{$collection->status->name}}}</a></span>
					</div>
				</div>
			@endif
			
			@if($collection->canonical)
				<div class="row">
					<div class="col-md-2">
						<strong>Canonicity:</strong>
					</div>
					<div class="col-md-10">
						<span class="canonical_tag"><a href="{{route('index_collection', ['search' => 'canonical'])}}">Canonical</a></span>
					</div>
				</div>
			@else
				<div class="row">
					<div class="col-md-2">
						<strong>Canonicity:</strong>
					</div>
					<div class="col-md-10">
						<span class="canonical_tag"><a href="{{route('index_collection', ['search' => 'non-canonical'])}}">Non-Canonical</a></span>
					</div>
				</div>
			@endif
			
			<div class="row">
				<div class="col-md-2">
					<strong>Created By:</strong> 
				</div>
				<div class="col-md-10">
					@if ($collection->created_by_user != null)
						@if(Auth::check() && Auth::user()->hasPermissionTo('View User'))
							<a href="{{route('admin_show_user', ['user' => $collection->created_by_user])}}">{{$collection->created_by_user->name}}</a>
						@else
							{{$collection->created_by_user->name}}
						@endif
					 @ {{$collection->created_at}}
					@else
						Unknown @ {{$collection->created_at}}
					@endif
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-2">
					<strong>Updated By:</strong> 
				</div>
				<div class="col-md-10">
					@if($collection->updated_by_user != null)
						@if(Auth::check() && Auth::user()->hasPermissionTo('View User'))
							<a href="{{route('admin_show_user', ['user' => $collection->updated_by_user])}}">{{$collection->updated_by_user->name}}</a>
						@else
							{{$collection->created_by_user->name}}
						@endif
						 @ {{$collection->updated_at}}
					@else
						Unknown @ {{$collection->updated_at}}
					@endif
				</div>
			</div>
		</div>
	</div>
	@if(($collection->description != null) && ($collection->description != ""))
		<br/>
		<br/>
		<div id="collection_summary_text" class="row">
				{!!nl2br(e($collection->description))!!}
		</div>
	@endif
	
	@include('partials.collection.show', 
	[
	'volumes' => $collection->volumes(), 'editVolume' => false, 'editChapter' => false, 'scanalatorLinkRoute' => 'index_collection', 'hideVolumes' => false
	])
	
	@if(($collection->parent_collection != null) || (count($collection->child_collections)))
		<br/>
		<p>
			<h3>Alternative Versions of This Collection</h3>
			@if($collection->parent_collection != null)
				<button class="accordion">Parent Collection:</button>
				<div class="volume_panel" id="parent_collection">
					<span class="col-md-1">
						@if($collection->parent_collection->cover_image != null)
							<a href="{{route('show_collection', ['collection' => $collection->parent_collection])}}"><img src="{{asset($collection->parent_collection->cover_image->thumbnail)}}" class="img-responsive img-rounded" alt="Collection Cover"></a>
						@endif
					</span>
					<span class="col-md-11">
						<a href="{{route('show_collection', ['collection' => $collection->parent_collection])}}">{{$collection->parent_collection->name}}</a>
						@if($collection->parent_collection->language != null)
							({{$collection->parent_collection->language->name}})
						@endif
					</span>
				</div>
			@endif
	
			@if(count($sibling_collections))
				@if(count($sibling_collections) == 1)
					<button class="accordion">Sibling Collection</button>
				@else
					<button class="accordion">Sibling Collections</button>
				@endif
				<div class="volume_panel" id="sibling_collections">
					@foreach($sibling_collections as $sibling_collection)
					<div id="sibling_collection">
						<span class="col-md-1">
							@if($sibling_collection->cover_image != null)
								<a href="{{route('show_collection', ['collection' => $sibling_collection])}}"><img src="{{asset($sibling_collection->cover_image->thumbnail)}}" class="img-responsive img-rounded" alt="Responsive image"></a>
							@endif
						</span>
						<span class="col-md-11">
							<a href="{{route('show_collection', ['collection' => $sibling_collection])}}">{{$sibling_collection->name}}</a>
							@if($sibling_collection->language != null)
								({{$sibling_collection->language->name}})
							@endif
						</span>
					</div>
					@endforeach
				</div>
			@endif
			
			@if(count($collection->child_collections))
				@if(count($collection->child_collections) == 1)
					<button class="accordion">Child Collection:</button>
				@else
					<button class="accordion">Child Collections:</button>
				@endif

				<div class="volume_panel" id="child_collections">					
					@foreach($collection->child_collections as $child_collection)
						<div id="child_collection">
							<span class="col-md-1">
								@if($child_collection->cover_image != null)
									<a href="{{route('show_collection', ['collection' => $child_collection])}}"><img src="{{asset($child_collection->cover_image->thumbnail)}}" class="img-responsive img-rounded" alt="Responsive image"></a>
								@endif
							</span>
							<span class="col-md-11">
								<a href="{{route('show_collection', ['collection' => $child_collection])}}">{{$child_collection->name}}</a>
								@if($child_collection->language != null)
									({{$child_collection->language->name}})
								@endif
							</span>
						</div>
					@endforeach
				</div>
			@endif
		</p>
	@endif
</div>

@endsection

@section('footer')

@endsection
@extends('layouts.app')

@section('title')
	{{$collection->name}}
@endsection

@section('header')

@endsection

@section('content')
<div class="container">
	<div id="collection_summary" class="row">
		@if($collection->cover_image != null)
		<div id="cover" class="col-md-3">
			<img src="{{asset($collection->cover_image->name)}}" class="img-responsive img-rounded" alt="Responsive image" height="100%" width="100%">
		</div>
		@endif
	
		@if($collection->cover_image != null)
			<div id="collection_info" class="col-md-9">
		@else
			<div id="collection_short_info">
		@endif		
			<h2>{{{$collection->name}}}</h2>
			@if((count($collection->primary_artists)) || (count($collection->secondary_artists)))
				<div class="tag_holder"><strong>Artists:</strong>
					@foreach($collection->primary_artists()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->get() as $artist)
						<span class="primary_artists"><a href="/artist/{{$artist->id}}">{{{$artist->name}}} <span class="artist_count">({{$artist->usage_count()}})</span></a></span>
					@endforeach
					
					@foreach($collection->secondary_artists()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->get() as $artist)
						<span class="secondary_artists"><a href="/artist/{{$artist->id}}">{{{$artist->name}}} <span class="artist_count">({{$artist->usage_count()}})</span></a></span>
					@endforeach
				</div>
			@endif
			
			@if((count($collection->primary_series)) || (count($collection->secondary_series)))
				<div class="tag_holder"><strong>Series:</strong>
					@foreach($collection->primary_series()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->get() as $series)
						<span class="primary_series"><a href="/series/{{$series->id}}">{{{$series->name}}} <span class="series_count">({{$series->usage_count()}})</span></a></span>
					@endforeach
					
					@foreach($collection->secondary_series()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->get() as $series)
						<span class="secondary_series"><a href="/series/{{$series->id}}">{{{$series->name}}} <span class="series_count">({{$series->usage_count()}})</span></a></span>
					@endforeach
				</div>
			@endif
			
			@if((count($collection->primary_tags)) || (count($collection->secondary_tags)))
				<div class="tag_holder"><strong>Tags:</strong>
					@foreach($collection->primary_tags()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->get() as $tag)
						<span class="primary_tags"><a href="/tag/{{$tag->id}}">{{{$tag->name}}} <span class="tag_count"> ({{$tag->usage_count()}})</span></a></span>
					@endforeach
					
					@foreach($collection->secondary_tags()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->get() as $tag)
						<span class="secondary_tags"><a href="/tag/{{$tag->id}}">{{{$tag->name}}} <span class="tag_count">({{$tag->usage_count()}})</span></a></span>
					@endforeach
			@endif
			</div>
			
			@if($collection->language != null)
				<div>
					<strong>Language:</strong> {{{$collection->language->name}}}
				</div>
			@endif
			
			@if($collection->rating != null)
				<div>
					<strong>Rating:</strong> {{{$collection->rating->name}}}
				</div>	
			@endif
			
			@if($collection->status != null)
				<div>
					<strong>Status:</strong> {{{$collection->status->name}}}
				</div>
			@endif
			
			<div>
				<strong>Created By:</strong> <a href="/user/{{$collection->id}}">{{{$collection->created_by_user->name}}}</a> @ {{$collection->created_at}}
			</div>
			
			<div>
				<strong>Last Updated By:</strong> <a href="/user/{{$collection->id}}">{{{$collection->updated_by_user->name}}}</a> @ {{$collection->updated_at}}
			</div>
		</div>
	</div>
	<br/>
	<br/>
	<div id="collection_summary_text" class="row">
			{!!html_entity_decode(nl2br($collection->description))!!}
	</div>
	<br/>
	
	@if(count($collection->volumes))
		@foreach($collection->volumes()->orderBy('number', 'asc')->get() as $volume)
			<button class="accordion">
				@if($volume->name != null && $volume->name != "")
					Volume {{$volume->number}} - $volume->name
				@else
					Volume {{$volume->number}}
				@endif 
			</button>
			<div class="volume_panel">
				@foreach($volume->chapters()->orderBy('number', 'asc')->get() as $chapter)
					<div>
						@if($chapter->name != null && $chapter->name != "")
							<a href="/chapter/{{$chapter->id}}">Chapter {{$chapter->number}}</a> - {{{$chapter->name}}}
						@else
							<a href="/chapter/{{$chapter->id}}">Chapter {{$chapter->number}}</a>
						@endif
					</div>
				@endforeach
				<div><a href="/chapter/create/{{$volume->id}}">Add New Chapter</a></div>
			</div>
		@endforeach
	@else
		<div><a href="/volume/create/{{$collection->id}}">Add New Volume</a></div>
	@endif
	
	<br/>
	@if(($collection->parent_collection != null) || (count($collection->child_collections)))
		<p>
			<h3>Alternative Versions of This Collection</h3>
			@if($collection->parent_collection != null)
				<button class="accordion">Parent Collection:</button>
				<div class="volume_panel" id="parent_collection">
					<span class="col-md-1">
						@if($collection->parent_collection->cover_image != null)
							<a href="/collection/{{$collection->parent_collection->id}}"><img src="{{asset($collection->parent_collection->cover_image->name)}}" class="img-responsive img-rounded" alt="Responsive image"></a>
						@endif
					</span>
					<span class="col-md-11">
						<a href="/collection/{{$collection->parent_collection->id}}">{{$collection->parent_collection->name}}</a>
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
								<a href="/collection/{{$sibling_collection->id}}"><img src="{{asset($sibling_collection->cover_image->name)}}" class="img-responsive img-rounded" alt="Responsive image"></a>
							@endif
						</span>
						<span class="col-md-11">
							<a href="/collection/{{$sibling_collection->id}}">{{$sibling_collection->name}}</a>
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
									<a href="/collection/{{$child_collection->id}}"><img src="{{asset($child_collection->cover_image->name)}}" class="img-responsive img-rounded" alt="Responsive image"></a>
								@endif
							</span>
							<span class="col-md-11">
								<a href="/collection/{{$child_collection->id}}">{{$child_collection->name}}</a>
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
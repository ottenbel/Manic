@extends('layouts.app')

@section('title')
	{{$collection->name}}
@endsection

@section('header')

@endsection

@section('content')
<div class="container">
	<div id="collection_summary">
		@if($collection->cover_image != null)
		<div id="cover" class="col-md-4">
			<img src="{{asset($collection->cover_image->name)}}" class="img-fluid" alt="Responsive image">
		</div>
		@endif
	
		@if($collection->cover_image != null)
			<div id="collection_info" class="col-md-8">
		@else
			<div id="collection_info">
		@endif		
			<h4>{{{$collection->name}}}</h4>
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
	
	<br/>
	<p id="collection_summary">
			{!!html_entity_decode(nl2br($collection->description))!!}
	</p>
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
	
	@if(($collection->parent_collection != null) || (count($collection->child_collections)))
		<p>
			Alternative Versions of this Collection:
			@if($collection->parent_collection != null)
				<div id="parent_collection">
				Parent Collection:
					<a href="/collection/{{$collection->parent_collection->id}}">{{$collection->parent_collection->name}}</a>
					@if($collection->parent_collection->language != null)
						({{$collection->parent_collectionlanguage->name}})
					@endif
				</div>
			@endif
	
			@if(count($sibling_collections))
				<div id="sibling_collections">
					Sibling Collection(s):
					@foreach($sibling_collections as $sibling_collection)
					<div id="sibling_collection">
						<a href="/collection/{{$sibling_collection->id}}">{{$sibling_collection->name}}</a>
						@if($sibling_collection->language != null)
							({{$sibling_collection->language->name}})
						@endif
					</div>
					@endforeach
				</div>
			@endif
			
			@if(count($sibling_collections))
				<div id="child_collections">
					Child Collection(s):
					@foreach($collection->child_collections as $child_collection)
						<div id="child_collection">
							<a href="/collection/{{$child_collection->id}}">{{$child_collection->name}}</a>
						@if($child_collection->language != null)
							({{$child_collection->language->name}})
						@endif
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
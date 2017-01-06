@extends('layouts.app')

@section('title')
	{{$collection->name}}
@endsection

@section('header')

@endsection

@section('content')
<div class="container">
	<div id="collection_summary">
		@if($collection->cover != null)
		<div id="cover" class="col-md-4">
			<img src="{{$collection->cover->name}}" class="img-fluid" alt="Responsive image">
		</div>
		@endif
	
		@if($collection->cover != null)
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
					
					@foreach($collection->secondary_tags()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->get as $tag)
						<span class="secondary_tags"><a href="/tag/{{$tag->id}}">{{{$tag->name}}} <span class="tag_count">({{$tag->usage_count()}})</span></a></span>
					@endforeach
			@endif
			</div>
			
			@if($collection->language != null)
				<div>
					<strong>Language:</strong> {{{$collection->language->name}}}
				</div>
			@endif
				
			@if($collection->status != null)
				<div>
					<strong>Status:</strong> {{{$collection->status->name}}}
				</div>
			@endif
			
			@if($collection->rating != null)
				<div>
					<strong>Rating:</strong> {{{$collection->rating->name}}}
				</div>	
			@endif
			
			<div>
				<strong>Created By:</strong> <a href="/user/{{$collection->id}}">{{{$collection->created_by_user->name}}}</a> @ {{$collection->created_at}}
			</div>
			
			<div>
				<strong>Updated By:</strong> <a href="/user/{{$collection->id}}">{{{$collection->updated_by_user->name}}}</a> @ {{$collection->updated_at}}
			</div>
	</div>
	
	<p id="collection_summary">
		{!nl2br($collection->description)!}
	</p>
	
	@if(count($collection->volumes))
	
	@else
		<div></div>
	@endif
</div>

@endsection

@section('footer')

@endsection
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
	
	</div>
	
	<div id="collection_summary">
		{{{$collection->summary}}}
	</div>

	@if(Auth::user())
		<div><a href="/collection/{{$collection->id}}/edit" class = "btn btn-default">Edit Collection</a></div>
	@endif	
	
	
</div>

@endsection

@section('footer')

@endsection
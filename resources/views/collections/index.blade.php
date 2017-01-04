@extends('layouts.app')

@section('title')
Index - Page {{$collections->currentPage()}}
@endsection

@section('header')

@endsection

@section('content')
<div class="container">
	<div class="row">
		@if($collections->count() == 0)
			<div>
				No collections have been found in the database.  Add a new collection <a href = "{{url('/collection/create')}}">here.</a>
			</div>
		@else
			<table class="table table-striped">
				@foreach($collections as $collection)
					<tr>
						<td>
						@if($collection->cover != null)
							<img src="{{$collection->cover->name}}" class="img-thumbnail" height="100px" width="100%">
						@endif
						</td>
						<td>
							<div><a href="/collection/{{$collection->id}}"><h5>{{{$collection->name}}}</h5></a></div>
							
							@if((count($collection->primary_artists)) || (count($collection->secondary_artists)))
								<div class="tag_holder">Artists:
									@foreach($collection->primary_artists()->take(10)->get() as $artist)
										<span class="primary_artists"><a href="/artist/{{$artist->id}}">{{{$artist->name}}}</a></span>
									@endforeach
									@if(10 > count($collection->primary_artists))
										@foreach($collection->secondary_artists()->take(10 - count($collection->primary_artists))->get() as $artist)
											<span class="secondary_artists"><a href="/artist/{{$artist->id}}">{{{$artist->name}}}</a></span>
										@endforeach
									@endif
								</div>
							@endif
							
							@if((count($collection->primary_tags)) || (count($collection->secondary_tags)))
								<div><strong>Tags:</strong>
									@foreach($collection->primary_tags()->take(10)->get() as $tag)
										<span class="primary_tags"><a href="/tag/{{$tag->id}}">{{{$tag->name}}}</a></span>
									@endforeach
									@if(10 > count($collection->primary_tags))
										@foreach($collection->secondary_tags()->take(10 - count($collection->primary_tags))->get as $tag)
											<span class="secondary_tags"><a href="/tag/{{$tag->id}}">{{{$tag->name}}}</a></span>
										@endforeach
									@endif
								</div>
							@endif
							
							@if($collection->rating != null || $collection->status != null)
								<div>
									@if($collection->rating != null)
										<span><strong>Rating:</strong> {{{$collection->rating->name}}}</span>
									@endif
									@if($collection->status != null)
										<span><strong>Status:</strong> {{{$collection->status->name}}}</span>
									@endif
								</div>
							@endif
						</td>
					</tr>
				@endforeach
			</table>
		@endif
	</div>
	{{ $collections->links() }}
</div>
@endsection

@section('footer')

@endsection
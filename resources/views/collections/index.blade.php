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
							<div><a href="/collection/{{$collection->id}}">{{{$collection->name}}}</a></div>
							
							@if(count($collection->primary_artists()))
								<div>Artists:
									<ul class="tags">
									@foreach($collection->primary_artists()->take(10)->get as $artist)
										<li><a href="/artist/{{$artist->id}}">{{{$artist->name}}}</a></li>
									@endforeach
									</ul>
								</div>
							@endif
							
							@if(count($collection->primary_tags()))
								<div>Tags:
									<ul class="tags">
									@foreach($collection->primary_tags()->take(10)->get() as $tag)
										<li><a href="/tag/{{$tag->id}}">{{{$tag->name}}}</a></li>
									@endforeach
									</ul>
								</div>
							@endif
							<div>
								@if($collection->rating != null)
									{{{$collection->rating->name}}}
								@endif
							</div>
							<div>
								@if($collection->status != null)
									{{{$collection->status->name}}}
								@endif
							</div>
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
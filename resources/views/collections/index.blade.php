@extends('layouts.app')

@section('title')
Index - Page {{$collections->currentPage()}}
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	<div class="row">
		@if($collections->count() == 0)
			@if(Auth::user())
				<div>
					No collections have been found in the database. Add a new collection <a href = "{{url('/collection/create')}}">here.</a>
				</div>
			@else
				<div>
					No collections have been found in the database.
				</div>
			@endif
		@else
			<table class="table table-striped">
				@foreach($collections as $collection)
					<tr>
						<td class="col-xs-2">
						@if($collection->cover_image != null)
							<a href="/collection/{{$collection->id}}"><img src="{{asset($collection->cover_image->name)}}" class="img-responsive img-rounded"></a>
						@endif
						</td>
						<td class="col-xs-10">
							<div><a href="/collection/{{$collection->id}}"><h4>{{{$collection->name}}}</h4></a></div>
							
							@if(($collection->primary_artists()->count()) || ($collection->secondary_artists()->count()))
								<div class="tag_holder"><strong>Artists:</strong>
									@foreach($collection->primary_artists()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->take(10)->get() as $artist)
										<span class="primary_artists"><a href="/artist/{{$artist->id}}">{{{$artist->name}}} <span class="artist_count">({{$artist->usage_count()}})</span></a></span>
									@endforeach
									@if(10 > $collection->primary_artists()->count())
										@foreach($collection->secondary_artists()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->take(10 - $collection->primary_artists()->count())->get() as $artist)
											<span class="secondary_artists"><a href="/artist/{{$artist->id}}">{{{$artist->name}}} <span class="artist_count">({{$artist->usage_count()}})</span></a></span>
										@endforeach
									@endif
								</div>
							@endif
							
							@if(($collection->primary_series()->count()) || ($collection->secondary_series()->count()))
								<div class="tag_holder"><strong>Series:</strong>
									@foreach($collection->primary_series()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->take(10)->get() as $series)
										<span class="primary_series"><a href="/series/{{$series->id}}">{{{$series->name}}} <span class="series_count">({{$series->usage_count()}})</span></a></span>
									@endforeach
									@if(10 > $collection->primary_series()->count())
										@foreach($collection->secondary_series()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->take(10 - $collection->primary_series()->count())->get() as $series)
											<span class="secondary_series"><a href="/series/{{$series->id}}">{{{$series->name}}} <span class="series_count">({{$series->usage_count()}})</span></a></span>
										@endforeach
									@endif
								</div>
							@endif
							
							@if(($collection->primary_characters()->count()) || ($collection->secondary_characters()->count()))
								<div class="tag_holder"><strong>Characters:</strong>
									@foreach($collection->primary_characters()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->take(10)->get() as $character)
										<span class="primary_characters"><a href="/character/{{$character->id}}">{{{$character->name}}} <span class="character_count">({{$character->usage_count()}})</span></a></span>
									@endforeach
									@if(10 > $collection->primary_characters()->count())
										@foreach($collection->secondary_characters()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->take(10 - $collection->primary_characters()->count())->get() as $character)
											<span class="secondary_characters"><a href="/character/{{$character->id}}">{{{$character->name}}} <span class="character_count">({{$character->usage_count()}})</span></a></span>
										@endforeach
									@endif
								</div>
							@endif
							
							@if(($collection->primary_tags()->count()) || ($collection->secondary_tags()->count()))
								<div class="tag_holder"><strong>Tags:</strong>
									@foreach($collection->primary_tags()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->take(10)->get() as $tag)
										<span class="primary_tags"><a href="/tag/{{$tag->id}}">{{{$tag->name}}} <span class="tag_count"> ({{$tag->usage_count()}})</span></a></span>
									@endforeach
									@if(10 > $collection->primary_tags()->count())
										@foreach($collection->secondary_tags()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->take(10 - $collection->primary_tags->count())->get() as $tag)
											<span class="secondary_tags"><a href="/tag/{{$tag->id}}">{{{$tag->name}}} <span class="tag_count">({{$tag->usage_count()}})</span></a></span>
										@endforeach
									@endif
								</div>
							@endif
							</td>
							
							<td class="col-xs-10">
								@if($collection->rating != null)
										<strong>Rating:</strong> {{{$collection->rating->name}}}
									@endif
									
									@if($collection->status != null)
										<strong>Status:</strong> {{{$collection->status->name}}}
									@endif
									
									@if($collection->language != null)
										<strong>Language:</strong> {{{$collection->language->name}}}
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
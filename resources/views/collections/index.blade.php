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
			@can('create', App\Models\Collection::class)
				<div class="text-center">
					No collections have been found in the database. Add a new collection <a href = "{{route('create_collection')}}">here.</a>
				</div>
				<br/>
			@endcan
			@cannot('create', App\Models\Collection::class)
				<div class="text-center">
					No collections have been found in the database.
				</div>
				<br/>
			@endcan
		@else
			<table class="table table-striped">
				@foreach($collections as $collection)
					<tr>
						<td class="col-xs-2">
						@if($collection->cover_image != null)
							<a href="{{route('show_collection', ['collection' => $collection])}}"><img src="{{asset($collection->cover_image->name)}}" class="img-responsive img-rounded"></a>
						@endif
						</td>
						<td class="col-xs-10">
							<div><a href="{{route('show_collection', ['collection' => $collection])}}"><h4>{{{$collection->name}}}</h4></a></div>
							
							@if(($collection->primary_artists()->count()) || ($collection->secondary_artists()->count()))
								<div class="row">
									<div class="tag_holder">
										<div class="col-md-2">
											<strong>Artists:</strong>
										</div>
										<div class="col-md-10">
											@foreach($collection->primary_artists()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->take(10)->get() as $artist)
												<span class="primary_artists"><a href="/artist/{{$artist->id}}">{{{$artist->name}}} <span class="artist_count">({{$artist->usage_count()}})</span></a></span>
											@endforeach
											@if(10 > $collection->primary_artists()->count())
												@foreach($collection->secondary_artists()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->take(10 - $collection->primary_artists()->count())->get() as $artist)
													<span class="secondary_artists"><a href="/artist/{{$artist->id}}">{{{$artist->name}}} <span class="artist_count">({{$artist->usage_count()}})</span></a></span>
												@endforeach
											@endif
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
											@foreach($collection->primary_series()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->take(10)->get() as $series)
												<span class="primary_series"><a href="/series/{{$series->id}}">{{{$series->name}}} <span class="series_count">({{$series->usage_count()}})</span></a></span>
											@endforeach
											@if(10 > $collection->primary_series()->count())
												@foreach($collection->secondary_series()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->take(10 - $collection->primary_series()->count())->get() as $series)
													<span class="secondary_series"><a href="/series/{{$series->id}}">{{{$series->name}}} <span class="series_count">({{$series->usage_count()}})</span></a></span>
												@endforeach
											@endif
										</div>
									</div>
								</div>
							@endif
							
							@if(($collection->primary_characters()->count()) 
								|| $collection->secondary_characters()->count())
								<div class="row">
									<div class="tag_holder">
										<div class="col-md-2">
											<strong>Characters:</strong>
										</div>
										<div class="col-md-10">
											@foreach($collection->primary_characters()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->take(10)->get() as $character)
												<span class="primary_characters"><a href="/character/{{$character->id}}">{{{$character->name}}} <span class="character_count">({{$character->usage_count()}})</span></a></span>
											@endforeach
											@if(10 > $collection->primary_characters()->count())
												@foreach($collection->secondary_characters()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->take(10 - $collection->primary_characters()->count())->get() as $character)
													<span class="secondary_characters"><a href="/character/{{$character->id}}">{{{$character->name}}} <span class="character_count">({{$character->usage_count()}})</span></a></span>
												@endforeach
											@endif
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
											@foreach($collection->primary_tags()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->take(10)->get() as $tag)
												<span class="primary_tags"><a href="{{route('show_tag', ['tag' => $tag])}}">{{{$tag->name}}} <span class="tag_count"> ({{$tag->usage_count()}})</span></a></span>
											@endforeach
											@if(10 > $collection->primary_tags()->count())
												@foreach($collection->secondary_tags()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->take(10 - $collection->primary_tags->count())->get() as $tag)
													<span class="secondary_tags"><a href="{{route('show_tag', ['tag' => $tag])}}">{{{$tag->name}}} <span class="tag_count">({{$tag->usage_count()}})</span></a></span>
												@endforeach
											@endif
										</div>
									</div>
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
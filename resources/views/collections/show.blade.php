@extends('layouts.app')

@section('title')
	{{$collection->name}}
@endsection

@section('head')

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
				<div class="col-md-12">
					<h2>{{{$collection->name}}}</h2>
				</div>
			</div>
			@if(($collection->primary_artists()->count()) || ($collection->secondary_artists()->count()))
				<div class="row">
					<div class="tag_holder">
						<div class="col-md-2">
							<strong>Artists:</strong>
						</div>
						<div class="col-md-10">
							@foreach($collection->primary_artists()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->get() as $artist)
								<span class="primary_artists"><a href="{{route('show_artist', ['artist' => $artist])}}">{{{$artist->name}}} <span class="artist_count">({{$artist->usage_count()}})</span></a></span>
							@endforeach
							
							@foreach($collection->secondary_artists()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->get() as $artist)
								<span class="secondary_artists"><a href="{{route('show_artist', ['artist' => $artist])}}">{{{$artist->name}}} <span class="artist_count">({{$artist->usage_count()}})</span></a></span>
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
								<span class="primary_series"><a href="/series/{{$series->id}}">{{{$series->name}}} <span class="series_count">({{$series->usage_count()}})</span></a></span>
							@endforeach
							
							@foreach($collection->secondary_series()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->get() as $series)
								<span class="secondary_series"><a href="/series/{{$series->id}}">{{{$series->name}}} <span class="series_count">({{$series->usage_count()}})</span></a></span>
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
								<span class="primary_characters"><a href="/character/{{$character->id}}">{{{$character->name}}} <span class="character_count">({{$character->usage_count()}})</span></a></span>
							@endforeach
							
							@foreach($collection->secondary_characters()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->get() as $character)
								<span class="secondary_characters"><a href="/character/{{$character->id}}">{{{$character->name}}} <span class="character_count">({{$character->usage_count()}})</span></a></span>
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
								<span class="primary_tags"><a href="{{route('show_tag', ['tag' => $tag])}}">{{{$tag->name}}} <span class="tag_count"> ({{$tag->usage_count()}})</span></a></span>
							@endforeach
							
							@foreach($collection->secondary_tags()->withCount('collections')->orderBy('collections_count', 'desc')->orderBy('name', 'asc')->get() as $tag)
								<span class="secondary_tags"><a href="{{route('show_tag', ['tag' => $tag])}}">{{{$tag->name}}} <span class="tag_count">({{$tag->usage_count()}})</span></a></span>
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
						{{{$collection->language->name}}}
					</div>
				</div>
			@endif
			
			@if($collection->rating != null)
				<div class="row">
					<div class="col-md-2">
						<strong>Rating:</strong>
					</div>
					<div class="col-md-10">
						{{{$collection->rating->name}}}
					</div>
				</div>	
			@endif
			
			@if($collection->status != null)
				<div class="row">
					<div class="col-md-2">
						<strong>Status:</strong> 
					</div>
					<div class="col-md-10">
						{{{$collection->status->name}}}
					</div>
				</div>
			@endif
			
			@if($collection->canonical)
				<div class="row">
					<div class="col-md-2">
						<strong>Canonicity:</strong>
					</div>
					<div class="col-md-10">
						Canonical
					</div>
				</div>
			@else
				<div class="row">
					<div class="col-md-2">
						<strong>Canonicity:</strong>
					</div>
					<div class="col-md-10">
						Non-Canon
					</div>
				</div>
			@endif
			
			<div class="row">
				<div class="col-md-2">
					<strong>Created By:</strong> 
				</div>
				<div class="col-md-10">
					@if ($collection->created_by_user != null)
						<a href="/user/{{$collection->id}}">{{{$collection->created_by_user->name}}}</a> @ {{$collection->created_at}}
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
						<a href="/user/{{$collection->id}}">{{{$collection->updated_by_user->name}}}</a> @ {{$collection->updated_at}}
					@else
						Unknown @ {{$collection->updated_at}}
					@endif
				</div>
			</div>
		</div>
	</div>
	<br/>
	<br/>
	<div id="collection_summary_text" class="row">
			{!!html_entity_decode(nl2br($collection->description))!!}
	</div>
	<br/>
	
	@foreach($collection->volumes()->orderBy('volume_number', 'asc')->get() as $volume)
		<button class="accordion">
			@if($volume->name != null && $volume->name != "")
				Volume {{$volume->volume_number}} - {{{$volume->name}}}
			@else
				Volume {{$volume->volume_number}}
			@endif 
		</button>
		<div class="volume_panel">
			@if($volume->cover_image != null)
				<div id="cover" class="col-md-2">
					<img src="{{asset($volume->cover_image->name)}}" class="img-responsive img-rounded" alt="Volume Cover" height="100%" width="100%">
				</div>
				<div id="cover" class="col-md-10">
			@else
				<div>
			@endif
					<table width="100%">
						@foreach($volume->chapters()->orderBy('chapter_number', 'asc')->get() as $chapter)
							<tr>
								@if($chapter->name != null && $chapter->name != "")
									<td>
										<a href="{{route('show_chapter', ['chapter' => $chapter])}}">Chapter {{$chapter->chapter_number}}</a> - {{{$chapter->name}}}
									</td>
								@else
									<td>
										<a href="{{route('show_chapter', ['chapter' => $chapter])}}">Chapter {{$chapter->chapter_number}}</a>
									</td>
								@endif
								<td>
									@if(($chapter->primary_scanalators()) || ($chapter->secondary_scanalators()))
										<div class="scanalator_holder">			
										@foreach($chapter->primary_scanalators()->withCount('chapters')->orderBy('chapters_count', 'desc')->orderBy('name', 'asc')->get() as $scanalator)
												<span class="primary_scanalators"><a href="/scanalator/{{$scanalator->id}}">{{{$scanalator->name}}} <span class="scanalator_count"> ({{$scanalator->usage_count()}})</span></a></span>
											@endforeach
											
											@foreach($chapter->secondary_scanalators()->withCount('chapters')->orderBy('chapters_count', 'desc')->orderBy('name', 'asc')->get() as $scanalator)
												<span class="secondary_scanalators"><a href="/scanalator/{{$scanalator->id}}">{{{$scanalator->name}}} <span class="scanalator_count">({{$scanalator->usage_count()}})</span></a></span>
											@endforeach
										</div>
									@endif
								</td>
								@if($chapter->source != null)
									<td>
										<span class="source_tag"><a href="{{$chapter->source}}">Source</a></span>
									</td>
								@endif
							</tr>
						@endforeach
					</table>
				</div>
		</div>
	@endforeach
	
	<br/>
	@if(($collection->parent_collection != null) || (count($collection->child_collections)))
		<p>
			<h3>Alternative Versions of This Collection</h3>
			@if($collection->parent_collection != null)
				<button class="accordion">Parent Collection:</button>
				<div class="volume_panel" id="parent_collection">
					<span class="col-md-1">
						@if($collection->parent_collection->cover_image != null)
							<a href="{{route('show_collection', ['collection' => $collection->parent_collection])}}"><img src="{{asset($collection->parent_collection->cover_image->name)}}" class="img-responsive img-rounded" alt="Collection Cover"></a>
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
								<a href="{{route('show_collection', ['collection' => $sibling_collection])}}"><img src="{{asset($sibling_collection->cover_image->name)}}" class="img-responsive img-rounded" alt="Responsive image"></a>
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
									<a href="{{route('show_collection', ['collection' => $child_collection])}}"><img src="{{asset($child_collection->cover_image->name)}}" class="img-responsive img-rounded" alt="Responsive image"></a>
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
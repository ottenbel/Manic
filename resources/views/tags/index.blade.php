@extends('layouts.app')

@section('title')
	Tags - Page {{$tags->currentPage()}}
@endsection

@section('head')

@endsection

@section('content')
	<div class="container">
		@if($tags->count() != 0)
			<div>
				<div>
					<b>Sort By:</b>
					@if($list_type == "usage")
						<b><a href="{{route('index_tag')}}?type=usage&order={{$list_order}}">Tag Usage</a></b> <a href="{{route('index_tag')}}?type=alphabetic&order={{$list_order}}">Alphabetic</a>
					@elseif ($list_type == "alphabetic")
						<a href="{{route('index_tag')}}?type=usage&order={{$list_order}}">Tag Usage</a> <b><a href="{{route('index_tag')}}?type=alphabetic&order={{$list_order}}">Alphabetic</a></b>
					@endif
				</div>
				
				<div>
					<b>Display Order:</b>
					@if($list_order == "asc")
						<b><a href="{{route('index_tag')}}?type={{$list_type}}&order=asc">Ascending</a></b> <a href="{{route('index_tag')}}?type={{$list_type}}&order=desc">Descending</a>
					@elseif($list_order == "desc")
						<a href="{{route('index_tag')}}?type={{$list_type}}&order=asc">Ascending</a> <b><a href="{{route('index_tag')}}?type={{$list_type}}&order=desc">Descending</a></b>
					@endif
				</div>
			</div>
			
			@foreach($tags as $tag)
				@if((($loop->iteration - 1) % 3) == 0)
					<div class="row">
				@endif
				
				<div class="col-xs-4">
					<span class="primary_tags"><a href="{{route('show_tag', ['tag' => $tag])}}">{{{$tag->name}}} <span class="tag_count">({{$tag->usage_count()}})</span></a></span>
				</div>
				
				@if((($loop->iteration - 1) % 3) == 2)			
					</div>
				@endif
			@endforeach
			<br/>
			<br/>
			{{ $tags->links() }}
		@else
			@can('create', App\Models\TagObjects\Tag\Tag::class)
				<div class="text-center">
					No tags have been found in the database. Add a new tag <a href = "{{route('create_tag')}}">here.</a>
				</div>
				<br/>
			@endcan
			
			@cannot('create', App\Models\TagObjects\Tag\Tag::class)
				<div class="text-center">
					No tags have been found in the database.
				</div>
				<br/>
			@endcan
		@endif
	</div>
@endsection

@section('footer')

@endsection
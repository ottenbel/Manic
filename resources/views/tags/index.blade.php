@extends('layouts.app')

@section('title')
	Tags - Page {{$tags->currentPage()}}
@endsection

@section('head')

@endsection

@section('content')
	<div class="container">
		
		<div>
			<div>
				<b>Sort By:</b>
				@if($list_type == "usage")
					<b><a href="/tag?type=usage&order={{$list_order}}">Tag Usage</a></b> <a href="/tag?type=alphabetic&order={{$list_order}}">Alphabetic</a>
				@elseif ($list_type == "alphabetic")
					<a href="/tag?type=usage&order={{$list_order}}">Tag Usage</a> <b><a href="/tag?type=alphabetic&order={{$list_order}}">Alphabetic</a></b>
				@endif
			</div>
			
			<div>
				<b>Display Order:</b>
				@if($list_order == "asc")
					<b><a href="/tag?type={{$list_type}}&order=asc">Ascending</a></b> <a href="/tag?type={{$list_type}}&order=desc">Descending</a>
				@elseif($list_order == "desc")
					<a href="/tag?type={{$list_type}}&order=asc">Ascending</a> <b><a href="/tag?type={{$list_type}}&order=desc">Descending</a></b>
				@endif
			</div>
		</div>
		
		@foreach($tags as $tag)
			@if((($loop->iteration - 1) % 3) == 0)
				<div class="row">
			@endif
			
			<div class="col-xs-4">
				<span class="primary_tags"><a href="/tag/{{$tag->id}}">{{{$tag->name}}} <span class="tag_count">({{$tag->usage_count()->count()}})</span></a></span>
			</div>
			
			@if((($loop->iteration - 1) % 3) == 2)			
				</div>
			@endif
		@endforeach
	</div>
	<br/>
	<br/>
	{{ $tags->links() }}
@endsection

@section('footer')

@endsection
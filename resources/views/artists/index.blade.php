@extends('layouts.app')

@section('title')
	Artists - Page {{$artists->currentPage()}}
@endsection

@section('head')

@endsection

@section('content')
	<div class="container">
		
		<div>
			<div>
				<b>Sort By:</b>
				@if($list_type == "usage")
					<b><a href="/artist?type=usage&order={{$list_order}}">Artist Usage</a></b> <a href="/artist?type=alphabetic&order={{$list_order}}">Alphabetic</a>
				@elseif ($list_type == "alphabetic")
					<a href="/artist?type=usage&order={{$list_order}}">Artist Usage</a> <b><a href="/artist?type=alphabetic&order={{$list_order}}">Alphabetic</a></b>
				@endif
			</div>
			
			<div>
				<b>Display Order:</b>
				@if($list_order == "asc")
					<b><a href="/artist?type={{$list_type}}&order=asc">Ascending</a></b> <a href="/artist?type={{$list_type}}&order=desc">Descending</a>
				@elseif($list_order == "desc")
					<a href="/artist?type={{$list_type}}&order=asc">Ascending</a> <b><a href="/artist?type={{$list_type}}&order=desc">Descending</a></b>
				@endif
			</div>
		</div>
		
		@foreach($artists as $artist)
			@if((($loop->iteration - 1) % 3) == 0)
				<div class="row">
			@endif
			
			<div class="col-xs-4">
				<span class="primary_artists"><a href="/artist/{{$artist->id}}">{{{$artist->name}}} <span class="artist_count">({{$artist->usage_count()}})</span></a></span>
			</div>
			
			@if((($loop->iteration - 1) % 3) == 2)			
				</div>
			@endif
		@endforeach
	</div>
	<br/>
	<br/>
	{{ $artists->links() }}
@endsection

@section('footer')

@endsection
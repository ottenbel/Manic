@extends('layouts.app')

@section('title')
	Series - Page {{$series->currentPage()}}
@endsection

@section('head')

@endsection

@section('content')
	<div class="container">
		
		<div>
			<div>
				<b>Sort By:</b>
				@if($list_type == "usage")
					<b><a href="/series?type=usage&order={{$list_order}}">Series Usage</a></b> <a href="/series?type=alphabetic&order={{$list_order}}">Alphabetic</a>
				@elseif ($list_type == "alphabetic")
					<a href="/series?type=usage&order={{$list_order}}">Series Usage</a> <b><a href="/series?type=alphabetic&order={{$list_order}}">Alphabetic</a></b>
				@endif
			</div>
			
			<div>
				<b>Display Order:</b>
				@if($list_order == "asc")
					<b><a href="/series?type={{$list_type}}&order=asc">Ascending</a></b> <a href="/series?type={{$list_type}}&order=desc">Descending</a>
				@elseif($list_order == "desc")
					<a href="/series?type={{$list_type}}&order=asc">Ascending</a> <b><a href="/series?type={{$list_type}}&order=desc">Descending</a></b>
				@endif
			</div>
		</div>
		
		@foreach($series as $ser)
			@if((($loop->iteration - 1) % 3) == 0)
				<div class="row">
			@endif
			
			<div class="col-xs-4">
				<span class="primary_series"><a href="/series/{{$ser->id}}">{{{$ser->name}}} <span class="series_count">({{$ser->usage_count()}})</span></a></span>
			</div>
			
			@if((($loop->iteration - 1) % 3) == 2)			
				</div>
			@endif
		@endforeach
	</div>
	<br/>
	<br/>
	{{ $series->links() }}
@endsection

@section('footer')

@endsection
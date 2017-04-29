@extends('layouts.app')

@section('title')
	Series - Page {{$series->currentPage()}}
@endsection

@section('head')

@endsection

@section('content')
	<div class="container">
		@if($series->count() != 0)
			<div>
				<div>
					<b>Sort By:</b>
					@if($list_type == "usage")
						<b><a href="{{route('index_series')}}?type=usage&order={{$list_order}}">Series Usage</a></b> <a href="{{route('index_series')}}?type=alphabetic&order={{$list_order}}">Alphabetic</a>
					@elseif ($list_type == "alphabetic")
						<a href="{{route('index_series')}}?type=usage&order={{$list_order}}">Series Usage</a> <b><a href="{{route('index_series')}}?type=alphabetic&order={{$list_order}}">Alphabetic</a></b>
					@endif
				</div>
				
				<div>
					<b>Display Order:</b>
					@if($list_order == "asc")
						<b><a href="{{route('index_series')}}?type={{$list_type}}&order=asc">Ascending</a></b> <a href="{{route('index_series')}}?type={{$list_type}}&order=desc">Descending</a>
					@elseif($list_order == "desc")
						<a href="{{route('index_series')}}?type={{$list_type}}&order=asc">Ascending</a> <b><a href="{{route('index_series')}}?type={{$list_type}}&order=desc">Descending</a></b>
					@endif
				</div>
			</div>
			
			@foreach($series as $ser)
				@if((($loop->iteration - 1) % 3) == 0)
					<div class="row">
				@endif
				
				<div class="col-xs-4">
					<span class="primary_series"><a href="{{route('show_series', ['series' => $ser] )}}">{{{$ser->name}}} <span class="series_count">({{$ser->usage_count()}})</span></a></span>
				</div>
				
				@if((($loop->iteration - 1) % 3) == 2)			
					</div>
				@endif
			@endforeach
		<br/>
		<br/>
		{{ $series->links() }}
	@else
		@can('create', App\Models\TagObjects\Series\Series::class)
			<div class="text-center">
				No series have been found in the database. Add a new series <a href = "{{route('create_series')}}">here.</a>
			</div>
		@endcan
		
		@cannot('create', App\Models\TagObjects\Series\Series::class)
			<div class="text-center">
				No series have been found in the database.
			</div>
		@endcan
	@endif
	</div>
@endsection

@section('footer')

@endsection
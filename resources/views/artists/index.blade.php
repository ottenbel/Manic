@extends('layouts.app')

@section('title')
	Artists - Page {{$artists->currentPage()}}
@endsection

@section('head')

@endsection

@section('content')
	<div class="container">	
		@if($artists->count() != 0)
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
			<br/>
			<br/>
			{{ $artists->links() }}
		@else
			@can('create', App\Models\TagObjects\Artist\Artist::class)
				<div class="text-center">
					No artists have been found in the database. Add a new artist <a href = "{{url('/artist/create')}}">here.</a>
				</div>
				<br/>
			@endcan
			
			@cannot('create', App\Models\TagObjects\Artist\Artist::class)
				<div class="text-center">
					No artists have been found in the database.
				</div>
				<br/>
			@endcan
		@endif
	</div>	
@endsection

@section('footer')

@endsection
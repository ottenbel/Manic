@extends('layouts.app')

@section('title')
	Ratings - Page {{$ratings->currentPage()}}
@endsection

@section('head')

@endsection

@section('content')
	<div class="container">
		<div>
			<b>Display Order:</b>
			@if($list_order == "asc")
				<b><a href="{{route('index_rating')}}?&order=asc">Ascending</a></b> <a href="{{route('index_rating')}}?order=desc">Descending</a>
			@elseif($list_order == "desc")
				<a href="{{route('index_rating')}}?order=asc">Ascending</a> <b><a href="{{route('index_rating')}}?order=desc">Descending</a></b>
			@endif
		</div>
		
		<div>
			@if($ratings->count() != 0)
				@foreach($ratings as $rating)
					@if((($loop->iteration - 1) % 3) == 0)
						<div class="row">
					@endif
					
					<div class="col-xs-4">
						@if($rating->description != null)
							<span class="rating_tag" title="{{$rating->description}}">
						@else
							<span class="rating_tag">
						@endif
							<a href="{{route('show_rating', ['rating' => $rating])}}">{{$rating->name}}</a>
						</span>
					</div>
					
					@if((($loop->iteration - 1) % 3) == 2)
						</div>
						<br/>
					@endif
				@endforeach
				<br/>
				<br/>
				{{ $ratings->links() }}			
			@else				
				<br/>
				<div class="text-center">
					<p>No ratings have been found in the database.</p>
					@can('create', App\Models\Rating::class)
						<p>Add a new Rating <a href="{{create_rating}}">here</a>.</p>
					@endcan
				</div>
			@endif
		</div>
	</div>
@endsection

@section('footer')

@endsection
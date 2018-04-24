@extends('layouts.app')

@section('title')
	Statuses - Page {{$statuses->currentPage()}}
@endsection

@section('head')

@endsection

@section('content')
	<div class="container">
		<div>
			<b>Display Order:</b>
			@if($list_order == "asc")
				<b><a href="{{route('index_status')}}?&order=asc">Ascending</a></b> <a href="{{route('index_status')}}?order=desc">Descending</a>
			@elseif($list_order == "desc")
				<a href="{{route('index_status')}}?order=asc">Ascending</a> <b><a href="{{route('index_status')}}?order=desc">Descending</a></b>
			@endif
		</div>
		
		<div>
			@if($statuses->count() != 0)
				@foreach($statuses as $status)
					@if((($loop->iteration - 1) % 3) == 0)
						<div class="row">
					@endif
					
					<div class="col-xs-4">
						@if($status->description != null)
							<span class="status_tag" title="{{$status->description}}">
						@else
							<span class="status_tag">
						@endif
							<a href="{{route('show_status', ['status' => $status])}}">{{$status->name}}</a>
						</span>
					</div>
					
					@if((($loop->iteration - 1) % 3) == 2)
						</div>
						<br/>
					@endif
				@endforeach
				<br/>
				<br/>
				{{ $statuses->links() }}			
			@else				
				<br/>
				<div class="text-center">
					<p>No statuses have been found in the database.</p>
					@can('create', App\Models\Status::class)
						<p>Add a new Status <a href="{{create_status}}">here</a>.</p>
					@endcan
				</div>
			@endif
		</div>
	</div>
@endsection

@section('footer')

@endsection
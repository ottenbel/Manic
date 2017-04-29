@extends('layouts.app')

@section('title')
	Scanalators - Page {{$scanalators->currentPage()}}
@endsection

@section('head')

@endsection

@section('content')
	<div class="container">
		@if($scanalators->count() != 0)
			<div>
				<div>
					<b>Sort By:</b>
					@if($list_type == "usage")
						<b><a href="{{route('index_scanalator')}}?type=usage&order={{$list_order}}">Scanalator Usage</a></b> <a href="{{route('index_scanalator')}}?type=alphabetic&order={{$list_order}}">Alphabetic</a>
					@elseif ($list_type == "alphabetic")
						<a href="{{route('index_scanalator')}}?type=usage&order={{$list_order}}">Scanalator Usage</a> <b><a href="{{route('index_scanalator')}}?type=alphabetic&order={{$list_order}}">Alphabetic</a></b>
					@endif
				</div>
				
				<div>
					<b>Display Order:</b>
					@if($list_order == "asc")
						<b><a href="{{route('index_scanalator')}}?type={{$list_type}}&order=asc">Ascending</a></b> <a href="{{route('index_scanalator')}}?type={{$list_type}}&order=desc">Descending</a>
					@elseif($list_order == "desc")
						<a href="{{route('index_scanalator')}}?type={{$list_type}}&order=asc">Ascending</a> <b><a href="{{route('index_scanalator')}}?type={{$list_type}}&order=desc">Descending</a></b>
					@endif
				</div>
			</div>
			
			@foreach($scanalators as $scanalator)
				@if((($loop->iteration - 1) % 3) == 0)
					<div class="row">
				@endif
				
				<div class="col-xs-4">
					<span class="primary_scanalators"><a href="{{route('show_scanalator', ['scanalator' => $scanalator])}} ">{{{$scanalator->name}}} <span class="scanalator_count">({{$scanalator->usage_count()}})</span></a></span>
				</div>
				
				@if((($loop->iteration - 1) % 3) == 2)			
					</div>
				@endif
			@endforeach
		<br/>
		<br/>
		{{ $scanalators->links() }}
	@else
		@can('create', App\Models\TagObjects\Scanalator\Scanalator::class)
			<div class="text-center">
				No scanalators have been found in the database. Add a new scanalator <a href = "{{route('create_scanalator')}}">here.</a>
			</div>
		@endcan
		@cannot('create', App\Models\TagObjects\Scanalator\Scanalator::class)
			<div class="text-center">
				No scanalators have been found in the database.
			</div>
		@endcan
	@endif
	</div>
@endsection

@section('footer')

@endsection
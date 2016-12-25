@extends('layouts.app')

@section('title')
Page {{$results->currentPage()}}
@endsection

@section('header')

@endsection

@section('content')

@if($collections->count() != 0)
	<div>No collections have been found in the database.  Add a new collection <a href = "/collection/create">here</a>.</div>
@elseif
	<table class="table table-striped">
	@foreach($collections as $collection)
		<tr>
		<td>
		Image
		</td>
		<td>
		<div><a href="/collection/{{$collection->id}}">{{$collection->name}}</a></div>
		</div>Summary/Tags?<div>
		</td>
		</tr>
	@endforeach
	</table>
@endif

{{ $collections->links() }}

@endsection


@section('footer')

@endsection
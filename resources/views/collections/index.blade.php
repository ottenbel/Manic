@extends('layouts.app')

@section('title')
Index - Page {{$collections->currentPage()}}
@endsection

@section('header')

@endsection

@section('content')
<div class="container">
	<div class="row">
		@if($collections->count() == 0)
			<div>
				No collections have been found in the database.  Add a new collection <a href = "{{url('/collection/create')}}">here.</a>
			</div>
		@else
			<table class="table table-striped">
				@foreach($collections as $collection)
					<tr>
						<td>
						@if($collection->cover != null)
							Image
						@endif
						</td>
						<td>
							<div><a href="/collection/{{$collection->id}}">{{$collection->name}}</a></div>
							<div>Summary/Tags?</div>
						</td>
					</tr>
				@endforeach
			</table>
		@endif
	</div>
	{{ $collections->links() }}
</div>
@endsection

@section('footer')

@endsection
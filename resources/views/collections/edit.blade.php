@extends('layouts.app')

@section('title')
Edit Collection - {{{$collection->name}}}
@endsection

@section('header')

@endsection

@section('content')
<div class="container">
	<h1>Edit Collection</h1>
	
	<form method="POST" action="/collection/{{$collection->id}}" enctype="multipart/form-data">
		{{ csrf_field() }}
		{{method_field('PATCH')}}
		
		@include('partials.collection-input', array('collection' => $collection, 'ratings' => $ratings, 'statuses' => $statuses, 'languages' => $languages))
		
		{{ Form::submit('Update Collection', array('class' => 'btn btn-primary')) }}
	</form>
	
	@foreach($collection->volumes()->orderBy('number', 'asc')->get() as $volume)
	
	<div id = "volumes">
		<div id="volume">
			@if($volume->name != null && $volume->name != "")
					<a href="/volume/edit/{{$volume->id}}">Volume {{$volume->number}} - {{{$volume->name}}}</a>
				@else
					<a href="/volume/edit/{{$volume->id}}">Volume {{$volume->number}}</a>
				@endif 
		</div>
	</div>
	@endforeach
	
</div>
@endsection

@section('footer')

@endsection
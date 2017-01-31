@extends('layouts.app')

@section('title')
Edit Volume - {{{$volume->name}}}
@endsection

@section('header')

@endsection

@section('content')
<div class="container">
	<h1>Edit Volume</h1>
	
	<form method="POST" action="/volume/{{$volume->id}}" enctype="multipart/form-data">
		{{ csrf_field() }}
		{{method_field('PATCH')}}
		
		{{ Form::hidden('collection_id', $volume->collection_id) }}
		
		@include('partials.volume-input', array('volume' => $volume))
		
		<div id = "chapters">
			@foreach($volume->chapters()->orderBy('number', 'asc')->get() as $chapter)
				<div id="chapter">
					@if($chapter->name != null && $chapter->name != "")
						<a href="/chapter/{{$chapter->id}}/edit">Chapter {{$chapter->number}} - {{$chapter->name}}</a>
					@else
						<a href="/chapter/{{$chapter->id}}/edit">Chapter {{$chapter->number}}</a>
					@endif
				</div>
			@endforeach
		</div>
		
		{{ Form::submit('Update Volume', array('class' => 'btn btn-primary')) }}
	</form>
	
</div>
@endsection

@section('footer')

@endsection
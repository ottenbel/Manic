@extends('layouts.app')

@section('title')
Edit Artist - {{{$tagObject->name}}}
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	<h1>Edit Artist</h1>
	
	<form method="POST" action="/artist/{{$tagObject->id}}" enctype="multipart/form-data">
		{{ csrf_field() }}
		{{method_field('PATCH')}}
		
		{{ Form::hidden('artist_id', $tagObject->id) }}
		
		@include('partials.tag-object-input')
		
		{{ Form::submit('Update Artist', array('class' => 'btn btn-primary')) }}
	</form>
	
</div>
@endsection

@section('footer')

@endsection
@extends('layouts.app')

@section('title')
Create a New Artist
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	<h1>Create a New Artist</h1>
	
	<form method="POST" action="/artist" enctype="multipart/form-data">
		{{ csrf_field() }}
		
		@include('partials.tag-object-input')
		
		{{ Form::submit('Create Artist', array('class' => 'btn btn-primary')) }}
	</form>
</div>
@endsection

@section('footer')

@endsection
@extends('layouts.app')

@section('title')
Create a New Chapter 
@endsection

@section('header')

@endsection

@section('content')
<div class="container">
	<h1>Create a New Chapter</h1>
	<h2>On <a href="/collection/{{$collection->id}}">{{{$collection->name}}}</a></h2>
	
	<form method="POST" action="/chapter" enctype="multipart/form-data">
		{{ csrf_field() }}
		
		@include('partials.chapter-input')
		
		{{ Form::submit('Create Chapter', array('class' => 'btn btn-primary')) }}
	</form>
</div>
@endsection

@section('footer')

@endsection
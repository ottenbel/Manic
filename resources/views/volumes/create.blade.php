@extends('layouts.app')

@section('title')
Create a New Volume 
@endsection

@section('header')

@endsection

@section('content')
<div class="container">
	<h1>Create a New Volume</h1>
	
	<form method="POST" action="/volume" enctype="multipart/form-data">
		{{ csrf_field() }}
		
		@include('partials.volume-input')
		
		{{ Form::submit('Create Volume', array('class' => 'btn btn-primary')) }}
	</form>
</div>
@endsection

@section('footer')

@endsection
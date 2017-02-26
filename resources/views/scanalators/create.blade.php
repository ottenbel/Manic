@extends('layouts.app')

@section('title')
Create a New Scanalator
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	<h1>Create a New Scanalator</h1>
	
	<form method="POST" action="/scanalator" enctype="multipart/form-data">
		{{ csrf_field() }}
		
		@include('partials.tag-object-input')
		
		{{ Form::submit('Create Scanalator', array('class' => 'btn btn-primary')) }}
	</form>
</div>
@endsection

@section('footer')

@endsection
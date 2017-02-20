@extends('layouts.app')

@section('title')
Create a New Tag
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	<h1>Create a New Tag</h1>
	
	<form method="POST" action="/tag" enctype="multipart/form-data">
		{{ csrf_field() }}
		
		@include('partials.tag-object-input')
		
		{{ Form::submit('Create Tag', array('class' => 'btn btn-primary')) }}
	</form>
</div>
@endsection

@section('footer')

@endsection
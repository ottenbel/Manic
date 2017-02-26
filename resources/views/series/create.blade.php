@extends('layouts.app')

@section('title')
Create a New Series
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	<h1>Create a New Series</h1>
	
	<form method="POST" action="/series" enctype="multipart/form-data">
		{{ csrf_field() }}
		
		@include('partials.tag-object-input')
		
		{{ Form::submit('Create Series', array('class' => 'btn btn-primary')) }}
	</form>
</div>
@endsection

@section('footer')

@endsection
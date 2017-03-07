@extends('layouts.app')

@section('title')
Create a New Collection
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	<h1>Create a New Collection</h1>
	
	<form method="POST" action="/collection" enctype="multipart/form-data">
		{{ csrf_field() }}
		
		<div class="form-group">
			{{ Form::label('series', 'Series') }}
			@if((!empty($tagObject)) && ($tagObject->series != null) && (Input::old('series') == null))
				{{ Form::text('series', $tagObject->series->name, array('class' => 'form-control')) }}
			@else
				{{ Form::text('series', Input::old('series'), array('class' => 'form-control')) }}
			@endif
			@if($errors->has('series'))
				<div class ="alert alert-danger" id="name_errors">{{$errors->first('series')}}</div>
			@endif
		</div>
		
		@include('partials.tag-object-input')
		
		{{ Form::submit('Create Collection', array('class' => 'btn btn-primary')) }}
	</form>
</div>
@endsection

@section('footer')

@endsection
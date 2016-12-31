@extends('layouts.app')

@section('title')
Create a New Collection
@endsection

@section('header')

@endsection

@section('content')
<div class="container">
	<h1>Create a New Collection</h1>
	
	#{{ Form::open(array('url' => 'collection', 'files' => true)) }}
	<form method="POST" action="{{ url('/collection') }}" enctype="multipart/form-data">
		{{ csrf_field() }}
		<div class="form-group">
			{{ Form::label('cover', 'Cover Image') }}
			{{ Form::file('image') }}
			@if ($errors->has('image'))
				<div class ="alert alert-danger" id="image_errors">{{$errors->first('image')}}</div>
			@endif
		</div>
		
		<div class="form-group">
			{{ Form::label('name', 'Name') }}
			{{ Form::text('name', Input::old('name'), array('class' => 'form-control')) }}
			@if($errors->has('name'))
				<div class ="alert alert-danger" id="name_errors">{{$errors->first('name')}}</div>
			@endif
		</div>
		
		<div class="form-group">
			{{ Form::label('description', 'Description') }}
			{{ Form::textarea('description', Input::old('description'), array('class' => 'form-control')) }}
		<div>
		
		<div class="form-group">
			{{ Form::label('parent_id', 'Parent Collection') }}
			{{ Form::text('parent_id', Input::old('parent_id'), array('class' => 'form-control')) }}
			@if($errors->has('parent_id'))
				<div class ="alert alert-danger" id="parent_id_errors">{{$errors->first('parent_id')}}</div>
			@endif
		</div>
		
		<div class="form-group">
			{{ Form::label('language', 'Language') }}
			{{ Form::select('language', $languages) }}
		</div>
		
		<div class="form-group">
			{{ Form::label('artist_primary', 'Primary Artists') }}
			{{ Form::text('artist_primary', Input::old('artist_primary'), array('class' => 'form-control')) }}
			
			{{ Form::label('artist_secondary', 'Secondary Artists') }}
			{{ Form::text('artist_secondary', Input::old('artist_secondary'), array('class' => 'form-control')) }}
		</div>
		
		<div class="form-group">
			{{ Form::label('series_primary', 'Series Primary') }}
			{{ Form::text('series_primary', Input::old('series_primary'), array('class' => 'form-control')) }}
			
			{{ Form::label('series_secondary', 'Series Secondary') }}
			{{ Form::text('series_secondary', Input::old('series_secondary'), array('class' => 'form-control')) }}
		</div>
		
		<div class="form-group">
			{{ Form::label('tag_primary', 'Tags Primary') }}
			{{ Form::text('tag_primary', Input::old('tag_primary'), array('class' => 'form-control')) }}
			
			{{ Form::label('tag_secondary', 'Tags Secondary') }}
			{{ Form::text('tag_secondary', Input::old('tag_secondary'), array('class' => 'form-control')) }}

		</div>
		
		<div class="form-group">
			{{ Form::label('canonical', 'Canonical') }}
			{{ Form::checkbox('canonical', Input::old('canonical'), array('class' => 'form-control')) }}
		<div>
		
		<div class="form-group">
			{{ Form::label('rating', 'Rating: ') }}
			@foreach($ratings as $rating)
				{{ Form::radio('ratings', $rating->id, false, array('id'=>"ratings-$rating->priority")) }}
				{{ Form::label("ratings-$rating->priority", $rating->name) }}
			@endforeach
		<div>
		
		<div class="form-group">
			{{ Form::label('statuses', 'Status: ') }}
			@foreach($statuses as $status)
				{{ Form::radio('statuses', $status->id, false, array('id'=>"statuses-$status->priority")) }}
				{{ Form::label("statuses-$status->priority", $status->name) }}
			@endforeach
		<div>
		
		{{ Form::submit('Create Collection', array('class' => 'btn btn-primary')) }}
		
	#{{ Form::close() }}
	</form>
	
</div>
@endsection

@section('footer')

@endsection
@extends('layouts.app')

@section('title')
Create a New Character
@endsection

@section('head')
<script src="/js/autocomplete/series.js"></script>
@endsection

@section('content')
<div class="container">
	<h1>Create a New Character</h1>
	
	<form method="POST" action="/character" enctype="multipart/form-data">
		{{ csrf_field() }}
		
		<div class="form-group">
			{{ Form::label('parent_series', 'Series') }}
			@if(($series != null) && (Input::old('parent_series') == null))
				{{ Form::text('parent_series', $series->name, array('class' => 'form-control', 'placeholder' => Config::get('constants.placeholders.tagObjects.parentSeries'))) }}
			@else
				{{ Form::text('parent_series', Input::old('parent_series'), array('class' => 'form-control', 'placeholder' => Config::get('constants.placeholders.tagObjects.parentSeries'))) }}
			@endif
			@if($errors->has('parent_series'))
				<div class ="alert alert-danger" id="name_errors">{{$errors->first('parent_series')}}</div>
			@endif
		</div>
		
		@include('partials.tag-object-input')
		
		{{ Form::submit('Create Character', array('class' => 'btn btn-primary')) }}
	</form>
</div>
@endsection

@section('footer')

@endsection
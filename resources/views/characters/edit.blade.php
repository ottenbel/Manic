@extends('layouts.app')

@section('title')
Edit Character - {{{$tagObject->name}}}
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	@can('update', $tagObject)
		<h1>Edit Character</h1>
		<h2>Associated With <a href="{{route('show_series', ['series' => $tagObject->series()->first()])}}">{{$tagObject->series->name}}</a></h2>
		
		<form method="POST" action="{{route('update_character', ['series' => $tagObject])}}" enctype="multipart/form-data">
			{{ csrf_field() }}
			{{method_field('PATCH')}}
			
			@include('partials.tag-object-input')
			
			{{ Form::submit('Update Character', array('class' => 'btn btn-primary')) }}
		</form>
	@endcan
	
	@cannot('update', $tagObject)
		<h1>Error</h1>
		<div class="alert alert-danger" role="alert">
			User does not have the correct permissions in order to edit character.
		</div>
	@endcan
</div>
@endsection

@section('footer')

@endsection
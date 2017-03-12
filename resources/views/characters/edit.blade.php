@extends('layouts.app')

@section('title')
Edit Character - {{{$tagObject->name}}}
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	<h1>Edit Character</h1>
	<h2>Associated With <a href="/series/{{$tagObject->series->id}}">{{$tagObject->series->name}}</a></h2>
	
	<form method="POST" action="/character/{{$tagObject->id}}" enctype="multipart/form-data">
		{{ csrf_field() }}
		{{method_field('PATCH')}}
		
		{{ Form::hidden('character_id', $tagObject->id) }}
		
		@include('partials.tag-object-input')
		
		{{ Form::submit('Update Character', array('class' => 'btn btn-primary')) }}
	</form>
	
</div>
@endsection

@section('footer')

@endsection
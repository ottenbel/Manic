@extends('layouts.app')

@section('title')
Edit Scanalator - {{{$tagObject->name}}}
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	<h1>Edit Scanalator</h1>
	
	<form method="POST" action="/scanalator/{{$tagObject->id}}" enctype="multipart/form-data">
		{{ csrf_field() }}
		{{method_field('PATCH')}}
				
		@include('partials.tag-object-input')
		
		{{ Form::submit('Update Scanalator', array('class' => 'btn btn-primary')) }}
	</form>
</div>
@endsection

@section('footer')

@endsection
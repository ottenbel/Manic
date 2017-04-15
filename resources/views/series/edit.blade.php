@extends('layouts.app')

@section('title')
	Edit Series - {{{$tagObject->name}}}
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	<h1>Edit Series</h1>
	
	<form method="POST" action="/series/{{$tagObject->id}}" enctype="multipart/form-data">
		{{ csrf_field() }}
		{{method_field('PATCH')}}
				
		@include('partials.tag-object-input')
		
		{{ Form::submit('Update Series', array('class' => 'btn btn-primary')) }}
	</form>
</div>
@endsection

@section('footer')

@endsection
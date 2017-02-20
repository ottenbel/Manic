@extends('layouts.app')

@section('title')
Edit Tag - {{{$tagObject->name}}}
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	<h1>Edit Tag</h1>
	
	<form method="POST" action="/tag/{{$tagObject->id}}" enctype="multipart/form-data">
		{{ csrf_field() }}
		{{method_field('PATCH')}}
		
		{{ Form::hidden('tag_id', $tagObject->id) }}
		
		@include('partials.tag-object-input')
		
		{{ Form::submit('Update Tag', array('class' => 'btn btn-primary')) }}
	</form>
	
</div>
@endsection

@section('footer')

@endsection
@extends('layouts.app')

@section('title')
Edit Collection - {{{$collection->name}}}
@endsection

@section('header')

@endsection

@section('content')
<div class="container">
	<h1>Create a New Collection</h1>
	
	<form method="POST" action="/collection/{{$collection->id}}" enctype="multipart/form-data">
		{{ csrf_field() }}
		{{method_field('PATCH')}}
		
		@include('partials.collection-input', array('collection' => $collection, 'ratings' => $ratings, 'statuses' => $statuses, 'languages' => $languages))
		
		{{ Form::submit('Create Collection', array('class' => 'btn btn-primary')) }}
	</form>
</div>
@endsection

@section('footer')

@endsection
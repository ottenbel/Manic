@extends('layouts.app')

@section('title')
Create a New Collection
@endsection

@section('head')
<script src="/js/autocomplete/artist.js"></script>
<script src="/js/autocomplete/collection.js"></script>
<script src="/js/autocomplete/series.js"></script>
<script src="/js/autocomplete/tag.js"></script>
@endsection

@section('content')
<div class="container">
	<h1>Create a New Collection</h1>
	
	<form method="POST" action="/collection" enctype="multipart/form-data">
		{{ csrf_field() }}
		
		@include('partials.collection-input', array('ratings' => $ratings, 'statuses' => $statuses, 'languages' => $languages))
		
		{{ Form::submit('Create Collection', array('class' => 'btn btn-primary')) }}
	</form>
</div>
@endsection

@section('footer')

@endsection
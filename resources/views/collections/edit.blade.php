@extends('layouts.app')

@section('title')
Edit Collection - {{{$collection->name}}}
@endsection

@section('head')
<script src="/js/autocomplete/artist.js"></script>
<script src="/js/autocomplete/character.js"></script>
<script src="/js/autocomplete/collection.js"></script>
<script src="/js/autocomplete/series.js"></script>
<script src="/js/autocomplete/tag.js"></script>
@endsection

@section('content')
<div class="container">
	<h1>Edit Collection</h1>
	
	<form method="POST" action="/collection/{{$collection->id}}" enctype="multipart/form-data">
		{{ csrf_field() }}
		{{method_field('PATCH')}}
		
		{{ Form::hidden('collection_id', $collection->id) }}
		
		@include('partials.collection-input', array('collection' => $collection, 'ratings' => $ratings, 'statuses' => $statuses, 'languages' => $languages))
		<div id = "volumes">
			@foreach($collection->volumes()->orderBy('volume_number', 'asc')->get() as $volume)
				<div id="volume">
					@if($volume->name != null && $volume->name != "")
						<a href="/volume/{{$volume->id}}/edit">Volume {{$volume->volume_number}} - {{{$volume->name}}}</a>
					@else
						<a href="/volume/{{$volume->id}}/edit">Volume {{$volume->volume_number}}</a>
					@endif 
				</div>
			@endforeach
		</div>
		<br/>
		{{ Form::submit('Update Collection', array('class' => 'btn btn-primary')) }}
	</form>
	
</div>
@endsection

@section('footer')

@endsection
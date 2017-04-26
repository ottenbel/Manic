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
	@can('update', $collection)
		<h1>Edit Collection</h1>
		
		<form method="POST" action="{{route('update_collection', ['collection' => $collection])}}" enctype="multipart/form-data">
			{{ csrf_field() }}
			{{method_field('PATCH')}}
			
			{{ Form::hidden('collection_id', $collection->id) }}
			
			@include('partials.collection-input', array('collection' => $collection, 'ratings' => $ratings, 'statuses' => $statuses, 'languages' => $languages))
			<div id = "volumes">
				@foreach($collection->volumes()->orderBy('volume_number', 'asc')->get() as $volume)
					@can('update', $volume)
						<div id="volume">
							@if($volume->name != null && $volume->name != "")
								<a href="{{route('edit_volume', ['volume' => $volume])}}">Volume {{$volume->volume_number}} - {{{$volume->name}}}</a>
							@else
								<a href="{{route('edit_volume', ['volume' => $volume])}}">Volume {{$volume->volume_number}}</a>
							@endif 
						</div>
					@endcan
				@endforeach
			</div>
			<br/>
			{{ Form::submit('Update Collection', array('class' => 'btn btn-primary')) }}
		</form>
	@endcan
	
	@cannot('update', $collection)
		<h1>Error</h1>
		<div class="alert alert-danger" role="alert">
			User does not have the correct permissions in order to edit collection.
		</div>
	@endcan
</div>
@endsection

@section('footer')

@endsection
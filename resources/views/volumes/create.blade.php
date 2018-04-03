@extends('layouts.app')

@section('title')
Create a New Volume 
@endsection

@section('head')
	<script src="/js/confirmdelete.js"></script>
@endsection

@section('content')
<div class="container">
	@can('create', App\Models\Volume::class)
		<h1>Create a New Volume</h1>
		<h2>On <a href="{{route('show_collection', ['collection' => $collection])}}">{{{$collection->name}}}</a></h2>
		
		<form method="POST" action="{{route('store_volume')}}" enctype="multipart/form-data">
			{{ csrf_field() }}
			
			{{ Form::hidden('collection_id', $collection->id) }}
			
			@include('partials.volume.input', array('configurations' => $configurations))
			
			@include('partials.collection.show', ['volumes' => $collection->volumes, 'editVolume' => false, 'editChapter' => false, 'scanalatorLinkRoute' => 'show_scanalator', 'hideVolumes' => false, 'isChapter' => false])
			<br/>
			
			{{ Form::submit('Create Volume', array('class' => 'btn btn-primary')) }}
		</form>
	@endcan
	
	@cannot('create', App\Models\Volume::class)
		<h1>Error</h1>
		<div class="alert alert-danger" role="alert">
			User does not have the correct permissions in order to create a new volume.
		</div>
	@endcan
</div>
@endsection

@section('footer')

@endsection
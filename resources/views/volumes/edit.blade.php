@extends('layouts.app')

@section('title')
Edit Volume - {{{$volume->name}}}
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	@can('update', $volume)
		<h1>Edit Volume</h1>
		
		<form method="POST" action="{{route('update_volume', ['volume' => $volume])}}" enctype="multipart/form-data">
			{{ csrf_field() }}
			{{method_field('PATCH')}}
			
			{{ Form::hidden('collection_id', $volume->collection_id) }}
			{{ Form::hidden('volume_id', $volume->id) }}
			
			@include('partials.volume-input', array('volume' => $volume))
			
			@include('partials.show-volume-chapters', ['volume' => $volume, 'editVolume' => false, 'editVolumeRoute' => 'edit_volume', 'chapterLinkRoute' => 'edit_chapter', 'scanalatorLinkRoute' => 'edit_scanalator', 'chapterOnly' => true, 'hideVolumes' => false])
			
			<br/>
			
			{{ Form::submit('Update Volume', array('class' => 'btn btn-primary')) }}
		</form>
	@endcan
	
	@cannot('update', $volume)
		<h1>Error</h1>
		<div class="alert alert-danger" role="alert">
			User does not have the correct permissions in order to edit volume.
		</div>
	@endcan
</div>
@endsection

@section('footer')

@endsection
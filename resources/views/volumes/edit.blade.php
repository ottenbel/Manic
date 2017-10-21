@extends('layouts.app')

@section('title')
Edit Volume - {{{$volume->name}}}
@endsection

@section('head')
	<script src="/js/confirmdelete.js"></script>
@endsection

@section('content')
<div class="container">
	@can('update', $volume)
		<div class="row">
			<div class="col-xs-8"><h1>Edit Volume</h1></div>
			@can('delete', $volume)
				<div class="col-xs-4 text-right">
					<form method="POST" action="{{route('delete_volume', ['volume' => $volume])}}">
						{{ csrf_field() }}
						{{method_field('DELETE')}}
						
						{{ Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete Volume', array('type' => 'submit', 'class' => 'btn btn-danger', 'onclick' =>'ConfirmDelete(event)')) }}
					</form>
				</div>
			@endcan
		</div>
		
		<form method="POST" action="{{route('update_volume', ['volume' => $volume])}}" enctype="multipart/form-data">
			{{ csrf_field() }}
			{{method_field('PATCH')}}
			
			{{ Form::hidden('collection_id', $volume->collection_id) }}
			{{ Form::hidden('volume_id', $volume->id) }}
			
			@include('partials.volume.input', array('configurations' => $configurations, 'volume' => $volume))
			
			@include('partials.volume.show', ['volume' => $volume, 'editVolume' => false, 'editVolumeRoute' => 'edit_volume', 'chapterLinkRoute' => 'edit_chapter', 'scanalatorLinkRoute' => 'edit_scanalator', 'chapterOnly' => true, 'hideVolumes' => false])
			
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
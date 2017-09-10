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
	<script src="/js/confirmdelete.js"></script>
@endsection

@section('content')
<div class="container">
	@can('update', $collection)
		<div class="row">
			<div class="col-xs-8"><h1>Edit Collection</h1></div>
			@can('delete', $collection)
				<div class="col-xs-4 text-right">
					<form method="POST" action="{{route('delete_collection', ['collection' => $collection])}}">
						{{ csrf_field() }}
						{{method_field('DELETE')}}
						
						{{ Form::submit('Delete Collection', array('class' => 'btn btn-danger', 'onclick' =>'ConfirmDelete(event)')) }}
					</form>
				</div>
			@endcan
		</div>
		
		<form method="POST" action="{{route('update_collection', ['collection' => $collection])}}" enctype="multipart/form-data">
			{{ csrf_field() }}
			{{method_field('PATCH')}}
			
			{{ Form::hidden('collection_id', $collection->id) }}
			
			@include('partials.collection-input', array('configurations' => $configurations, 'collection' => $collection, 'ratings' => $ratings, 'statuses' => $statuses, 'languages' => $languages))
			
			@include('partials.show-collection-content', ['volumes' => $collection->volumes(), 'editVolume' => true, 'editVolumeRoute' => 'edit_volume', 'chapterLinkRoute' => 'edit_chapter', 'scanalatorLinkRoute' => 'edit_scanalator', 'hideVolumes' => false])
			
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
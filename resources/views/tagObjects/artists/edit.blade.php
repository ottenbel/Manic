@extends('layouts.app')

@section('title')
Edit Artist - {{{$artist->name}}}
@endsection

@section('head')
	<script src="/js/autocomplete/artist.js"></script>
	<script src="/js/confirmdelete.js"></script>
@endsection

@section('content')
<div class="container">
	@can('update', $artist)
	<div class="row">
		<div class="col-xs-8"><h1>Edit Artist</h1></div>
		@can('delete', $artist)
			<div class="col-xs-4 text-right">
				<form method="POST" action="{{route('delete_artist', ['artist' => $artist])}}">
					{{ csrf_field() }}
					{{method_field('DELETE')}}
					
					{{ Form::submit('Delete Artist', array('class' => 'btn btn-danger', 'onclick' =>'ConfirmDelete(event)')) }}
				</form>
			</div>
		@endcan
	</div>
	
	<form method="POST" action="{{route('update_artist', ['artist' => $artist])}}" enctype="multipart/form-data">
		{{ csrf_field() }}
		{{method_field('PATCH')}}
		
		@include('partials.tagObjects.tag-object-input', 
		[
			'tagObject' => $artist,
			'child' => 'artist_child',
			'namePlaceholder' => 'constants.placeholders.tagObjects.artist.name', 
			'shortDescriptionPlaceholder' => 'constants.placeholders.tagObjects.artist.shortDescription',
			'descriptionPlaceholder' => 'constants.placeholders.tagObjects.artist.description', 
			'sourcePlaceholder' => 'constants.placeholders.tagObjects.artist.source',
			'childPlaceholder' => 'constants.placeholders.tagObjects.artist.child',
			'nameHelpPlaceholder' => 'constants.placeholders.tagObjects.artist.nameHelp',
			'shortDescriptionHelpPlaceholder' => 'constants.placeholders.tagObjects.artist.shortDescriptionHelp',
			'descriptionHelpPlaceholder' => 'constants.placeholders.tagObjects.artist.descriptionHelp',
			'sourceHelpPlaceholder' => 'constants.placeholders.tagObjects.artist.sourceHelp',
			'childHelpPlaceholder' => 'constants.placeholders.tagObjects.artist.childHelp'
		])
		
		{{ Form::submit('Update Artist', array('class' => 'btn btn-primary')) }}
	</form>
	@endcan
	
	@cannot('update', $artist)
		<h1>Error</h1>
		<div class="alert alert-danger" role="alert">
			User does not have the correct permissions in order to edit artist.
		</div>
	@endcan
</div>
@endsection

@section('footer')

@endsection
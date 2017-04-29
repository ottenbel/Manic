@extends('layouts.app')

@section('title')
Edit Artist - {{{$artist->name}}}
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	@can('update', $artist)
	<h1>Edit Artist</h1>
	
	<form method="POST" action="{{route('update_artist', ['artist' => $artist])}}" enctype="multipart/form-data">
		{{ csrf_field() }}
		{{method_field('PATCH')}}
		
		@include('partials.tag-object-input', ['tagObject' => $artist, 'namePlaceholder' => 'constants.placeholders.tagObjects.artist.name', 'descriptionPlaceholder' => 'constants.placeholders.tagObjects.artist.description', 'sourcePlaceholder' => 'constants.placeholders.tagObjects.artist.source'])
		
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
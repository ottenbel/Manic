@extends('layouts.app')

@section('title')
Edit Artist - {{{$tagObject->name}}}
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	@can('update', $tagObject)
	<h1>Edit Artist</h1>
	
	<form method="POST" action="/artist/{{$tagObject->id}}" enctype="multipart/form-data">
		{{ csrf_field() }}
		{{method_field('PATCH')}}
		
		@include('partials.tag-object-input')
		
		{{ Form::submit('Update Artist', array('class' => 'btn btn-primary')) }}
	</form>
	@endcan
	
	@cannot('update', $tagObject)
		<h1>Error</h1>
		<div class="alert alert-danger" role="alert">
			User does not have the correct permissions in order to edit artist.
		</div>
	@endcan
</div>
@endsection

@section('footer')

@endsection
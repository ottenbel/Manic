@extends('layouts.app')

@section('title')
Edit Character - {{{$character->name}}}
@endsection

@section('head')
	<script src="/js/confirmdelete.js"></script>
@endsection

@section('content')
<div class="container">
	@can('update', $character)
		<div class="row">
			<div class="col-xs-8"><h1>Edit Character</h1></div>
			@can('delete', $character)
				<div class="col-xs-4 text-right">
					<form method="POST" action="{{route('delete_character', ['character' => $character])}}">
						{{ csrf_field() }}
						{{method_field('DELETE')}}
						
						{{ Form::submit('Delete Character', array('class' => 'btn btn-danger', 'onclick' =>'ConfirmDelete()')) }}
					</form>
				</div>
			@endcan
		</div>
		
		<h2>Associated With <a href="{{route('show_series', ['series' => $character->series()->first()])}}">{{$character->series->name}}</a></h2>
		
		<form method="POST" action="{{route('update_character', ['series' => $character])}}" enctype="multipart/form-data">
			{{ csrf_field() }}
			{{method_field('PATCH')}}
			
			@include('partials.tagObjects.tag-object-input', ['tagObject' => $character, 'namePlaceholder' => 'constants.placeholders.tagObjects.character.name', 'descriptionPlaceholder' => 'constants.placeholders.tagObjects.character.description', 'sourcePlaceholder' => 'constants.placeholders.tagObjects.character.source'])
			
			{{ Form::submit('Update Character', array('class' => 'btn btn-primary')) }}
		</form>
	@endcan
	
	@cannot('update', $character)
		<h1>Error</h1>
		<div class="alert alert-danger" role="alert">
			User does not have the correct permissions in order to edit character.
		</div>
	@endcan
</div>
@endsection

@section('footer')

@endsection
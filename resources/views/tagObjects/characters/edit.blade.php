@extends('layouts.app')

@section('title')
Edit Character - {{{$character->name}}}
@endsection

@section('head')
	<script src="/js/autocomplete/character.js"></script>
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
						
						{{ Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete Character', array('type' => 'submit', 'class' => 'btn btn-danger', 'onclick' =>'ConfirmDelete(event)')) }}
					</form>
				</div>
			@endcan
		</div>
		
		<h2>Associated With <a href="{{route('show_series', ['series' => $character->series()->first()])}}">{{$character->series->name}}</a></h2>
		
		<form method="POST" action="{{route('update_character', ['character' => $character])}}" enctype="multipart/form-data">
			{{ csrf_field() }}
			{{method_field('PATCH')}}
			
			<div class="form-group">
				{{ Form::label('parent_series', 'Series', array('style' => 'display:none')) }}
				{{ Form::text('parent_series', $character->series()->first()->name, array('class' => 'form-control', 'style' => 'display:none')) }}
			</div>

			
			@include('partials.tagObjects.tag-object-input', 
			[
				'tagObject' => $character, 
				'child' => 'character_child',
				'namePlaceholder' => $configurations['name'],
				'descriptionPlaceholder' => $configurations['description'], 
				'shortDescriptionPlaceholder' => $configurations['short_description'],
				'sourcePlaceholder' => $configurations['source'],
				'childPlaceholder' => $configurations['child']
			])
			
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
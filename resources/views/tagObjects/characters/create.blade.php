@extends('layouts.app')

@section('title')
Create a New Character
@endsection

@section('head')
	<script src="/js/autocomplete/character.js"></script>
	<script src="/js/autocomplete/series.js"></script>
@endsection

@section('content')
<div class="container">
	@can('create', App\Models\TagObjects\Character\Character::class)
	<h1>Create a New Character</h1>
	
	<form method="POST" action="{{route('store_character')}}" enctype="multipart/form-data">
		{{ csrf_field() }}
		
		<div class="form-group">
			{{ Form::label('parent_series', 'Series') }}
			<i class="fa fa-question-circle" aria-hidden="true" title="{{Config::get('constants.placeholders.tagObjects.character.parentSeriesHelp')}}"></i>
			@if(($series != null) && (Input::old('parent_series') == null))
				{{ Form::text('parent_series', $series->name, array('class' => 'form-control', 'placeholder' => Config::get('constants.placeholders.tagObjects.character.parentSeries'))) }}
			@else
				{{ Form::text('parent_series', Input::old('parent_series'), array('class' => 'form-control', 'placeholder' => Config::get('constants.placeholders.tagObjects.character.parentSeries'))) }}
			@endif
			@if($errors->has('parent_series'))
				<div class ="alert alert-danger" id="name_errors">{{$errors->first('parent_series')}}</div>
			@endif
		</div>
		
		@include('partials.tagObjects.tag-object-input', 
		[
			'child' => 'character_child',
			'namePlaceholder' => 'constants.placeholders.tagObjects.character.name', 
			'descriptionPlaceholder' => 'constants.placeholders.tagObjects.character.description', 
			'shortDescriptionPlaceholder' => 'constants.placeholders.tagObjects.character.shortDescription',
			'sourcePlaceholder' => 'constants.placeholders.tagObjects.character.source',
			'childPlaceholder' => 'constants.placeholders.tagObjects.character.child',
			'nameHelpPlaceholder' => 'constants.placeholders.tagObjects.character.nameHelp',
			'shortDescriptionHelpPlaceholder' => 'constants.placeholders.tagObjects.character.shortDescriptionHelp',
			'descriptionHelpPlaceholder' => 'constants.placeholders.tagObjects.character.descriptionHelp',
			'sourceHelpPlaceholder' => 'constants.placeholders.tagObjects.character.sourceHelp',
			'childHelpPlaceholder' => 'constants.placeholders.tagObjects.character.childHelp'
		])
		
		{{ Form::submit('Create Character', array('class' => 'btn btn-primary')) }}
	</form>
	@endcan
	
	@cannot('create', App\Models\TagObjects\Character\Character::class)
		<h1>Error</h1>
		<div class="alert alert-danger" role="alert">
			User does not have the correct permissions in order to create a new character.
		</div>
	@endcan
</div>
@endsection

@section('footer')

@endsection
@extends('layouts.app')

@section('title')
Create a New Tag
@endsection

@section('head')
	<script src="/js/autocomplete/tag.js"></script>
@endsection

@section('content')
<div class="container">
	@can('create', App\Models\TagObjects\Tag\Tag::class)
	<h1>Create a New Tag</h1>
	
	<form method="POST" action="{{route('store_tag')}}" enctype="multipart/form-data">
		{{ csrf_field() }}
		
		@include('partials.tagObjects.tag-object-input', 
		[
			'child' => 'tag_child',
			'namePlaceholder' => $configurations['name'], 
			'shortDescriptionPlaceholder' => $configurations['short_description'],
			'descriptionPlaceholder' => $configurations['description'], 
			'sourcePlaceholder' => $configurations['source'],
			'childPlaceholder' => $configurations['child']
		])
		
		{{ Form::submit('Create Tag', array('class' => 'btn btn-primary')) }}
	</form>
	@endcan
	
	@cannot('create', App\Models\TagObjects\Tag\Tag::class)
		<h1>Error</h1>
		<div class="alert alert-danger" role="alert">
			User does not have the correct permissions in order to create a new tag.
		</div>
	@endcan
</div>
@endsection

@section('footer')

@endsection
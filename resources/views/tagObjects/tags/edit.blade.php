@extends('layouts.app')

@section('title')
Edit Tag - {{{$tag->name}}}
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	@can('update', $tag)
		<h1>Edit Tag</h1>
		
		<form method="POST" action="{{route('update_tag', ['tag' => $tag])}}" enctype="multipart/form-data">
			{{ csrf_field() }}
			{{method_field('PATCH')}}
					
			@include('partials.tag-object-input', ['tagObject' => $tag, 'namePlaceholder' => 'constants.placeholders.tagObjects.tag.name', 'descriptionPlaceholder' => 'constants.placeholders.tagObjects.tag.description', 'sourcePlaceholder' => 'constants.placeholders.tagObjects.tag.source'])
			
			{{ Form::submit('Update Tag', array('class' => 'btn btn-primary')) }}
		</form>	
	@endcan
	
	@cannot('update', $tag)
		<h1>Error</h1>
		<div class="alert alert-danger" role="alert">
			User does not have the correct permissions in order to edit tag.
		</div>
	@endcan
</div>
@endsection

@section('footer')

@endsection
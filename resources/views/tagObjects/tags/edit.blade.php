@extends('layouts.app')

@section('title')
Edit Tag - {{{$tag->name}}}
@endsection
	
@section('head')
	<script src="/js/autocomplete/tag.js"></script>
	<script src="/js/confirmdelete.js"></script>
@endsection

@section('content')
<div class="container">
	@can('update', $tag)
		
		<div class="row">
			<div class="col-xs-8"><h1>Edit Tag</h1></div>
			@can('delete', $tag)
				<div class="col-xs-4 text-right">
					<form method="POST" action="{{route('delete_tag', ['tag' => $tag])}}">
						{{ csrf_field() }}
						{{method_field('DELETE')}}
						
						{{ Form::submit('Delete Tag', array('class' => 'btn btn-danger', 'onclick' =>'ConfirmDelete(event)')) }}
					</form>
				</div>
			@endcan
		</div>
		
		<form method="POST" action="{{route('update_tag', ['tag' => $tag])}}" enctype="multipart/form-data">
			{{ csrf_field() }}
			{{method_field('PATCH')}}
					
			@include('partials.tagObjects.tag-object-input', 
			[
				'tagObject' => $tag, 
				'child' => 'tag_child',
				'namePlaceholder' => 'constants.placeholders.tagObjects.tag.name', 
				'shortDescriptionPlaceholder' => 'constants.placeholders.tagObjects.tag.shortDescription',
				'descriptionPlaceholder' => 'constants.placeholders.tagObjects.tag.description', 
				'sourcePlaceholder' => 'constants.placeholders.tagObjects.tag.source',
				'childPlaceholder' => 'constants.placeholders.tagObjects.tag.child'
			])
			
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
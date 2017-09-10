@extends('layouts.app')

@section('title')
Edit Scanalator - {{{$scanalator->name}}}
@endsection

@section('head')
	<script src="/js/autocomplete/scanalator.js"></script>
	<script src="/js/confirmdelete.js"></script>
@endsection

@section('content')
<div class="container">
	@can('update', $scanalator)
	
	<div class="row">
		<div class="col-xs-8"><h1>Edit Scanalator</h1></div>
		@can('delete', $scanalator)
			<div class="col-xs-4 text-right">
				<form method="POST" action="{{route('delete_scanalator', ['scanalator' => $scanalator])}}">
					{{ csrf_field() }}
					{{method_field('DELETE')}}
					
					{{ Form::submit('Delete Scanalator', array('class' => 'btn btn-danger', 'onclick' =>'ConfirmDelete(event)')) }}
				</form>
			</div>
		@endcan
	</div>
	<form method="POST" action="{{route('update_scanalator', ['scanalator' => $scanalator])}}" enctype="multipart/form-data">
		{{ csrf_field() }}
		{{method_field('PATCH')}}
				
		@include('partials.tagObjects.tag-object-input', 
		[
			'tagObject' => $scanalator, 
			'child' => 'scanalator_child',
			'namePlaceholder' => $configurations['name'], 
			'shortDescriptionPlaceholder' => $configurations['shortDescription'],
			'descriptionPlaceholder' => $configurations['description'], 
			'sourcePlaceholder' => $configurations['source'],
			'childPlaceholder' => $configurations['child']
		])
		
		{{ Form::submit('Update Scanalator', array('class' => 'btn btn-primary')) }}
	</form>
	@endcan
	
	@cannot('update', $scanalator)
		<h1>Error</h1>
		<div class="alert alert-danger" role="alert">
			User does not have the correct permissions in order to edit scanalator.
		</div>
	@endcan
</div>
@endsection

@section('footer')

@endsection
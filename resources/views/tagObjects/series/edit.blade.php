@extends('layouts.app')

@section('title')
	Edit Series - {{{$series->name}}}
@endsection

@section('head')
	<script src="/js/autocomplete/series.js"></script>
	<script src="/js/confirmdelete.js"></script>
@endsection

@section('content')
<div class="container">
	@can('update', $series)
		
		<div class="row">
			<div class="col-xs-8"><h1>Edit Series</h1></div>
			@can('delete', $series)
				<div class="col-xs-4 text-right">
					<form method="POST" action="{{route('delete_series', ['series' => $series])}}">
						{{ csrf_field() }}
						{{method_field('DELETE')}}
						
						{{ Form::submit('Delete Series', array('class' => 'btn btn-danger', 'onclick' =>'ConfirmDelete(event)')) }}
					</form>
				</div>
			@endcan
		</div>
		
		<form method="POST" action="{{route('update_series', ['series' => $series])}}" enctype="multipart/form-data">
			{{ csrf_field() }}
			{{method_field('PATCH')}}
					
			@include('partials.tagObjects.tag-object-input', 
			[
				'tagObject' => $series, 
				'child' => 'series_child',
				'namePlaceholder' => 'constants.placeholders.tagObjects.series.name',
				'shortDescriptionPlaceholder' => 'constants.placeholders.tagObjects.series.shortDescription',				
				'descriptionPlaceholder' => 'constants.placeholders.tagObjects.series.description', 
				'sourcePlaceholder' => 'constants.placeholders.tagObjects.series.source',
				'childPlaceholder' => 'constants.placeholders.tagObjects.series.child',
				'nameHelpPlaceholder' => 'constants.placeholders.tagObjects.series.nameHelp',
				'shortDescriptionHelpPlaceholder' => 'constants.placeholders.tagObjects.series.shortDescriptionHelp',
				'descriptionHelpPlaceholder' => 'constants.placeholders.tagObjects.series.descriptionHelp',
				'sourceHelpPlaceholder' => 'constants.placeholders.tagObjects.series.sourceHelp',
				'childHelpPlaceholder' => 'constants.placeholders.tagObjects.series.childHelp'

			])
			
			{{ Form::submit('Update Series', array('class' => 'btn btn-primary')) }}
		</form>
	@endcan
	
	@cannot('update', $series)
		<h1>Error</h1>
		<div class="alert alert-danger" role="alert">
			User does not have the correct permissions in order to edit series.
		</div>
	@endcan
</div>
@endsection

@section('footer')

@endsection
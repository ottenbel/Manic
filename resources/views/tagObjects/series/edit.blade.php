@extends('layouts.app')

@section('title')
	Edit Series - {{{$series->name}}}
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	@can('update', $series)
		<h1>Edit Series</h1>
		
		<form method="POST" action="{{route('update_series', ['series' => $series])}}" enctype="multipart/form-data">
			{{ csrf_field() }}
			{{method_field('PATCH')}}
					
			@include('partials.tag-object-input', ['tagObject' => $series, 'namePlaceholder' => 'constants.placeholders.tagObjects.series.name', 'descriptionPlaceholder' => 'constants.placeholders.tagObjects.series.description', 'sourcePlaceholder' => 'constants.placeholders.tagObjects.series.source'])
			
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
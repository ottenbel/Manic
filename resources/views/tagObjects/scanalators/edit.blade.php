@extends('layouts.app')

@section('title')
Edit Scanalator - {{{$scanalator->name}}}
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	@can('update', $scanalator)
	<h1>Edit Scanalator</h1>
	
	<form method="POST" action="{{route('update_scanalator', ['scanalator' => $scanalator])}}" enctype="multipart/form-data">
		{{ csrf_field() }}
		{{method_field('PATCH')}}
				
		@include('partials.tag-object-input', ['tagObject' => $scanalator])
		
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
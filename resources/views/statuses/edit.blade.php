@extends('layouts.app')

@section('title')
	Edit Status - {{$status->name}}
@endsection

@section('head')
	<script src="/js/confirmdelete.js"></script>
@endsection

@section('content')
<div class="container">
	@can('update', $status)
		<div class="row">
			<div class="col-xs-8"><h1>Edit Status</h1></div>
			@can('delete', $status)
				<div class="col-xs-4 text-right">
					<form method="POST" action="{{route('delete_status', ['status' => $status])}}">
						{{ csrf_field() }}
						{{method_field('DELETE')}}
						
						{{ Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete Status', array('type' => 'submit', 'class' => 'btn btn-danger', 'onclick' =>'ConfirmDelete(event)')) }}
					</form>
				</div>
			@endcan
		</div>
		
		<form method="POST" action="{{route('update_status', ['status' => $status])}}" enctype="multipart/form-data">
			{{ csrf_field() }}
			{{method_field('PATCH')}}
			
			@include('partials.statuses.input', array('configurations' => $configurations, 'status' => $status))
			
			{{ Form::submit('Edit Status', array('class' => 'btn btn-primary')) }}
		</form>
	@endcan
	
	@cannot('update', $status)
		<h1>Error</h1>
		<div class="alert alert-danger" role="alert">
			User does not have the correct permissions in order to edit {{$status->name}}.
		</div>
	@endcan
</div>
@endsection

@section('footer')

@endsection
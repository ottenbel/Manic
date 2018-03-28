@extends('layouts.app')

@section('title')
	Edit Language - {{$language->name}}
@endsection

@section('head')
	<script src="/js/confirmdelete.js"></script>
@endsection

@section('content')
<div class="container">
	@can('update', $language)
		<div class="row">
			<div class="col-xs-8"><h1>Edit Language</h1></div>
			@can('delete', $language)
				<div class="col-xs-4 text-right">
					<form method="POST" action="{{route('delete_language', ['language' => $language])}}">
						{{ csrf_field() }}
						{{method_field('DELETE')}}
						
						{{ Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete Language', array('type' => 'submit', 'class' => 'btn btn-danger', 'onclick' =>'ConfirmDelete(event)')) }}
					</form>
				</div>
			@endcan
		</div>
		
		<form method="POST" action="{{route('update_language', ['language' => $language])}}" enctype="multipart/form-data">
			{{ csrf_field() }}
			{{method_field('PATCH')}}
			
			@include('partials.language.input', array('configurations' => $configurations, 'language' => $language))
			
			{{ Form::submit('Edit Language', array('class' => 'btn btn-primary')) }}
		</form>
	@endcan
	
	@cannot('update', $language)
		<h1>Error</h1>
		<div class="alert alert-danger" role="alert">
			User does not have the correct permissions in order to edit {{$language->name}}.
		</div>
	@endcan
</div>
@endsection

@section('footer')

@endsection
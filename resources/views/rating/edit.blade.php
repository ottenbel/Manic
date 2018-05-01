@extends('layouts.app')

@section('title')
	Edit Rating - {{$rating->name}}
@endsection

@section('head')
	<script src="/js/confirmdelete.js"></script>
@endsection

@section('content')
<div class="container">
	@can('update', $rating)
		<div class="row">
			<div class="col-xs-8"><h1>Edit Rating</h1></div>
			@can('delete', $rating)
				<div class="col-xs-4 text-right">
					<form method="POST" action="{{route('delete_rating', ['rating' => $rating])}}">
						{{ csrf_field() }}
						{{method_field('DELETE')}}
						
						{{ Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete Rating', array('type' => 'submit', 'class' => 'btn btn-danger', 'onclick' =>'ConfirmDelete(event)')) }}
					</form>
				</div>
			@endcan
		</div>
		
		<form method="POST" action="{{route('update_rating', ['rating' => $rating])}}" enctype="multipart/form-data">
			{{ csrf_field() }}
			{{method_field('PATCH')}}
			
			@include('partials.rating.input', array('configurations' => $configurations, 'rating' => $rating))
			
			{{ Form::submit('Edit Rating', array('class' => 'btn btn-primary')) }}
		</form>
	@endcan
	
	@cannot('update', $rating)
		<h1>Error</h1>
		<div class="alert alert-danger" role="alert">
			User does not have the correct permissions in order to edit {{$rating->name}}.
		</div>
	@endcan
</div>
@endsection

@section('footer')

@endsection
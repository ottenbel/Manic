@extends('layouts.app')

@section('title')
	{{$rating->name}}
@endsection

@section('head')
	<script src="/js/confirmdelete.js"></script>
@endsection

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<h2>{{{$rating->name}}}</h2>
			</div>
			<div class="row col-md-4">
				<span style="float:right">
					<a class="btn btn-success btn-sm" href="{{route('index_collection', ['search' => 'rating:' . $rating->name])}}"><i class="fa fa-search" aria-hidden="true"></i> Search</a>
				</span>
				@if(Auth::check() && (Auth::user()->can('delete', $rating)) && (Auth::user()->cannot('update', $rating)))
					<span style="float:right">
						<form method="POST" action="{{route('delete_rating', ['rating' => $rating])}}">
							{{ csrf_field() }}
							{{method_field('DELETE')}}
							
							{{ Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete Rating', array('type' => 'submit', 'class' => 'btn btn-sm btn-danger', 'onclick' =>'ConfirmDelete(event)')) }}
						</form>
					</span>
				@endif
			</div>
		</div>
		
		@if(($rating->priority != null) && ($rating->priority != ""))
			<div><strong>Priority:</strong></div>
			<div id="{{$rating->name}}_description">
				{{$rating->priority}}
			</div>
			<br/>
		@endif
		
		<div><strong>Usage:</strong></div>
		<div>
			@if($usageCount > 0)
				{{$usageCount}} collections are set with {{$rating->name}} as the associated rating.
			@else
				{{$rating->name}} is not associated with any collections.
			@endif
		</div>
	</div>
@endsection

@section('footer')

@endsection
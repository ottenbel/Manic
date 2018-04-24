@extends('layouts.app')

@section('title')
	{{$status->name}}
@endsection

@section('head')
	<script src="/js/confirmdelete.js"></script>
@endsection

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<h2>{{{$status->name}}}</h2>
			</div>
			<div class="row col-md-4">
				<span style="float:right">
					<a class="btn btn-success btn-sm" href="{{route('index_collection', ['search' => 'status:' . $status->name])}}"><i class="fa fa-search" aria-hidden="true"></i> Search</a>
				</span>
				@if(Auth::check() && (Auth::user()->can('delete', $status)) && (Auth::user()->cannot('update', $status)))
					<span style="float:right">
						<form method="POST" action="{{route('delete_status', ['status' => $status])}}">
							{{ csrf_field() }}
							{{method_field('DELETE')}}
							
							{{ Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete Status', array('type' => 'submit', 'class' => 'btn btn-sm btn-danger', 'onclick' =>'ConfirmDelete(event)')) }}
						</form>
					</span>
				@endif
			</div>
		</div>
		
		@if(($status->priority != null) && ($status->priority != ""))
			<div><strong>Priority:</strong></div>
			<div id="{{$status->name}}_description">
				{{$status->priority}}
			</div>
			<br/>
		@endif
		
		<div><strong>Usage:</strong></div>
		<div>
			@if($usageCount > 0)
				{{$usageCount}} collections are set with {{$status->name}} as the associated status.
			@else
				{{$status->name}} is not associated with any collections.
			@endif
		</div>
	</div>
@endsection

@section('footer')

@endsection
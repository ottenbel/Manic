@extends('layouts.app')

@section('title')
	Pagination Configuration
@endsection

@section('head')
	<script src="/js/confirmdelete.js"></script>
@endsection

@section('content')
<div class="container">
	<div class="row">
			@if(Route::is('user_dashboard_configuration_pagination'))
				<div class="col-xs-8"><h1>Edit User Pagination Configuration</h1></div>
			@elseif(Route::is('admin_dashboard_configuration_pagination'))
				<div class="col-xs-8"><h1>Edit Site Pagination Configuration</h1></div>
			@endif
			
			@if(Route::is('user_dashboard_configuration_pagination'))
				@can('reset', App\Models\Configuration\ConfigurationPagination::class)
					<div class="col-xs-4 text-right">
						<form method="POST" action="{{route('user_reset_configuration_pagination')}}">
							{{ csrf_field() }}
							{{method_field('DELETE')}}
							
							{{ Form::submit('Reset Configuration To Site Defaults', array('class' => 'btn btn-danger', 'onclick' =>'ConfirmDelete(event)')) }}
						</form>
					</div>
				@endcan
			@endif
		</div>

    <div class="form-group">
		@if(Route::is('admin_dashboard_configuration_pagination'))
			<form method="POST" action="{{route('admin_update_configuration_pagination')}}" enctype="multipart/form-data">
		@elseif(Route::is('user_dashboard_configuration_pagination'))
			<form method="POST" action="{{route('user_update_configuration_pagination')}}" enctype="multipart/form-data">
		@endif
			{{ csrf_field() }}
			{{method_field('PATCH')}}
			
			<button id="pagination_value_button" class="closedAccordion" type="button">
				Pagination Configuration
			</button>
			<div id="pagination_value_panel" class="volume_panel">
				@foreach($paginationValues as $paginationValue)
					<div class="form-group">
						{{ Form::label("pagination_values[$paginationValue->key]", $paginationValue->key) }}
						<i class="fa fa-question-circle" aria-hidden="true" title="{{$paginationValue->description}}"></i>
						@if(Input::old('volume') == null)
							{{ Form::text("pagination_values[$paginationValue->key]", $paginationValue->value, array('class' => 'form-control', 'placeholder' => $paginationValue->value)) }}
						@else
							{{ Form::text("pagination_values[$paginationValue->key]", Input::old("pagination_values[$paginationValue->key]"), array('class' => 'form-control', 'placeholder' => $paginationValue->value)) }}
						@endif
						@if($errors->has("pagination_values.".$paginationValue->key))
							<div class ="alert alert-danger" id="paginationValueError">{{$errors->first("pagination_values.".$paginationValue->key)}}</div>
						@endif
					</div>
				@endforeach
			</div>
			<button id="pagination_value_helper_button" class="closedAccordion" type="button">
				Pagination Helper Configuration
			</button>
			<div id="pagination_value_helper_panel" class="volume_panel">
				@foreach($paginationValues as $paginationValue)
					<div class="form-group">
						{{ Form::label("pagination_values_helpers[$paginationValue->key]", "$paginationValue->key"."_helper") }}
						<i class="fa fa-question-circle" aria-hidden="true" title="{{$paginationValue->description}}"></i>
						@if(Input::old('volume') == null)
							{{ Form::text("pagination_values_helpers[$paginationValue->key]", $paginationValue->description, array('class' => 'form-control', 'placeholder' => $paginationValue->description)) }}
						@else
							{{ Form::text("pagination_values_helpers[$paginationValue->key]", Input::old("pagination_values_helpers[$paginationValue->key]"), array('class' => 'form-control', 'placeholder' => $paginationValue->description)) }}
						@endif
						@if($errors->has("pagination_values_helpers.".$paginationValue->key))
							<div class ="alert alert-danger" id="paginationValueError">{{$errors->first("pagination_values_helpers.".$paginationValue->key)}}</div>
						@endif
					</div>
				@endforeach
			</div>
			<br/>
			<div class="text-right">
			{{ Form::submit('Update Configuration Settings', array('class' => 'btn btn-primary')) }}
			</div>
		</form>
	</div>
</div>
@endsection

@section('footer')

@endsection
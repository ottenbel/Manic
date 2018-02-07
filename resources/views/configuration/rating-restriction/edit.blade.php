@extends('layouts.app')

@section('title')
	Rating Restriction Configuration
@endsection

@section('head')
	<script src="/js/confirmdelete.js"></script>
@endsection

@section('content')
<div class="container">
	<div class="row">
			@if(Route::is('user_dashboard_configuration_rating_restriction'))
				<div class="col-xs-8"><h1>Edit User Rating Restriction Configuration</h1></div>
			@elseif(Route::is('admin_dashboard_configuration_rating_restriction'))
				<div class="col-xs-8"><h1>Edit Site Rating Restriction Configuration</h1></div>
			@endif
			
			@if(Route::is('user_dashboard_configuration_rating_restriction'))
				@can('reset', App\Models\Configuration\ConfigurationRatingRestriction::class)
					<div class="col-xs-4 text-right">
						<form method="POST" action="{{route('user_reset_configuration_rating_restriction')}}">
							{{ csrf_field() }}
							{{method_field('DELETE')}}
							
							{{ Form::submit('Reset Configuration To Site Defaults', array('class' => 'btn btn-danger', 'onclick' =>'ConfirmDelete(event)')) }}
						</form>
					</div>
				@endcan
			@endif
		</div>

    <div class="form-group">
		@if(Route::is('admin_dashboard_configuration_rating_restriction'))
			<form method="POST" action="{{route('admin_update_configuration_rating_restriction')}}" enctype="multipart/form-data">
		@elseif(Route::is('user_dashboard_configuration_rating_restriction'))
			<form method="POST" action="{{route('user_update_configuration_rating_restriction')}}" enctype="multipart/form-data">
		@endif
			{{ csrf_field() }}
			{{method_field('PATCH')}}
			
			<button id="rating_restriction_value_button" class="closedAccordion" type="button">
				Rating Restriction Configuration
			</button>
			<div id="rating_restriction_value_panel" class="volume_panel">
				@foreach($ratingRestrictions as $ratingRestriction)
					<div class="form-group">
						{{ Form::label("rating_restriction_values[$ratingRestriction->rating_id]", $ratingRestriction->rating->name) }}
						@if(Input::old("rating_restriction_values[$ratingRestriction->rating_id]") == null)
							{{ Form::checkbox("rating_restriction_values[$ratingRestriction->rating_id]", null, $ratingRestriction->display)}}
						@else
							{{ Form::checkbox("rating_restriction_values[$ratingRestriction->rating_id]", null, Input::old("rating_restriction_values[$ratingRestriction->rating_id]"))}}
						@endif
						
						@if($errors->has("rating_restriction_values.".$ratingRestriction->rating_id))
							<div class ="alert alert-danger" id="paginationValueError">{{$errors->first("rating_restriction_values.".$ratingRestriction->rating_id)}}</div>
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
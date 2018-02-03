@extends('layouts.app')

@section('title')
	Placeholder Configuration
@endsection

@section('head')
	<script src="/js/confirmdelete.js"></script>
@endsection

@section('content')
<div class="container">
	<div class="row">
			@if(Route::is('user_dashboard_configuration_placeholders'))
				<div class="col-xs-8"><h1>Edit User Placeholder Configuration</h1></div>
			@elseif(Route::is('admin_dashboard_configuration_placeholders'))
				<div class="col-xs-8"><h1>Edit Site Placeholder Configuration</h1></div>
			@endif
			
			@if(Route::is('user_dashboard_configuration_placeholders'))
				@can('reset', App\Models\Configuration\ConfigurationPlaceholder::class)
					<div class="col-xs-4 text-right">
						<form method="POST" action="{{route('user_reset_configuration_placeholders')}}">
							{{ csrf_field() }}
							{{method_field('DELETE')}}
							
							{{ Form::submit('Reset Configuration To Site Defaults', array('class' => 'btn btn-danger', 'onclick' =>'ConfirmDelete(event)')) }}
						</form>
					</div>
				@endcan
			@endif
		</div>

    <div class="form-group">
		@if(Route::is('admin_dashboard_configuration_placeholders'))
			<form method="POST" action="{{route('admin_update_configuration_placeholders')}}" enctype="multipart/form-data">
		@elseif(Route::is('user_dashboard_configuration_placeholders'))
			<form method="POST" action="{{route('user_update_configuration_placeholders')}}" enctype="multipart/form-data">
		@endif
			{{ csrf_field() }}
			{{method_field('PATCH')}}
			
			@include('partials.configuration.placeholder.input', array('section' => 'Artist', 'items' => $artists))
			@include('partials.configuration.placeholder.input', array('section' => 'Character', 'items' => $characters))
			@include('partials.configuration.placeholder.input', array('section' => 'Scanalator', 'items' => $scanalators))
			@include('partials.configuration.placeholder.input', array('section' => 'Series', 'items' => $series))
			@include('partials.configuration.placeholder.input', array('section' => 'Tags', 'items' => $tags))
			@include('partials.configuration.placeholder.input', array('section' => 'Collection', 'items' => $collections))
			@include('partials.configuration.placeholder.input', array('section' => 'Volume', 'items' => $volumes))
			@include('partials.configuration.placeholder.input', array('section' => 'Chapter', 'items' => $chapters))
			@include('partials.configuration.placeholder.input', array('section' => 'Permission', 'items' => $permissions))
			@include('partials.configuration.placeholder.input', array('section' => 'Role', 'items' => $roles))
			
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
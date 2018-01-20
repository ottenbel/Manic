@extends('layouts.app')

@section('title')
	User Configuration
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	@can('Edit Personal Pagination Settings')
		<div class="row">
			<a href="{{route('user_dashboard_configuration_pagination')}}"><b>Pagination</b></a>
		</div>
	@endcan
	
	@can('Edit Personal Placeholder Settings')
		<div class="row">
			<a href="{{route('user_dashboard_configuration_placeholders')}}"><b>Placeholders</b></a>
		</div>
	@endcan
	
	@can('Edit Personal Rating Restriction Settings')
		<div class="row">
			<a href="{{route('user_dashboard_configuration_rating_restriction')}}"><b>Rating Restrictions</b></a>
		</div>
	@endcan
</div>
@endsection

@section('footer')

@endsection
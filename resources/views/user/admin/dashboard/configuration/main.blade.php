@extends('layouts.app')

@section('title')
	Admin Configuration
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	@can('Edit Global Pagination Settings')
		<div class="row">
			<a href="{{route('admin_dashboard_configuration_pagination')}}"><b>Pagination</b></a>
		</div>
	@endcan
	
	@can('Edit Personal Placeholder Settings')
		<div class="row">
			<a href="{{route('admin_dashboard_configuration_placeholders')}}"><b>Placeholders</b></a>
		</div>
	@endcan
	
	@can('Edit Global Rating Restriction Settings')
		<div class="row">
			<a href="{{route('admin_dashboard_configuration_rating_restriction')}}"><b>Rating Restrictions</b></a>
		</div>
	@endcan
</div>
@endsection

@section('footer')

@endsection
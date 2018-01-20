@extends('layouts.app')

@section('title')
	User Dashboard
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	@if((Auth::user()->can('Edit Personal Pagination Settings')) || (Auth::user()->can('Edit Personal Placeholder Settings')) || (Auth::user()->can('Edit Personal Rating Restriction Settings')))
		<div class="row">
			<a href="{{route('user_dashboard_configuration_main')}}"><b>Configuration</b></a>
		</div>
	@endif
	
	@include('partials.user.admin.user.rolesAndPermissions.show-content', 
	[
		'user' => Auth::user(),
		'roles' => Auth::user()->roles, 
		'permissions' => Auth::user()->permissions
	])			
</div>
@endsection

@section('footer')

@endsection
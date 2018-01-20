@extends('layouts.app')

@section('title')
	User Dashboard
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
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
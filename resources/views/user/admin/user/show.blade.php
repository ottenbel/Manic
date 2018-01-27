@extends('layouts.app')

@section('title')
	User - {{$user->name}}
@endsection

@section('head')
	<script src="/js/confirmdelete.js"></script>
@endsection

@section('content')
	<div class="container">
		@can('View User')
			<h1>{{$user->name}}</h1>
				
			@include('partials.user.admin.user.rolesAndPermissions.show-content', 
			[
				'roles' => $roles, 
				'permissions' => $permissions
			])
		@endcan
		@cannot('View User')
			<h1>Error</h1>
			<div class="alert alert-danger" role="alert">
				User does not have the correct permissions in order to view the user.
			</div>
		@endcan
	</div>
@endsection

@section('footer')

@endsection
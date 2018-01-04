@extends('layouts.app')

@section('title')
	Role - {{$role->name}}
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	<div class="col-md-12">
		<h1>{{$role->name}}</h1>
	</div>
	@if($role->permissions->count() > 0)
		<div class="col-md-12">
			<h2>Permissions</h2>
		</div>
		<div>
			@include('partials.rolesAndPermissions.permissions.permission-index', ['permissions' => $role->permissions])
		</div>
	@endif
</div>
@endsection

@section('footer')

@endsection